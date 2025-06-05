<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editCar ? 'Editar Coche' : 'Añadir Coche'; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <?php require_once __DIR__ . '/../config.php'; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/style.css">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">Gestor de Coches</h1>
    <?php if (!empty($formErrors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($formErrors as $err): ?>
                <div><?php echo htmlspecialchars($err); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="mb-5">
        <h2><?php echo $editCar ? 'Editar Coche' : 'Añadir Coche'; ?></h2>
        <form method="post" enctype="multipart/form-data" action="?action=<?php echo $editCar ? 'edit&id=' . $editCar->id : 'add'; ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Marca</label>
                <input type="text" name="brand" value="<?php echo $editCar->brand ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Modelo</label>
                <input type="text" name="model" value="<?php echo $editCar->model ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Descripción breve</label>
                <input type="text" name="short_desc" value="<?php echo $editCar->short_desc ?? ''; ?>" class="form-control" maxlength="60" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Año</label>
                <input type="number" name="year" value="<?php echo $editCar->year ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kilometraje</label>
                <input type="number" name="mileage" value="<?php echo $editCar->mileage ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Combustible</label>
                <input type="text" name="fuel" value="<?php echo $editCar->fuel ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Potencia (ej. 184 CV)</label>
                <input type="text" name="power" value="<?php echo $editCar->power ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Precio (€)</label>
                <input type="number" step="0.01" name="price" value="<?php echo $editCar->price ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Imagen principal</label>
                <input type="file" name="main_image" class="form-control">
<?php if (!empty($editCar->main_image)): ?>
    <img src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($editCar->main_image); ?>" style="width: 80px; margin-top: 5px;">
    <input type="hidden" name="main_image_current" value="<?php echo htmlspecialchars($editCar->main_image); ?>">
<?php endif; ?>
            </div>
            <div class="col-md-3">
                <label class="form-label">Imagen alternativa 1</label>
                <input type="file" name="alt_image1" class="form-control">
<?php if (!empty($editCar->alt_image1)): ?>
    <img src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($editCar->alt_image1); ?>" style="width: 80px; margin-top: 5px;">
    <input type="hidden" name="alt_image1_current" value="<?php echo htmlspecialchars($editCar->alt_image1); ?>">
<?php endif; ?>
            </div>
            <div class="col-md-3">
                <label class="form-label">Imagen alternativa 2</label>
                <input type="file" name="alt_image2" class="form-control">
<?php if (!empty($editCar->alt_image2)): ?>
    <img src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($editCar->alt_image2); ?>" style="width: 80px; margin-top: 5px;">
    <input type="hidden" name="alt_image2_current" value="<?php echo htmlspecialchars($editCar->alt_image2); ?>">
<?php endif; ?>
            </div>
            <div class="col-md-3">
                <label class="form-label">Imagen alternativa 3</label>
                <input type="file" name="alt_image3" class="form-control">
<?php if (!empty($editCar->alt_image3)): ?>
    <img src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($editCar->alt_image3); ?>" style="width: 80px; margin-top: 5px;">
    <input type="hidden" name="alt_image3_current" value="<?php echo htmlspecialchars($editCar->alt_image3); ?>">
<?php endif; ?>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción larga</label>
                <textarea name="long_desc" class="form-control" rows="3" required><?php echo $editCar->long_desc ?? ''; ?></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <?php if ($editCar): ?>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
</body>
</html>
