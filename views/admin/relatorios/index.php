<?php
// Espera variáveis: $usuarios, $setores, $materiais
?>
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3"><i class="bi bi-file-earmark-text"></i> Relatórios</h1>
        <p class="text-muted">Relatório quantitativo do sistema — filtros e exportação PDF.</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form id="filtrosRelatorio" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Data início</label>
                <input type="date" name="start" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Data fim</label>
                <input type="date" name="end" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Usuário</label>
                <select name="usuario" class="form-control">
                    <option value="">Todos</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Setor</label>
                <select name="setor" class="form-control">
                    <option value="">Todos</option>
                    <?php foreach ($setores as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Material</label>
                <select name="material" class="form-control">
                    <option value="">Todos</option>
                    <?php foreach ($materiais as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['descricao']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 text-end">
                <button type="button" id="btnGerar" class="btn btn-primary">Gerar Relatório</button>
                <button type="button" id="btnExportPdf" class="btn btn-outline-secondary">Exportar PDF</button>
            </div>
        </form>
    </div>
</div>

<!-- KPIs e Gráfico -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Total de Resgates</h6>
            <h3 id="kpi_total_resgates">0</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Materiais Disponíveis</h6>
            <h3 id="kpi_materiais_disponiveis">0</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Materiais Retirados</h6>
            <h3 id="kpi_materiais_retirados">0</h3>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <canvas id="chartSeries" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabela detalhada -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Materiais entregues</h5>
                <div class="table-responsive">
                    <table class="table table-striped" id="tableDetalhes">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Material</th>
                                <th>Quantidade</th>
                                <th>Destinatário</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGerar = document.getElementById('btnGerar');
    const btnExportPdf = document.getElementById('btnExportPdf');
    const form = document.getElementById('filtrosRelatorio');
    const ctx = document.getElementById('chartSeries').getContext('2d');
    let chart = null;

    function fetchDataAndRender() {
        const params = new URLSearchParams(new FormData(form));
        fetch('index.php?route=admin/relatorios/data&' + params.toString())
            .then(r => r.json())
            .then(data => {
                if (!data.success) return alert('Erro ao buscar dados');
                document.getElementById('kpi_total_resgates').textContent = data.kpis.total_resgates || 0;
                document.getElementById('kpi_materiais_disponiveis').textContent = data.kpis.materiais_disponiveis || 0;
                document.getElementById('kpi_materiais_retirados').textContent = data.kpis.materiais_retirados || 0;

                // Series
                const labels = data.series.map(s => s.dia);
                const values = data.series.map(s => parseInt(s.total));
                if (chart) chart.destroy();
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{ label: 'Resgates', data: values, borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.1)' }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });

                // Tabela detalhes
                const tbody = document.querySelector('#tableDetalhes tbody');
                tbody.innerHTML = '';
                data.detalhes.forEach(d => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = '<td>' + (d.data_retirada || '-') + '</td>' +
                                   '<td>' + (d.descricao || '-') + '</td>' +
                                   '<td>' + (d.quantidade_resgatada || '-') + '</td>' +
                                   '<td>' + (d.posto_graduacao + ' ' + d.nome_guerra + ' - ' + d.esquadrao) + '</td>';
                    tbody.appendChild(tr);
                });
            });
    }

    btnGerar.addEventListener('click', fetchDataAndRender);

    btnExportPdf.addEventListener('click', function() {
        // Submeter por POST para exportPdf
        const formData = new FormData(form);
        // Se existir gráfico, converter para base64 e incluir no POST para o PDF
        let chartImage = '';
        try {
            if (chart && typeof chart.toBase64Image === 'function') {
                chartImage = chart.toBase64Image();
            }
        } catch (e) {
            console.warn('Erro ao gerar imagem do gráfico:', e);
        }

        const formEl = document.createElement('form');
        formEl.method = 'POST';
        formEl.action = 'index.php?route=admin/relatorios/exportPdf';
        formEl.target = '_blank';

        for (const [k,v] of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = k;
            input.value = v;
            formEl.appendChild(input);
        }
        if (chartImage) {
            const imgInput = document.createElement('input');
            imgInput.type = 'hidden';
            imgInput.name = 'chart_image';
            imgInput.value = chartImage;
            formEl.appendChild(imgInput);
        }

        document.body.appendChild(formEl);
        formEl.submit();
        formEl.remove();
    });
});
</script>

