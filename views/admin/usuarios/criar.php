<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-person-plus"></i> Novo Usuário
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?route=admin/usuarios" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?route=admin/usuarios/criar">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">
                        <i class="bi bi-person"></i> Nome Completo *
                    </label>
                    <input type="text" class="form-control" id="nome" name="nome" required
                           placeholder="Nome completo do usuário">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="usuario" class="form-label">
                        <i class="bi bi-person-badge"></i> Nome de Usuário *
                    </label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required
                           placeholder="Nome de usuário para login">
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> Será usado para fazer login no sistema
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Email *
                    </label>
                    <input type="email" class="form-control" id="email" name="email" required
                           placeholder="email@bant.com">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="senha" class="form-label">
                        <i class="bi bi-lock"></i> Senha *
                    </label>
                    <input type="password" class="form-control" id="senha" name="senha" required
                           placeholder="Senha de acesso">
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> Mínimo 6 caracteres
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
                        <option value="<?= $setor['id'] ?>"><?= htmlspecialchars($setor['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> Setor responsável pelo usuário
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nivel" class="form-label">
                        <i class="bi bi-shield"></i> Nível de Acesso *
                    </label>
                    <select class="form-select" id="nivel" name="nivel" required>
                        <option value="setor">Administrador de Setor</option>
                        <option value="admin">Administrador Geral</option>
                    </select>
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> Administrador Geral tem acesso total ao sistema
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Informação:</strong> 
                <ul class="mb-0 mt-2">
                    <li>Administrador de Setor: Gerencia apenas materiais do seu setor</li>
                    <li>Administrador Geral: Acesso total ao sistema, incluindo gerenciamento de usuários</li>
                </ul>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?route=admin/usuarios" class="btn btn-secondary me-md-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Criar Usuário
                </button>
            </div>
        </form>
    </div>
</div> 