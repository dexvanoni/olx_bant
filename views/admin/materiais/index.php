<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-box"></i> Gerenciar Materiais
            </h1>
            <p class="text-muted">Cadastre e gerencie os materiais disponíveis para resgate</p>
        </div>
        <a href="index.php?route=admin/materiais/criar" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Material
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Descrição</th>
                        <th>Local</th>
                        <th>BMP</th>
                        <th>Dono</th>
                        <th>Condição</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Responsável</th>
                        <th>Status</th>
                        <th>Resgates</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materiais as $material): ?>
                    <tr>
                        <td><?= $material['id'] ?></td>
                        <td>
                            <?php 
                            $fotos = json_decode($material['fotos'], true) ?: [];
                            $foto_principal = !empty($fotos) ? $fotos[0] : null;
                            ?>
                            <?php if ($foto_principal): ?>
                                <img src="<?= UPLOAD_PATH . $foto_principal ?>" 
                                     class="rounded" 
                                     style="width: 50px; height: 50px; object-fit: cover;"
                                     alt="Foto do material"
                                     onerror="this.src='assets/img/placeholder.jpg'">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($material['descricao']) ?></strong>
                            <?php if (count($fotos) > 1): ?>
                                <br><small class="text-muted"><?= count($fotos) ?> fotos</small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($material['local_retirada']) ?></td>
                        <td><?= htmlspecialchars($material['numero_bmp']) ?></td>
                        <td><?= htmlspecialchars($material['dono_carga']) ?></td>
                        <td>
                            <?php
                            $condicao_cores = [
                                'excelente' => 'success',
                                'bom' => 'info',
                                'regular' => 'warning',
                                'ruim' => 'danger'
                            ];
                            $cor = $condicao_cores[$material['condicao_item']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $cor ?>"><?= ucfirst($material['condicao_item']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($material['tipo_material']) ?></td>
                        <td>
                            <strong><?= $material['quantidade_disponivel'] ?></strong> / <?= $material['quantidade_total'] ?>
                            <?php if ($material['quantidade_disponivel'] < $material['quantidade_total']): ?>
                                <br><small class="text-warning"><?= $material['quantidade_total'] - $material['quantidade_disponivel'] ?> resgatados</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?= htmlspecialchars($material['setor_nome'] ?? 'N/A') ?>
                            </small>
                        </td>
                        <td>
                            <?php
                            $status_cores = [
                                'disponivel' => 'success',
                                'aguardando_retirada' => 'warning',
                                'resgatado' => 'info'
                            ];
                            $cor = $status_cores[$material['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $cor ?>"><?= ucfirst(str_replace('_', ' ', $material['status'])) ?></span>
                        </td>
                        <td>
                            <?php if ($material['total_resgates'] > 0): ?>
                                <span class="badge bg-info"><?= $material['total_resgates'] ?></span>
                                <?php if ($material['resgates_pendentes'] > 0): ?>
                                    <br><small class="text-warning"><?= $material['resgates_pendentes'] ?> pendentes</small>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?route=admin/materiais/editar&id=<?= $material['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        title="Excluir"
                                        onclick="confirmDelete(<?= $material['id'] ?>, '<?= htmlspecialchars($material['descricao']) ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 