<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-search"></i> Detalhes do Resgate
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?route=admin/resgates" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-light">
        <strong>Material</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <?php $fotos = json_decode($resgate['fotos'], true) ?: []; ?>
                <?php if (!empty($fotos)): ?>
                    <img src="<?= UPLOAD_PATH . $fotos[0] ?>" class="img-fluid rounded mb-2" style="max-height:180px; object-fit:cover;" alt="Foto do material">
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <p><strong>Descrição:</strong> <?= htmlspecialchars($resgate['material_descricao'] ?? '') ?></p>
                <p><strong>Local de Retirada:</strong> <?= htmlspecialchars($resgate['local_retirada'] ?? '') ?></p>
                <p><strong>Nº BMP:</strong> <?= htmlspecialchars($resgate['numero_bmp'] ?? '') ?></p>
                <p><strong>Dono da Carga:</strong> <?= htmlspecialchars($resgate['dono_carga'] ?? '') ?></p>
                <p><strong>Tipo:</strong> <?= htmlspecialchars($resgate['tipo_material'] ?? '') ?></p>
                <p><strong>Condição:</strong> <?= ucfirst($resgate['condicao_item'] ?? '') ?></p>
                <p><strong>Responsável:</strong> <?= htmlspecialchars($resgate['admin_nome'] ?? '') ?> (<?= htmlspecialchars($resgate['admin_setor'] ?? '') ?>)</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-light">
        <strong>Dados do Resgate</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Quantidade Resgatada:</strong> <?= $resgate['quantidade_resgatada'] ?></p>
                <p><strong>Status:</strong> <span class="badge bg-<?= $resgate['status'] === 'retirado' ? 'success' : ($resgate['status'] === 'expirado' ? 'danger' : 'warning') ?>"><?= ucfirst($resgate['status']) ?></span></p>
                <p><strong>Data do Resgate:</strong> <?= date('d/m/Y H:i', strtotime($resgate['data_resgate'])) ?></p>
                <p><strong>Data Limite:</strong> <?= $resgate['data_limite'] ? date('d/m/Y H:i', strtotime($resgate['data_limite'])) : '-' ?></p>
                <p><strong>Data de Retirada:</strong> <?= $resgate['data_retirada'] ? date('d/m/Y H:i', strtotime($resgate['data_retirada'])) : '-' ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Posto/Graduação:</strong> <?= htmlspecialchars($resgate['posto_graduacao'] ?? '') ?></p>
                <p><strong>Nome de Guerra:</strong> <?= htmlspecialchars($resgate['nome_guerra'] ?? '') ?></p>
                <p><strong>Contato:</strong> <?= htmlspecialchars($resgate['contato'] ?? '') ?></p>
                <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($resgate['email'] ?? '') ?>"><?= htmlspecialchars($resgate['email'] ?? '') ?></a></p>
                <p><strong>Esquadrão:</strong> <?= htmlspecialchars($resgate['esquadrao'] ?? '') ?></p>
                <p><strong>Setor:</strong> <?= htmlspecialchars($resgate['setor'] ?? '') ?></p>
            </div>
        </div>
    </div>
</div>

<a href="index.php?route=admin/resgates" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Voltar para Resgates
</a> 