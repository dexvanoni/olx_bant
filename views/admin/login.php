<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-building display-1 text-primary"></i>
                        <h2 class="mt-3">Painel Administrativo</h2>
                        <p class="text-muted">Base Aérea de Natal - BANT</p>
                    </div>
                    
                    <?php if (isset($erro)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <?= $erro ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">
                                <i class="bi bi-person"></i> Usuário
                            </label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="senha" class="form-label">
                                <i class="bi bi-lock"></i> Senha
                            </label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Entrar
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> 
                            Credenciais padrão: admin / admin123
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 