<!-- Conteúdo Principal -->
<main>
    <!-- Hero Section -->
    <section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-6 fw-bold mb-3">
                    <i class="bi bi-building"></i> Sistema de Resgate de Materiais
                </h1>
                <p class="lead mb-4">
                    Base Aérea de Natal - BANT<br>
                    Encontre e resgate materiais disponíveis para doação
                </p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="bi bi-building display-1 text-light opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Materiais Section -->
<section id="materiais" class="py-5">
    <div class="container">
        <!--<div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center mb-3">
                    <i class="bi bi-box"></i> Materiais Disponíveis
                </h2>
                <p class="text-center text-muted">
                    Clique em "RESGATAR" para solicitar a retirada de qualquer material
                </p>
            </div>
        </div>-->

        <?php if (empty($materiais)): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i>
                    <strong>Nenhum material disponível no momento.</strong><br>
                    Novos materiais serão adicionados em breve.
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($materiais as $material): ?>
            <?php 
                $fotos = json_decode($material['fotos'], true) ?: [];
                $foto_principal = !empty($fotos) ? $fotos[0] : null;
                $fotos_json = htmlspecialchars(json_encode(array_map(function($f) { return UPLOAD_PATH . ltrim($f, '/'); }, $fotos)));
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card material-card h-100">
                    <div class="position-relative">
                        <?php if ($foto_principal): ?>
                            <img src="<?= UPLOAD_PATH . $foto_principal ?>" 
                                 class="card-img-top material-image" 
                                 alt="Foto do material"
                                 data-fotos='<?= $fotos_json ?>'
                                 style="cursor:zoom-in;"
                                 onerror="this.src='assets/img/placeholder.jpg'">
                        <?php else: ?>
                            <div class="card-img-top material-image bg-light d-flex align-items-center justify-content-center" style="cursor:zoom-in;" data-fotos='<?= $fotos_json ?>'>
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($material['status'] == 'resgatado'): ?>
                            <span class="badge bg-warning status-badge">
                                <i class="bi bi-hourglass-split"></i> Estoque em disputa
                            </span>
                            <?php elseif ($material['status'] == 'em_disputa'): ?>
                                <span class="badge bg-success status-badge">
                                    <i class="bi bi-check-circle"></i> Entrar em disputa
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success status-badge">
                                    <i class="bi bi-check-circle"></i> Disponível
                                </span>
                            <?php endif; ?>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($material['descricao']) ?></h5>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="bi bi-geo-alt"></i> <strong>Local:</strong> <?= htmlspecialchars($material['local_retirada']) ?>
                            </small><br>
                            <small class="text-muted">
                                <i class="bi bi-hash"></i> <strong>BMP:</strong> <?= htmlspecialchars($material['numero_bmp']) ?>
                            </small><br>
                            <small class="text-muted">
                                <i class="bi bi-person"></i> <strong>Dono:</strong> <?= htmlspecialchars($material['dono_carga']) ?>
                            </small><br>
                            <small class="text-muted">
                                <i class="bi bi-star"></i> <strong>Condição:</strong> 
                                <?= ucfirst($material['condicao_item']) ?>
                            </small><br>
                            <small class="text-muted">
                                <i class="bi bi-tag"></i> <strong>Tipo:</strong> <?= htmlspecialchars($material['tipo_material']) ?>
                            </small><br>
                            <?php if ($material['status'] == 'em_disputa'): ?>
                                <small class="text-muted">
                                <i class="bi bi-box"></i> <strong>Material zerado! Entrar em disputa</strong>
                                </small>
                            <?php endif; ?>
                            <?php if ($material['status'] == 'disponivel' || $material['status'] == 'aguardando_retirada'): ?>
                                <small class="text-muted">
                                    <i class="bi bi-box"></i> <strong>Disponível:</strong> <?= $material['quantidade_disponivel'] ?> de <?= $material['quantidade_total'] ?>
                                </small>
                            </small>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (count($fotos) > 1): ?>
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="bi bi-images"></i> <?= count($fotos) ?> fotos disponíveis
                            </small>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mt-auto">
                            <button type="button" 
                                    class="btn btn-primary w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#resgateModal"
                                    data-material-id="<?= $material['id'] ?>"
                                    data-material-desc="<?= htmlspecialchars($material['descricao']) ?>"
                                    data-quantidade-disponivel="<?= $material['quantidade_disponivel'] ?>"
                                    >
                                    <?php if ($material['status'] == 'resgatado'): ?>
                                        <i class="bi bi-hourglass-split" ></i> EM ANÁLISE DE DISPUTA
                                    <?php else: ?>
                                        <i class="bi bi-hand-index"></i> RESGATAR
                                    <?php endif; ?>
                            </button>
                            <?php if ($material['status'] == 'resgatado'): ?>
                                <p class="text-center text-muted" style="font-size: 0.8rem;">Entrar na disputa! Clique no botão acima.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Modal de Resgate -->
