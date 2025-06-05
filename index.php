<?php
declare(strict_types=1);
<<<<<<< HEAD
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/cars.php';
=======
require_once __DIR__ . '/cars.php';
require_once __DIR__ . '/uploads.php';
require_once __DIR__ . '/views.php';
>>>>>>> 19c44afc6cda4fd0235ebcbedc8b4788254611a3

header('Content-Type: text/html; charset=UTF-8');

$action = $_GET['action'] ?? '';
$formErrors = [];
<<<<<<< HEAD
$cars = readCars();
=======
>>>>>>> 19c44afc6cda4fd0235ebcbedc8b4788254611a3
$editCar = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        [$imagePath, $uploadErrors] = isset($_FILES['image']) ? handleUpload($_FILES['image']) : [null, []];
        $data = $_POST;
        $data['image'] = $imagePath;
        $formErrors = array_merge($uploadErrors, addCar($data));
        if (!$formErrors) {
            header('Location: index.php');
            exit;
        }
<<<<<<< HEAD
    }
    if ($action === 'edit' && isset($_GET['id'])) {
        $data = $_POST;
        $data['main_image'] = $_POST['main_image_current'] ?? '';
        $data['alt_image1'] = $_POST['alt_image1_current'] ?? '';
        $data['alt_image2'] = $_POST['alt_image2_current'] ?? '';
        $data['alt_image3'] = $_POST['alt_image3_current'] ?? '';
        $formErrors = updateCar((int)$_GET['id'], $data);
=======
    } elseif ($action === 'edit' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        [$imagePath, $uploadErrors] = !empty($_FILES['image']['name']) ? handleUpload($_FILES['image']) : [null, []];
        $data = $_POST;
        if ($imagePath !== null) {
            $data['image'] = $imagePath;
        } else {
            $data['image'] = null;
        }
        $formErrors = array_merge($uploadErrors, updateCar($id, $data));
>>>>>>> 19c44afc6cda4fd0235ebcbedc8b4788254611a3
        if (!$formErrors) {
            if ($imagePath !== null) {
                $cars = readCars();
                $prev = findCarById($cars, $id);
                if ($prev && $prev->image && $prev->image !== $imagePath) {
                    deleteFile($prev->image);
                }
            }
            header('Location: index.php');
            exit;
        }
    }
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $image = deleteCar($id);
    deleteFile($image);
    header('Location: index.php');
    exit;
}

<<<<<<< HEAD
if ($action === 'edit' && isset($_GET['id'])) {
    $editCar = findCarById($cars, (int)$_GET['id']);
}

// Renderizado de vistas
if ($action === 'edit' || $action === 'add') {
    require __DIR__ . '/views/form.php';
} else {
    require __DIR__ . '/views/list.php';
}
=======
$cars = readCars();
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
    <?php renderForm($editCar, $formErrors); ?>
    <div class="row">
        <?php foreach ($cars as $car): ?>
            <?php renderCarCard($car); ?>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
>>>>>>> 19c44afc6cda4fd0235ebcbedc8b4788254611a3
