<?php
declare(strict_types=1);
require_once __DIR__ . '/cars.php';

header('Content-Type: text/html; charset=UTF-8');

$action = $_GET['action'] ?? '';

$formErrors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $formErrors = addCar($_POST);
        if (!$formErrors) {
            header('Location: index.php');
            exit;
        }
    }
    if ($action === 'edit' && isset($_GET['id'])) {
        $formErrors = updateCar((int)$_GET['id'], $_POST);
        if (!$formErrors) {
            header('Location: index.php');
            exit;
        }
    }
}

if ($action === 'delete' && isset($_GET['id'])) {
    deleteCar((int)$_GET['id']);
    header('Location: index.php');
    exit;
}

$cars = readCars();
$editCar = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editCar = findCarById($cars, (int)$_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Coches</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">Gestor de Coches</h1>
    <?php if (!empty(
        $formErrors)): ?>
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
        <label class="form-label">Precio</label>
        <input type="number" step="0.01" name="price" value="<?php echo $editCar->price ?? ''; ?>" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Foto principal (JPG/PNG)</label>
        <input type="file" name="main_image" accept="image/*" class="form-control" <?php echo empty($editCar) ? 'required' : ''; ?> >
        <?php if (!empty($editCar->main_image)): ?>
            <input type="hidden" name="main_image" value="<?php echo $editCar->main_image; ?>">
            <img src="uploads/<?php echo htmlspecialchars($editCar->main_image); ?>" alt="Actual" style="max-width:80px;max-height:60px;">
        <?php endif; ?>
    </div>
    <div class="col-md-2">
        <label class="form-label">Foto alternativa 1</label>
        <input type="file" name="alt_image1" accept="image/*" class="form-control">
        <?php if (!empty($editCar->alt_image1)): ?>
            <input type="hidden" name="alt_image1" value="<?php echo $editCar->alt_image1; ?>">
            <img src="uploads/<?php echo htmlspecialchars($editCar->alt_image1); ?>" alt="Actual" style="max-width:60px;max-height:40px;">
        <?php endif; ?>
    </div>
    <div class="col-md-2">
        <label class="form-label">Foto alternativa 2</label>
        <input type="file" name="alt_image2" accept="image/*" class="form-control">
        <?php if (!empty($editCar->alt_image2)): ?>
            <input type="hidden" name="alt_image2" value="<?php echo $editCar->alt_image2; ?>">
            <img src="uploads/<?php echo htmlspecialchars($editCar->alt_image2); ?>" alt="Actual" style="max-width:60px;max-height:40px;">
        <?php endif; ?>
    </div>
    <div class="col-md-2">
        <label class="form-label">Foto alternativa 3</label>
        <input type="file" name="alt_image3" accept="image/*" class="form-control">
        <?php if (!empty($editCar->alt_image3)): ?>
            <input type="hidden" name="alt_image3" value="<?php echo $editCar->alt_image3; ?>">
            <img src="uploads/<?php echo htmlspecialchars($editCar->alt_image3); ?>" alt="Actual" style="max-width:60px;max-height:40px;">
        <?php endif; ?>
    </div>
    <div class="col-md-4">
        <label class="form-label">Logo de marca (opcional)</label>
        <input type="url" name="brand_logo" value="<?php echo $editCar->brand_logo ?? ''; ?>" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">Descripción extendida</label>
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

    <div class="row">
    <?php foreach ($cars as $car): ?>
        <div class="col-md-12 mb-4">
            <div class="card vehicle-card position-relative">
                <div class="row g-0">
                    <!-- Imagen del vehículo -->
                    <div class="col-lg-5">
                        <div class="vehicle-image">
                            <img id="mainImage<?php echo $car->id; ?>" src="<?php echo htmlspecialchars($car->main_image); ?>" loading="lazy" alt="Imagen principal">
                            <div class="image-overlay">
                                <!-- Icono SVG de estrella -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15 8.5 22 9.3 17 14.2 18.2 21.1 12 17.8 5.8 21.1 7 14.2 2 9.3 9 8.5 12 2"></polygon></svg>
                                <?php echo number_format($car->price, 2, ',', '.'); ?> €
                            </div>
                        </div>
                        <div class="thumbnails mt-2 d-flex gap-1">
                            <?php if ($car->main_image): ?>
                                <img class="thumb" src="<?php echo htmlspecialchars($car->main_image); ?>" alt="Vista principal">
                            <?php endif; ?>
                            <?php if ($car->alt_image1): ?>
                                <img class="thumb" src="<?php echo htmlspecialchars($car->alt_image1); ?>" alt="Vista alternativa 1">
                            <?php endif; ?>
                            <?php if ($car->alt_image2): ?>
                                <img class="thumb" src="<?php echo htmlspecialchars($car->alt_image2); ?>" alt="Vista alternativa 2">
                            <?php endif; ?>
                            <?php if ($car->alt_image3): ?>
                                <img class="thumb" src="<?php echo htmlspecialchars($car->alt_image3); ?>" alt="Vista alternativa 3">
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Información del vehículo -->
                    <div class="col-lg-7">
                        <div class="vehicle-info">
                            <div class="vehicle-header d-flex align-items-center mb-2">
                                <img class="brand-logo me-2" src="<?php echo htmlspecialchars($car->brand_logo); ?>" alt="Logo <?php echo htmlspecialchars($car->brand); ?>" style="width:48px;height:48px;">
                                <div class="header-content">
                                    <h3 class="vehicle-title mb-0"><?php echo htmlspecialchars($car->brand . ' ' . $car->model); ?></h3>
                                    <div class="vehicle-subtitle text-muted">
                                        <?php echo htmlspecialchars($car->short_desc); ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Ficha de especificaciones -->
                            <div class="specs-grid row">
                                <div class="spec-item col-6 col-md-3 d-flex align-items-center mb-2">
                                    <svg class="spec-icon me-2" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <div><div class="spec-label">Año</div><div class="spec-value"><?php echo $car->year; ?></div></div>
                                </div>
                                <div class="spec-item col-6 col-md-3 d-flex align-items-center mb-2">
                                    <svg class="spec-icon me-2" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19l8-16 8 16H4z"></path></svg>
                                    <div><div class="spec-label">Kilometraje</div><div class="spec-value"><?php echo number_format($car->mileage, 0, ',', '.'); ?> km</div></div>
                                </div>
                                <div class="spec-item col-6 col-md-3 d-flex align-items-center mb-2">
                                    <span class="spec-icon me-2">⚡</span>
                                    <div><div class="spec-label">Potencia</div><div class="spec-value"><?php echo htmlspecialchars($car->power); ?></div></div>
                                </div>
                                <div class="spec-item col-6 col-md-3 d-flex align-items-center mb-2">
                                    <svg class="spec-icon me-2" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 9.19a6 6 0 1 1-11.32 0z"></path></svg>
                                    <div><div class="spec-label">Combustible</div><div class="spec-value"><?php echo htmlspecialchars($car->fuel); ?></div></div>
                                </div>
                            </div>
                            <!-- Descripción -->
                            <div class="vehicle-description mt-2">
                                <?php echo nl2br(htmlspecialchars($car->long_desc)); ?>
                            </div>
                            <div class="mt-3">
                                <a href="?action=edit&id=<?php echo $car->id; ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="?action=delete&id=<?php echo $car->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este coche?');">Eliminar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


    
    
</div>
</body>
</html>
