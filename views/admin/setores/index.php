<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-building"></i> Gerenciar Setores
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?route=admin/setores/criar" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Setor
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Administradores</th>
                        <th>Status</th>
                        <th>Data Criação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($setores as $setor): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($setor['nome']) ?></strong>
                        </td>
                        <td>
                            <?= htmlspecialchars($setor['descricao'] ?? '') ?>
                        </td>
                        <td>
                            <span class="badge bg-info"><?= $setor['total_admins'] ?></span>
                        </td>
                        <td>
                            <?php if ($setor['ativo']): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?= date('d/m/Y H:i', strtotime($setor['created_at'])) ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?route=admin/setores/editar&id=<?= $setor['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($setor['total_admins'] == 0): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete(<?= $setor['id'] ?>, '<?= htmlspecialchars($setor['nome']) ?>')"
                                        title="Excluir">
                                    <i class="bi bi-trash"></i>
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

<script>
function confirmDelete(id, nome) {
    if (confirm(`Tem certeza que deseja excluir o setor "${nome}"?`)) {
        window.location.href = `index.php?route=admin/setores/excluir&id=${id}`;
    }
}
</script> 