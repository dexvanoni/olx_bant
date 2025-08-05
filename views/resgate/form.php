<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="bi bi-hand-index"></i> Solicitar Resgate
                    </h3>
                </div>
                <div class="card-body p-4">
                    <!-- Informações do Material -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <?php 
                            $fotos = json_decode($material['fotos'], true) ?: [];
                            $foto_principal = !empty($fotos) ? $fotos[0] : null;
                            ?>
                            <?php if ($foto_principal): ?>
                                <img src="<?= UPLOAD_PATH . $foto_principal ?>" 
                                     class="img-fluid rounded" 
                                     alt="Foto do material"
                                     onerror="this.src='assets/img/placeholder.jpg'">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h4><?= htmlspecialchars($material['descricao']) ?></h4>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt"></i> <strong>Local:</strong><br>
                                        <?= htmlspecialchars($material['local_retirada']) ?>
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-hash"></i> <strong>BMP:</strong><br>
                                        <?= htmlspecialchars($material['numero_bmp']) ?>
                                    </small>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> <strong>Dono:</strong><br>
                                        <?= htmlspecialchars($material['dono_carga']) ?>
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-star"></i> <strong>Condição:</strong><br>
                                        <?= ucfirst($material['condicao_item']) ?>
                                    </small>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="bi bi-tag"></i> <strong>Tipo:</strong> <?= htmlspecialchars($material['tipo_material']) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Formulário de Resgate -->
                    <form id="resgateForm">
                        <input type="hidden" name="material_id" value="<?= $material['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantidade_resgatada" class="form-label">
                                    <i class="bi bi-box"></i> Quantidade a Resgatar *
                                </label>
                                <input type="number" class="form-control" id="quantidade_resgatada" name="quantidade_resgatada" 
                                       min="1" value="1" required>
                                <div class="form-text">
                                    Disponível: <?= $material['quantidade_disponivel'] ?> de <?= $material['quantidade_total'] ?>
                                    <?php if ($material['quantidade_disponivel'] <= 0): ?>
                                        <br><span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle"></i> Este material está em disputa! Seu pedido entrará na fila de espera.</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="posto_graduacao" class="form-label">
                                    <i class="bi bi-person-badge"></i> Posto/Graduação *
                                </label>
                                <input type="text" class="form-control" id="posto_graduacao" name="posto_graduacao" required
                                       placeholder="Ex: Sgt, Cap, Maj, etc.">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome_guerra" class="form-label">
                                    <i class="bi bi-person"></i> Nome de Guerra *
                                </label>
                                <input type="text" class="form-control" id="nome_guerra" name="nome_guerra" required
                                       placeholder="Seu nome de guerra">
                            </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contato" class="form-label">
                                    <i class="bi bi-telephone"></i> Contato *
                                </label>
                                <input type="tel" class="form-control" id="contato" name="contato" required
                                       placeholder="Telefone ou celular">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       placeholder="seu.email@bant.com">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="esquadrao" class="form-label">
                                    <i class="bi bi-people"></i> Esquadrão *
                                </label>
                                <input type="text" class="form-control" id="esquadrao" name="esquadrao" required
                                       placeholder="Seu esquadrão">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="setor" class="form-label">
                                    <i class="bi bi-building"></i> Setor *
                                </label>
                                <input type="text" class="form-control" id="setor" name="setor" required
                                       placeholder="Seu setor">
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Importante:</strong> Você terá até <?= RESGATE_TIMEOUT_HOURS ?> horas para retirar este material. 
                            Após esse prazo, ele voltará automaticamente para a plataforma.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Confirmar Resgate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('resgateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!this.checkValidity()) {
        this.reportValidity();
        return;
    }
    
    const formData = new FormData(this);
    
    // Mostrar loading
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processando...';
    submitBtn.disabled = true;
    
    fetch('index.php?route=resgatar/salvar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            // Redirecionar após 3 segundos
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 3000);
        } else {
            showAlert(data.message, 'danger');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        showAlert('Erro ao processar resgate. Tente novamente.', 'danger');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script> 