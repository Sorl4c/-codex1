<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Coches</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <?php require_once __DIR__ . '/../config.php'; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/style.css">
</head>
<body>
<div class="container py-4">
    <div class="mb-3">
        <a href="views/list_public.php" class="btn btn-info">Ver Catálogo Público</a>
    </div>
    <h1 class="mb-4">Gestor de Coches</h1>
    <?php if (!empty($formErrors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($formErrors as $err): ?>
                <div><?php echo htmlspecialchars($err); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="mb-3">
        <a href="?action=add" class="btn btn-success">Añadir Coche</a>
    </div>
    <!-- Tabla resumen -->
    <div class="table-responsive mb-4">
      <table class="table table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Precio (€)</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cars as $car): ?>
            <tr>
              <td><?php echo $car->id; ?></td>
              <td><?php echo htmlspecialchars($car->brand); ?></td>
              <td><?php echo htmlspecialchars($car->model); ?></td>
              <td><?php echo $car->year; ?></td>
              <td><?php echo number_format($car->price, 2, ',', '.'); ?></td>
              <td>
                <a href="?action=edit&id=<?php echo $car->id; ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="?action=delete&id=<?php echo $car->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este coche?');">Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <!-- Vista visual -->
    <div class="row">
    <?php foreach ($cars as $car): ?>
      <div class="card vehicle-card position-relative mb-4">
        <div class="row g-0">
          <!-- Imagen del vehículo -->
          <div class="col-lg-5">
            <div class="vehicle-image">
              <img id="mainImage<?php echo $car->id; ?>" src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($car->main_image); ?>" loading="lazy" alt="Imagen principal" />
              <div class="image-overlay">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <polygon points="12 2 15 8.5 22 9.3 17 14.2 18.2 21.1 12 17.8 5.8 21.1 7 14.2 2 9.3 9 8.5 12 2"></polygon>
                </svg>
                <?php echo number_format($car->price, 2, ',', '.'); ?> €
              </div>
            </div>
            <div class="thumbnails">
              <?php if ($car->main_image): ?>
                <img class="thumb" src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($car->main_image); ?>" alt="Vista principal"
                     data-car-id="<?php echo $car->id; ?>" data-img-src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($car->main_image); ?>" />
              <?php endif; ?>
              <?php if ($car->alt_image1): ?>
                <img class="thumb" src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($car->alt_image1); ?>" alt="Vista alternativa 1"
                     data-car-id="<?php echo $car->id; ?>" data-img-src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($car->alt_image1); ?>" />
              <?php endif; ?>
              <?php if ($car->alt_image2): ?>
                <img class="thumb" src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($car->alt_image2); ?>" alt="Vista alternativa 2"
                     data-car-id="<?php echo $car->id; ?>" data-img-src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($car->alt_image2); ?>" />
              <?php endif; ?>
              <?php if ($car->alt_image3): ?>
                <img class="thumb" src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($car->alt_image3); ?>" alt="Vista alternativa 3"
                     data-car-id="<?php echo $car->id; ?>" data-img-src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($car->alt_image3); ?>" />
              <?php endif; ?>
            </div>
          </div>
          <!-- Información del vehículo -->
          <div class="col-lg-7">
            <div class="vehicle-info">
              <div class="vehicle-header">
                <img
                  class="brand-logo"
                  src="<?php echo htmlspecialchars($car->brand_logo); ?>"
                  alt="Logo <?php echo htmlspecialchars($car->brand); ?>"
                />
                <div class="header-content">
                  <h3 class="vehicle-title"><?php echo htmlspecialchars($car->brand . ' ' . $car->model); ?></h3>
                  <div class="vehicle-subtitle">
                    <?php echo htmlspecialchars($car->short_desc); ?>
                  </div>
                </div>
              </div>
              <div class="specs-grid">
                <!-- Año -->
                <div class="spec-item">
                  <svg class="spec-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                  <div class="spec-content">
                    <div class="spec-label">Año</div>
                    <div class="spec-value"><?php echo $car->year; ?></div>
                  </div>
                </div>
                <!-- Kilometraje -->
                <div class="spec-item">
                  <svg class="spec-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19l8-16 8 16H4z"></path></svg>
                  <div class="spec-content">
                    <div class="spec-label">Kilometraje</div>
                    <div class="spec-value"><?php echo number_format($car->mileage, 0, ',', '.'); ?> km</div>
                  </div>
                </div>
                <!-- Potencia -->
                <div class="spec-item">
                  <span class="spec-icon">⚡</span>
                  <div class="spec-content">
                    <div class="spec-label">Potencia</div>
                    <div class="spec-value"><?php echo htmlspecialchars($car->power); ?></div>
                  </div>
                </div>
                <!-- Combustible -->
                <div class="spec-item">
                  <svg class="spec-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 9.19a6 6 0 1 1-11.32 0z"></path></svg>
                  <div class="spec-content">
                    <div class="spec-label">Combustible</div>
                    <div class="spec-value"><?php echo htmlspecialchars($car->fuel); ?></div>
                  </div>
                </div>
              </div>
              <div class="vehicle-description">
                <?php echo nl2br(htmlspecialchars($car->long_desc)); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-3">
          <a href="?action=edit&id=<?php echo $car->id; ?>" class="btn btn-sm btn-warning">Editar</a>
          <a href="?action=delete&id=<?php echo $car->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este coche?');">Eliminar</a>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.thumb').forEach(function(thumb) {
    thumb.addEventListener('click', function() {
      var carId = this.getAttribute('data-car-id');
      var mainImg = document.getElementById('mainImage' + carId);
      if (mainImg) {
        mainImg.src = this.getAttribute('data-img-src');
      }
    });
  });
});
</script>
</body>
</html>
