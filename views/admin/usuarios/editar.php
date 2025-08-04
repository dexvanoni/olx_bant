<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-person-gear"></i> Editar Usuário
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?route=admin/usuarios" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?route=admin/usuarios/editar&id=<?= $usuario['id'] ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">
                        <i class="bi bi-person"></i> Nome Completo *
                    </label>
                    <input type="text" class="form-control" id="nome" name="nome" required
                           value="<?= htmlspecialchars($usuario['nome']) ?>"
                           placeholder="Nome completo do usuário">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="usuario" class="form-label">
                        <i class="bi bi-person-badge"></i> Nome de Usuário
                    </label>
                    <input type="text" class="form-control" id="usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>" readonly>
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> Nome de usuário não pode ser alterado
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Email *
                    </label>
                    <input type="email" class="form-control" id="email" name="email" required
                           value="<?= htmlspecialchars($usuario['email']) ?>"
                           placeholder="email@bant.com">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="senha" class="form-label">
                        <i class="bi bi-lock"></i> Nova Senha
                    </label>
                    <input type="password" class="form-control" id="senha" name="senha"
                           placeholder="Deixe em branco para manter a senha atual">
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> Preencha apenas se quiser alterar a senha
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="setor_id" class="form-label">
                        <i class="bi bi-building"></i> Setor *
                    </label>
                    <select class="form-select" id="setor_id" name="setor_id" required>
                        <option value="">Selecione um setor</option>
                        <?php foreach ($setores as $setor): ?>
                        <option value="<?= $setor['id'] ?>" <?= $usuario['setor_id'] == $setor['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($setor['nome']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nivel" class="form-label">
                        <i class="bi bi-shield"></i> Nível de Acesso *
                    </label>
                    <select class="form-select" id="nivel" name="nivel" required>
                        <option value="setor" <?= $usuario['nivel'] === 'setor' ? 'selected' : '' ?>>Administrador de Setor</option>
                        <option value="admin" <?= $usuario['nivel'] === 'admin' ? 'selected' : '' ?>>Administrador Geral</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ativo" name="ativo" 
                               <?= $usuario['ativo'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ativo">
                            <i class="bi bi-check-circle"></i> Usuário Ativo
                        </label>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Usuários inativos não podem fazer login
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Atenção:</strong> 
                <ul class="mb-0 mt-2">
                    <li>Alterar o nível para "Administrador Geral" dará acesso total ao sistema</li>
                    <li>Desativar um usuário impedirá que ele faça login</li>
                    <li>Não é possível editar o próprio usuário (use outro administrador)</li>
                </ul>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?route=admin/usuarios" class="btn btn-secondary me-md-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div> 