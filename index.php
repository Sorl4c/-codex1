<?php
declare(strict_types=1);
require_once __DIR__ . '/cars.php';
require_once __DIR__ . '/uploads.php';
require_once __DIR__ . '/views.php';

header('Content-Type: text/html; charset=UTF-8');

$action = $_GET['action'] ?? '';
$formErrors = [];
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
