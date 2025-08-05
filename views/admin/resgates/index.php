<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">
            <i class="bi bi-people"></i> Gerenciar Resgates
        </h1>
        <p class="text-muted">Acompanhe todos os resgates realizados pelos usuários</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Material</th>
                        <th>Quantidade</th>
                        <th>Resgatante</th>
                        <th>Contato</th>
                        <th>Esquadrão</th>
                        <th>Setor</th>
                        <th>Justificativa</th>
                        <th>Data Resgate</th>
                        <th>Data Limite</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resgates as $resgate): ?>
                    <tr>
                        <td><?= $resgate['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($resgate['material_descricao']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($resgate['local_retirada']) ?></small>
                        </td>
                        <td>
                            <span class="badge bg-primary"><?= $resgate['quantidade_resgatada'] ?></span>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($resgate['posto_graduacao']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($resgate['nome_guerra']) ?></small>
                        </td>
                        <td>
                            <i class="bi bi-telephone"></i> <?= htmlspecialchars($resgate['contato']) ?><br>
                            <i class="bi bi-envelope"></i> <?= htmlspecialchars($resgate['email']) ?>
                        </td>
                        <td><?= htmlspecialchars($resgate['esquadrao']) ?></td>
                        <td><?= htmlspecialchars($resgate['setor']) ?></td>
                        <td>
                            <?php if (!empty($resgate['justificativa'])): ?>
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="verJustificativa('<?= htmlspecialchars(addslashes($resgate['justificativa'])) ?>')" 
                                        title="Ver Justificativa">
                                    <i class="bi bi-chat-text"></i> Ver
                                </button>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= date('d/m/Y H:i', strtotime($resgate['data_resgate'])) ?>
                        </td>
                        <td>
                            <?php 
                            $data_limite = new DateTime($resgate['data_limite']);
                            $agora = new DateTime();
                            $diferenca = $agora->diff($data_limite);
                            $is_expirado = $agora > $data_limite;
                            ?>
                            <span class="<?= $is_expirado ? 'text-danger' : 'text-success' ?>">
                                <?= date('d/m/Y H:i', strtotime($resgate['data_limite'])) ?>
                            </span>
                            <?php if ($is_expirado): ?>
                                <br><small class="text-danger">Expirado há <?= $diferenca->days ?> dias</small>
                            <?php else: ?>
                                <br><small class="text-success"><?= $diferenca->days ?> dias restantes</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $status_cores = [
                                'aguardando_retirada' => 'warning',
                                'retirado' => 'success',
                                'expirado' => 'secondary',
                                'cancelado' => 'danger',
                            ];
                            $cor = $status_cores[$resgate['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $cor ?>">
                                <?= ucfirst(str_replace('_', ' ', $resgate['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?route=admin/resgates/detalhes&id=<?= $resgate['id'] ?>" class="btn btn-sm btn-outline-info" title="Ver Detalhes">
                                    <i class="bi bi-search"></i>
                                </a>
                                <?php if ($resgate['status'] === 'aguardando_retirada'): ?>
                                <button type="button" class="btn btn-sm btn-outline-success" onclick="marcarRetirado(<?= $resgate['id'] ?>)" title="Marcar como Retirado">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="cancelarResgate(<?= $resgate['id'] ?>)" title="Cancelar Resgate">
                                    <i class="bi bi-x-circle"></i>
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

<!-- Modal de Detalhes -->
<div class="modal fade" id="detalhesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye"></i> Detalhes do Resgate
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalhesContent">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Justificativa -->
<div class="modal fade" id="justificativaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-chat-text"></i> Justificativa do Resgate
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="justificativaContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function marcarRetirado(id) {
    if (confirm('Confirmar retirada do material?')) {
        window.location.href = 'index.php?route=admin/resgates/retirar&id=' + id;
    }
}
function cancelarResgate(id) {
    if (confirm('Deseja cancelar este resgate? A quantidade será devolvida ao estoque.')) {
        window.location.href = 'index.php?route=admin/resgates/cancelar&id=' + id;
    }
}

function verJustificativa(justificativa) {
    document.getElementById('justificativaContent').innerHTML = '<div class="alert alert-info"><i class="bi bi-chat-text"></i> ' + justificativa.replace(/\n/g, '<br>') + '</div>';
    var modal = new bootstrap.Modal(document.getElementById('justificativaModal'));
    modal.show();
}
</script> 