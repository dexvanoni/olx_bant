<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-people"></i> Gerenciar Usuários
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?route=admin/usuarios/criar" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Usuário
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
                        <th>Usuário</th>
                        <th>Email</th>
                        <th>Setor</th>
                        <th>Nível</th>
                        <th>Status</th>
                        <th>Data Criação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($usuario['nome']) ?></strong>
                        </td>
                        <td>
                            <code><?= htmlspecialchars($usuario['usuario']) ?></code>
                        </td>
                        <td>
                            <a href="mailto:<?= htmlspecialchars($usuario['email']) ?>">
                                <?= htmlspecialchars($usuario['email']) ?>
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-info"><?= htmlspecialchars($usuario['setor']) ?></span>
                        </td>
                        <td>
                            <?php if ($usuario['nivel'] === 'admin'): ?>
                                <span class="badge bg-danger">Administrador</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Setor</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($usuario['ativo']): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?= date('d/m/Y H:i', strtotime($usuario['created_at'])) ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?route=admin/usuarios/editar&id=<?= $usuario['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($usuario['id'] != $_SESSION['admin_id']): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['nome']) ?>')"
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
    if (confirm(`Tem certeza que deseja excluir o usuário "${nome}"?`)) {
        window.location.href = `index.php?route=admin/usuarios/excluir&id=${id}`;
    }
}
</script> 