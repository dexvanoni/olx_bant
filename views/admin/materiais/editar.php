<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-pencil"></i> Editar Material
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?route=admin/materiais" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="index.php?route=admin/materiais/editar&id=<?= $material['id'] ?>" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="descricao" class="form-label">
                        <i class="bi bi-card-text"></i> Descrição *
                    </label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="2" required><?= htmlspecialchars($material['descricao']) ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="local_retirada" class="form-label">
                        <i class="bi bi-geo-alt"></i> Local de Retirada *
                    </label>
                    <input type="text" class="form-control" id="local_retirada" name="local_retirada" value="<?= htmlspecialchars($material['local_retirada']) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="numero_bmp" class="form-label">
                        <i class="bi bi-hash"></i> Nº BMP *
                    </label>
                    <input type="text" class="form-control" id="numero_bmp" name="numero_bmp" value="<?= htmlspecialchars($material['numero_bmp']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="dono_carga" class="form-label">
                        <i class="bi bi-person"></i> Dono da Carga *
                    </label>
                    <input type="text" class="form-control" id="dono_carga" name="dono_carga" value="<?= htmlspecialchars($material['dono_carga']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="condicao_item" class="form-label">
                        <i class="bi bi-star"></i> Condição *
                    </label>
                    <select class="form-select" id="condicao_item" name="condicao_item" required>
                        <option value="excelente" <?= $material['condicao_item'] === 'excelente' ? 'selected' : '' ?>>Excelente</option>
                        <option value="bom" <?= $material['condicao_item'] === 'bom' ? 'selected' : '' ?>>Bom</option>
                        <option value="regular" <?= $material['condicao_item'] === 'regular' ? 'selected' : '' ?>>Regular</option>
                        <option value="ruim" <?= $material['condicao_item'] === 'ruim' ? 'selected' : '' ?>>Ruim</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="tipo_material" class="form-label">
                        <i class="bi bi-tag"></i> Tipo de Material *
                    </label>
                    <input type="text" class="form-control" id="tipo_material" name="tipo_material" value="<?= htmlspecialchars($material['tipo_material']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="quantidade_total" class="form-label">
                        <i class="bi bi-box"></i> Quantidade Total *
                    </label>
                    <input type="number" class="form-control" id="quantidade_total" name="quantidade_total" min="1" value="<?= $material['quantidade_total'] ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="setor" class="form-label">
                        <i class="bi bi-building"></i> Setor *
                    </label>
                    <input type="text" class="form-control" id="setor" name="setor" value="<?= htmlspecialchars($material['setor']) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="fotos" class="form-label">
                        <i class="bi bi-camera"></i> Fotos (máx. 3)
                    </label>
                    <input type="file" class="form-control" id="fotos" name="fotos[]" accept="image/*" multiple capture="environment">
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> Você pode adicionar novas fotos. As fotos atuais estão abaixo.
                    </div>
                    <div class="mt-2">
                        <?php $fotos = json_decode($material['fotos'], true) ?: []; ?>
                        <?php foreach ($fotos as $foto): ?>
                            <img src="<?= UPLOAD_PATH . $foto ?>" alt="Foto" class="rounded me-2 mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?route=admin/materiais" class="btn btn-secondary me-md-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div> 