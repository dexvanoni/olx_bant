<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-plus-circle"></i> Cadastrar Material
                </h1>
                <p class="text-muted">Adicione um novo material para resgate</p>
            </div>
            <a href="index.php?route=admin/materiais" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="index.php?route=admin/materiais/salvar" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="descricao" class="form-label">
                                <i class="bi bi-card-text"></i> Descrição do Material *
                            </label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3" required 
                                      placeholder="Descreva detalhadamente o material..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="local_retirada" class="form-label">
                                <i class="bi bi-geo-alt"></i> Local de Retirada *
                            </label>
                            <input type="text" class="form-control" id="local_retirada" name="local_retirada" required
                                   placeholder="Ex: Setor de Registro, Sala 101">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="numero_bmp" class="form-label">
                                <i class="bi bi-hash"></i> Número BMP *
                            </label>
                            <input type="text" class="form-control" id="numero_bmp" name="numero_bmp" required
                                   placeholder="Ex: BMP-2024-001">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dono_carga" class="form-label">
                                <i class="bi bi-person"></i> Dono da Carga *
                            </label>
                            <input type="text" class="form-control" id="dono_carga" name="dono_carga" required
                                   placeholder="Nome do responsável">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tipo_material" class="form-label">
                                <i class="bi bi-tag"></i> Tipo de Material *
                            </label>
                            <input type="text" class="form-control" id="tipo_material" name="tipo_material" required
                                   placeholder="Ex: Eletrônico, Móvel, Ferramenta">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="condicao_item" class="form-label">
                                <i class="bi bi-star"></i> Condição do Item *
                            </label>
                            <select class="form-select" id="condicao_item" name="condicao_item" required>
                                <option value="">Selecione a condição</option>
                                <option value="excelente">Excelente</option>
                                <option value="bom">Bom</option>
                                <option value="regular">Regular</option>
                                <option value="ruim">Ruim</option>
                            </select>
                        </div>
                                            <div class="col-md-6 mb-3">
                        <label for="quantidade_total" class="form-label">
                            <i class="bi bi-box"></i> Quantidade Total *
                        </label>
                        <input type="number" class="form-control" id="quantidade_total" name="quantidade_total"
                               min="1" value="1" required>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Quantidade total disponível para resgate
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
                            <option value="<?= $setor['id'] ?>" <?= $_SESSION['admin_setor_id'] == $setor['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($setor['nome']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Setor responsável pelo material
                        </div>
                    </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="fotos" class="form-label">
                                <i class="bi bi-camera"></i> Fotos do Material (máximo 3)
                            </label>
                            <input type="file" class="form-control" id="fotos" name="fotos[]" 
                                   accept="image/*" capture="environment" multiple>
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> 
                                Você pode selecionar até 3 fotos. Use "capture" para ativar a câmera em dispositivos móveis.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Cadastrar Material
                            </button>
                            <a href="index.php?route=admin/materiais" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </form>
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
                <div class="mb-3">
                    <h6><i class="bi bi-camera text-primary"></i> Fotos</h6>
                    <small class="text-muted">
                        • Máximo 3 fotos por material<br>
                        • Formatos: JPG, PNG, GIF<br>
                        • Tamanho máximo: 5MB por foto<br>
                        • Use a câmera em dispositivos móveis
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6><i class="bi bi-clock text-warning"></i> Prazo de Resgate</h6>
                    <small class="text-muted">
                        • <?= RESGATE_TIMEOUT_HOURS ?> horas para retirada<br>
                        • Retorna automaticamente após expiração
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6><i class="bi bi-envelope text-success"></i> Notificações</h6>
                    <small class="text-muted">
                        • Email automático para novos resgates<br>
                        • Acompanhamento no painel administrativo
                    </small>
                </div>
                
                <div class="alert alert-info">
                    <i class="bi bi-lightbulb"></i>
                    <strong>Dica:</strong> Descreva o material de forma clara e detalhada para facilitar a identificação pelos usuários.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview das imagens selecionadas
document.getElementById('fotos').addEventListener('change', function(e) {
    const files = e.target.files;
    const maxFiles = 3;
    
    if (files.length > maxFiles) {
        alert(`Você pode selecionar no máximo ${maxFiles} fotos.`);
        this.value = '';
        return;
    }
    
    // Validar tamanho dos arquivos
    for (let i = 0; i < files.length; i++) {
        if (files[i].size > <?= MAX_FILE_SIZE ?>) {
            alert(`O arquivo "${files[i].name}" excede o tamanho máximo de 5MB.`);
            this.value = '';
            return;
        }
    }
});
</script> 