<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-plus-circle"></i> Novo Setor
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?route=admin/setores" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?route=admin/setores/criar">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">
                        <i class="bi bi-building"></i> Nome do Setor *
                    </label>
                    <input type="text" class="form-control" id="nome" name="nome" required
                           placeholder="Ex: Registro, Manutenção, etc.">
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> Nome único do setor
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="descricao" class="form-label">
                        <i class="bi bi-card-text"></i> Descrição
                    </label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3"
                              placeholder="Descrição detalhada do setor"></textarea>
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> Descrição opcional do setor
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?route=admin/setores" class="btn btn-secondary me-md-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Criar Setor
                </button>
            </div>
        </form>
    </div>
</div> 