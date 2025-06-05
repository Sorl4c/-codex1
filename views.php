<?php
require_once __DIR__ . '/cars.php';

function renderForm(?Car $car, array $errors): void {
    $action = $car ? 'edit&id=' . $car->id : 'add';
    ?>
    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <h2><?= $car ? 'Editar Coche' : 'Añadir Coche' ?></h2>
    <form method="post" enctype="multipart/form-data" action="?action=<?= $action ?>" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Marca</label>
            <input type="text" name="brand" value="<?= htmlspecialchars($car->brand ?? '') ?>" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Modelo</label>
            <input type="text" name="model" value="<?= htmlspecialchars($car->model ?? '') ?>" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Año</label>
            <input type="number" name="year" value="<?= htmlspecialchars($car->year ?? '') ?>" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($car->price ?? '') ?>" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Kilometraje</label>
            <input type="number" name="mileage" value="<?= htmlspecialchars($car->mileage ?? '') ?>" class="form-control" required>
        </div>
        <div class="col-md-5">
            <label class="form-label">Imagen</label>
            <input type="file" name="image" accept="image/jpeg,image/png" class="form-control" <?= $car ? '' : 'required' ?>>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <?php if ($car): ?>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
        </div>
    </form>
    <?php
}

function renderCarCard(Car $car): void {
    ?>
    <div class="col-md-6 mb-4">
        <div class="card vehicle-card position-relative">
            <div class="row g-0">
                <div class="col-lg-5">
                    <div class="vehicle-image">
                        <?php if ($car->image): ?>
                            <img src="<?= htmlspecialchars($car->image) ?>" alt="Imagen del vehiculo" loading="lazy">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/640x360" alt="Sin imagen" loading="lazy">
                        <?php endif; ?>
                        <div class="image-overlay">
                            <?= number_format($car->price, 2, ',', '.') ?> EUR
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="vehicle-info">
                        <div class="vehicle-header">
                            <div class="header-content">
                                <h3 class="vehicle-title"><?= htmlspecialchars($car->brand . ' ' . $car->model) ?></h3>
                            </div>
                        </div>
                        <div class="specs-grid">
                            <div class="spec-item">
                                <div class="spec-content">
                                    <div class="spec-label">Año</div>
                                    <div class="spec-value"><?= $car->year ?></div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <div class="spec-content">
                                    <div class="spec-label">Kilometraje</div>
                                    <div class="spec-value"><?= $car->mileage ?> km</div>
                                </div>
                            </div>
                        </div>
                        <a href="?action=edit&id=<?= $car->id ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="?action=delete&id=<?= $car->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este coche?');">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