<div class="modal fade" id="resgateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-hand-index"></i> Solicitar Resgate
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>Material:</strong> <span id="materialDesc"></span>
                </div>
                
                <form id="resgateForm">
                    <input type="hidden" id="materialId" name="material_id">
                    <input type="hidden" id="quantidadeDisponivel" name="quantidade_disponivel">
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="quantidade_resgatada" class="form-label">
                                <i class="bi bi-box"></i> Quantidade a Resgatar *
                            </label>
                            <input type="number" class="form-control" id="quantidade_resgatada" name="quantidade_resgatada" 
                                   min="1" value="1" required>
                            <div class="form-text">
                                <span id="quantidadeInfo">Disponível: <span id="quantidadeDisponivelSpan">0</span></span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="posto_graduacao" class="form-label">
                                <i class="bi bi-person-badge"></i> Posto/Graduação *
                            </label>
                            <select class="form-control" name="posto_graduacao" required>
                                <option value="">Selecione</option>
                                <option value="Brig">Brigadeiro</option>
                                <option value="Cel">Coronel</option>
                                <option value="TCel">Tenente-Coronel</option>
                                <option value="Maj">Major</option>
                                <option value="Cap">Capitão</option>
                                <option value="1T">1º Tenente</option>
                                <option value="2T">2º Tenente</option>
                                <option value="Asp">Aspirante</option>
                                <option value="SO">Suboficial</option>
                                <option value="1S">1º Sargento</option>
                                <option value="2S">2º Sargento</option>
                                <option value="3S">3º Sargento</option>
                                <option value="CB">Cabo</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="Rec">Recruta</option>
                                <option value="Consc">Conscrito</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nome_guerra" class="form-label">
                                <i class="bi bi-person"></i> Nome de Guerra *
                            </label>
                            <input type="text" class="form-control" id="nome_guerra" name="nome_guerra" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contato" class="form-label">
                                <i class="bi bi-telephone"></i> Contato *
                            </label>
                            <input type="tel" class="form-control" id="contato" name="contato" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email *
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="esquadrao" class="form-label">
                                <i class="bi bi-people"></i> Esquadrão *
                            </label>
                            <input type="text" class="form-control" id="esquadrao" name="esquadrao" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="setor" class="form-label">
                                <i class="bi bi-building"></i> Setor *
                            </label>
                            <input type="text" class="form-control" id="setor" name="setor" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="justificativa" class="form-label">
                                <i class="bi bi-chat-text"></i> Justificativa do Resgate *
                            </label>
                            <textarea class="form-control" id="justificativa" name="justificativa"
                                      rows="4" required
                                      placeholder="Informe onde o material será empregado e quais suas utilidades..."></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Descreva detalhadamente o uso pretendido do material e sua importância para a missão.
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Importante:</strong> Você terá até <?= RESGATE_TIMEOUT_HOURS ?> horas para retirar este material. 
                        Após esse prazo, ele voltará automaticamente para a plataforma.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnResgatar">
                    <i class="bi bi-check-circle"></i> Confirmar Resgate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Galeria de Fotos -->
