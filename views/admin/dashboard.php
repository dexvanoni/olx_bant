<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">
            <i class="bi bi-speedometer2"></i> Dashboard
        </h1>
        <p class="text-muted">Visão geral do sistema de resgate de materiais</p>
    </div>
</div>

<!-- Cards de Estatísticas -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-box text-primary fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total de Materiais</h6>
                        <h3 class="mb-0"><?= $total_materiais ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle text-success fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Disponíveis</h6>
                        <h3 class="mb-0"><?= $materiais_disponiveis ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-clock text-warning fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Aguardando Retirada</h6>
                        <h3 class="mb-0"><?= $resgates_pendentes ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-info fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Resgates Hoje</h6>
                        <h3 class="mb-0"><?= $resgates_pendentes ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning"></i> Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="index.php?route=admin/materiais/criar" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                            <i class="bi bi-plus-circle fs-1 mb-2"></i>
                            <span>Cadastrar Material</span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="index.php?route=admin/materiais" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                            <i class="bi bi-box fs-1 mb-2"></i>
                            <span>Gerenciar Materiais</span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="index.php?route=admin/resgates" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                            <i class="bi bi-people fs-1 mb-2"></i>
                            <span>Ver Resgates</span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="index.php" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                            <i class="bi bi-eye fs-1 mb-2"></i>
                            <span>Ver Site</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Informações
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-clock text-warning me-2"></i>
                    <div>
                        <small class="text-muted">Prazo de Retirada</small><br>
                        <strong><?= RESGATE_TIMEOUT_HOURS ?> horas</strong>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-camera text-info me-2"></i>
                    <div>
                        <small class="text-muted">Fotos por Material</small><br>
                        <strong>Até 3 fotos</strong>
                    </div>
                </div>
                
               
                <div class="d-flex align-items-center">
                    <i class="bi bi-shield-check text-primary me-2"></i>
                    <div>
                        <small class="text-muted">Sistema</small><br>
                        <strong>Seguro e confiável</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 