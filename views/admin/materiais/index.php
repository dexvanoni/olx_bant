<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-box"></i> Gerenciar Materiais
            </h1>
            <p class="text-muted">Cadastre e gerencie os materiais disponíveis para resgate</p>
        </div>
        <a href="index.php?route=admin/materiais/criar" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Material
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Descrição</th>
                        <th>Local</th>
                        <th>BMP</th>
                        <th>Dono</th>
                        <th>Condição</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Responsável</th>
                        <th>Status</th>
                        <th>Resgates</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materiais as $material): ?>
                    <tr<?php if ($material['total_resgates'] > $material['quantidade_total']): ?> class="table-danger"<?php endif; ?>>
                        <td><?= $material['id'] ?></td>
                        <td>
                            <?php 
                            $fotos = json_decode($material['fotos'], true) ?: [];
                            $foto_principal = !empty($fotos) ? $fotos[0] : null;
                            ?>
                            <?php if ($foto_principal): ?>
                                <img src="<?= UPLOAD_PATH . $foto_principal ?>" 
                                     class="rounded" 
                                     style="width: 50px; height: 50px; object-fit: cover;"
                                     alt="Foto do material"
                                     onerror="this.src='assets/img/placeholder.jpg'">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($material['descricao']) ?></strong>
                            <?php if (count($fotos) > 1): ?>
                                <br><small class="text-muted"><?= count($fotos) ?> fotos</small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($material['local_retirada']) ?></td>
                        <td><?= htmlspecialchars($material['numero_bmp']) ?></td>
                        <td><?= htmlspecialchars($material['dono_carga']) ?></td>
                        <td>
                            <?php
                            $condicao_cores = [
                                'excelente' => 'success',
                                'bom' => 'info',
                                'regular' => 'warning',
                                'ruim' => 'danger'
                            ];
                            $cor = $condicao_cores[$material['condicao_item']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $cor ?>"><?= ucfirst($material['condicao_item']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($material['tipo_material']) ?></td>
                        <td>
                            <?php if ($material['quantidade_disponivel'] <= 0): ?>  
                                <strong>0</strong> / <?= $material['quantidade_total'] ?>
                            <?php else: ?>
                                <strong><?= $material['quantidade_disponivel'] ?></strong> / <?= $material['quantidade_total'] ?>
                            <?php endif; ?>
                            <?php if ($material['quantidade_disponivel'] < $material['quantidade_total']): ?>
                                <br><small class="text-warning"><?= $material['quantidade_total'] - $material['quantidade_disponivel'] ?> resgates</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?= htmlspecialchars($material['setor_nome'] ?? 'N/A') ?>
                            </small>
                        </td>
                        <td>
                            <?php
                            $status_cores = [
                                'disponivel' => 'success',
                                'aguardando_retirada' => 'warning',
                                'resgatado' => 'info'
                            ];
                            $cor = $status_cores[$material['status']] ?? 'secondary';
                            ?>
                            
                            <?php if ($material['quantidade_disponivel'] <= 0): ?>
                                <small class="text-muted">
                                <i class="bi bi-box"></i> <strong>Material em disputa</strong>
                                </small>
                            <?php else: ?>
                                <span class="badge bg-<?= $cor ?>"><?= ucfirst(str_replace('_', ' ', $material['status'])) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($material['total_resgates'] > 0): ?>
                                <span class="badge bg-info"><?= $material['total_resgates'] ?></span>
                                <?php if ($material['resgates_pendentes'] > 0): ?>
                                    <br><small class="text-warning"><?= $material['resgates_pendentes'] ?> pendentes</small>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?route=admin/materiais/editar&id=<?= $material['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        title="Excluir"
                                        onclick="confirmDelete(<?= $material['id'] ?>, '<?= htmlspecialchars($material['descricao']) ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <?php if ($material['total_resgates'] > $material['quantidade_total']): ?>
                                    <button type="button" 
                                            class="btn btn-sm btn-warning" 
                                            title="Ver Resgates"
                                            onclick="abrirModalResgates(<?= $material['id'] ?>, '<?= htmlspecialchars(addslashes($material['descricao'])) ?>')">
                                        <i class="bi bi-list-ul"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 

<!-- Modal de Resgates -->
<div class="modal fade" id="modalResgates" tabindex="-1" aria-labelledby="modalResgatesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalResgatesLabel">Resgates do Material</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div id="resgatesLoading" class="text-center my-3">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Carregando...</span>
          </div>
        </div>
        <div id="resgatesConteudo" style="display:none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>
function abrirModalResgates(materialId, descricao) {
    document.getElementById('modalResgatesLabel').textContent = 'Resgates do Material: ' + descricao;
    document.getElementById('resgatesLoading').style.display = '';
    document.getElementById('resgatesConteudo').style.display = 'none';
    var modal = new bootstrap.Modal(document.getElementById('modalResgates'));
    modal.show();
    // Buscar resgates via AJAX
    fetch('index.php?route=admin/materiais/getResgatesAjax&id=' + materialId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('resgatesLoading').style.display = 'none';
            if (data.success && Array.isArray(data.resgates)) {
                let html = '<table class="table table-bordered table-sm">';
                html += '<thead><tr><th>Solicitante</th><th>Data/Hora</th><th>Status</th><th>Justificativa</th><th>Ação</th></tr></thead><tbody>';
                data.resgates.forEach(resgate => {
                    html += '<tr>' +
                        '<td>' + resgate.posto_graduacao + ' ' + resgate.nome_usuario + ' - ' + resgate.esquadrao + '/' + resgate.setor + '/' + resgate.contato + '</td>' +
                        '<td>' + resgate.data_solicitacao + '</td>' +
                        '<td>' + resgate.status + '</td>' +
                        '<td>' + (resgate.justificativa || '-') + '</td>' +
                        '<td>';
                    if (resgate.status === 'aguardando_retirada' || resgate.status === 'em_disputa') {
                        html += '<button class="btn btn-success btn-sm" onclick="marcarRetirado(' + resgate.id + ',' + materialId + ')">Marcar Retirado</button>';
                    }
                    html += '</td></tr>';
                });
                html += '</tbody></table>';
                document.getElementById('resgatesConteudo').innerHTML = html;
                document.getElementById('resgatesConteudo').style.display = '';
            } else {
                document.getElementById('resgatesConteudo').innerHTML = '<div class="alert alert-warning">Nenhum resgate encontrado.</div>';
                document.getElementById('resgatesConteudo').style.display = '';
            }
        })
        .catch(() => {
            document.getElementById('resgatesLoading').style.display = 'none';
            document.getElementById('resgatesConteudo').innerHTML = '<div class="alert alert-danger">Erro ao buscar resgates.</div>';
            document.getElementById('resgatesConteudo').style.display = '';
        });
}

function marcarRetirado(resgateId, materialId) {
    if (!confirm('Tem certeza que deseja marcar este resgate como retirado? Os outros serão cancelados.')) return;
    fetch('index.php?route=admin/materiais/marcarRetiradoAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ resgate_id: resgateId, material_id: materialId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Resgate marcado como retirado e demais cancelados!');
            abrirModalResgates(materialId, ''); // Atualiza lista
        } else {
            alert('Erro: ' + (data.message || 'Não foi possível marcar como retirado.'));
        }
    })
    .catch(() => {
        alert('Erro ao processar a solicitação.');
    });
}
</script> 