<div class="modal fade" id="galeriaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="bi bi-images"></i> Fotos do Material
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center position-relative">
                <button id="galeriaPrev" class="btn btn-light position-absolute top-50 start-0 translate-middle-y" style="z-index:2;" tabindex="-1">
                    <i class="bi bi-chevron-left fs-2"></i>
                </button>
                <img id="galeriaImagem" src="" class="img-fluid rounded shadow" style="max-height:70vh; max-width:100%; object-fit:contain; background:#222;" alt="Foto do material">
                <button id="galeriaNext" class="btn btn-light position-absolute top-50 end-0 translate-middle-y" style="z-index:2;" tabindex="-1">
                    <i class="bi bi-chevron-right fs-2"></i>
                </button>
            </div>
            <div class="modal-footer justify-content-center bg-dark border-0">
                <span id="galeriaContador" class="text-white-50 small"></span>
            </div>
        </div>
    </div>
</div>

<script>
// Configurar modal de resgate
document.addEventListener('DOMContentLoaded', function() {
    const resgateModal = document.getElementById('resgateModal');
    const materialIdInput = document.getElementById('materialId');
    const materialDescSpan = document.getElementById('materialDesc');
    const btnResgatar = document.getElementById('btnResgatar');
    const resgateForm = document.getElementById('resgateForm');
    
    // Verificar se os elementos existem
    if (!resgateModal || !materialIdInput || !materialDescSpan || !btnResgatar || !resgateForm) {
        console.error('Elementos do modal não encontrados');
        return;
    }
    
    resgateModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;
        const materialId = button.getAttribute('data-material-id');
        const materialDesc = button.getAttribute('data-material-desc');
        const quantidadeDisponivel = button.getAttribute('data-quantidade-disponivel');
        
        console.log('Modal aberto:', { materialId, materialDesc, quantidadeDisponivel });
        
        materialIdInput.value = materialId;
        materialDescSpan.textContent = materialDesc;
        
        // Configurar quantidade
        document.getElementById('quantidadeDisponivel').value = quantidadeDisponivel;
        document.getElementById('quantidadeDisponivelSpan').textContent = quantidadeDisponivel;
        
        // Remover qualquer limitação de max para permitir disputa
        document.getElementById('quantidade_resgatada').removeAttribute('max');
        
        // Adicionar mensagem de disputa se disponível for 0
        const quantidadeInfo = document.getElementById('quantidadeInfo');
        if (quantidadeDisponivel <= 0) {
            quantidadeInfo.innerHTML = 'Disponível: <span id="quantidadeDisponivelSpan">0</span> <br><span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle"></i> Este material está em disputa! Seu pedido entrará para análise.</span>';
        } else {
            quantidadeInfo.innerHTML = 'Disponível: <span id="quantidadeDisponivelSpan">' + quantidadeDisponivel + '</span>';
        }
    });
    
    btnResgatar.addEventListener('click', function() {
        console.log('Botão resgatar clicado');
        
        // Validar formulário
        if (!resgateForm.checkValidity()) {
            console.log('Formulário inválido');
            resgateForm.reportValidity();
            return;
        }
        
        // Mostrar loading
        btnResgatar.disabled = true;
        btnResgatar.innerHTML = '<i class="bi bi-hourglass-split"></i> Processando...';
        
        const formData = new FormData(resgateForm);
        
        // Debug: mostrar dados do formulário
        console.log('Dados do formulário:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        // Usar XMLHttpRequest
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php?route=resgatar/salvar', true);
        
        // Adicionar timeout
        xhr.timeout = 10000; // 10 segundos
        
        xhr.onload = function() {
            console.log('Status da resposta:', xhr.status);
            console.log('XHR Response:', xhr.responseText);
            
            try {
                const data = JSON.parse(xhr.responseText);
                console.log('Parsed data:', data);
                
                if (data.success) {
                    console.log('Sucesso! Mostrando alerta...');
                    showAlert(data.message, 'success');
                    
                    // Fechar modal após um pequeno delay
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(resgateModal).hide();
                        resgateForm.reset();
                    }, 1000);
                    
                    // Recarregar página após 3 segundos
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    console.log('Erro! Mostrando alerta...');
                    showAlert(data.message || 'Erro desconhecido', 'danger');
                }
            } catch (e) {
                console.error('Erro ao parsear JSON:', e);
                console.error('Resposta completa:', xhr.responseText);
                showAlert('Resposta inválida do servidor. Verifique o console para mais detalhes.', 'danger');
            }
            
            btnResgatar.disabled = false;
            btnResgatar.innerHTML = '<i class="bi bi-check-circle"></i> Confirmar Resgate';
        };
        
        xhr.onerror = function() {
            console.error('Erro na requisição XHR');
            showAlert('Erro ao processar resgate. Tente novamente.', 'danger');
            btnResgatar.disabled = false;
            btnResgatar.innerHTML = '<i class="bi bi-check-circle"></i> Confirmar Resgate';
        };
        
        xhr.ontimeout = function() {
            console.error('Timeout na requisição XHR');
            showAlert('Tempo limite excedido. Tente novamente.', 'danger');
            btnResgatar.disabled = false;
            btnResgatar.innerHTML = '<i class="bi bi-check-circle"></i> Confirmar Resgate';
        };
        
        console.log('Enviando requisição...');
        xhr.send(formData);
    });
    
    // Garantir que o max seja sempre removido quando o modal for aberto
    resgateModal.addEventListener('shown.bs.modal', function() {
        const inputQtd = document.getElementById('quantidade_resgatada');
        if (inputQtd) {
            inputQtd.removeAttribute('max');
        }
    });
});

// Galeria de fotos dos materiais
(function() {
    // Armazenar fotos e índice atual
    let fotos = [];
    let fotoAtual = 0;

    // Elementos do modal
    const galeriaModal = document.getElementById('galeriaModal');
    const galeriaImagem = document.getElementById('galeriaImagem');
    const galeriaPrev = document.getElementById('galeriaPrev');
    const galeriaNext = document.getElementById('galeriaNext');
    const galeriaContador = document.getElementById('galeriaContador');


    // Função para atualizar imagem
    function atualizarImagem() {
        if (!fotos.length) return;
        galeriaImagem.src = fotos[fotoAtual];
        galeriaContador.textContent = (fotoAtual+1) + ' / ' + fotos.length;
        galeriaPrev.style.display = fotos.length > 1 ? '' : 'none';
        galeriaNext.style.display = fotos.length > 1 ? '' : 'none';
    }

    // Eventos de navegação
    galeriaPrev.addEventListener('click', function() {
        if (fotos.length) {
            fotoAtual = (fotoAtual - 1 + fotos.length) % fotos.length;
            atualizarImagem();
        }
    });
    galeriaNext.addEventListener('click', function() {
        if (fotos.length) {
            fotoAtual = (fotoAtual + 1) % fotos.length;
            atualizarImagem();
        }
    });

    // Fechar modal reseta galeria
    galeriaModal.addEventListener('hidden.bs.modal', function() {
        fotos = [];
        fotoAtual = 0;
        galeriaImagem.src = '';
        galeriaContador.textContent = '';
    });

    // Delegação: clique na imagem do card
    document.addEventListener('click', function(e) {
        const img = e.target.closest('.material-image[data-fotos]');
        if (img) {
            try {
                fotos = JSON.parse(img.getAttribute('data-fotos'));
            } catch {
                fotos = [];
            }
            console.log('Fotos para galeria:', fotos); // <-- debug
            if (!Array.isArray(fotos) || !fotos.length) return;
            fotoAtual = 0;
            atualizarImagem();
            const modal = new bootstrap.Modal(galeriaModal);
            modal.show();
        }
    });
})();
</script>
</main> 