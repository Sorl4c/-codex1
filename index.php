<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/cars.php';

header('Content-Type: text/html; charset=UTF-8');

$action = $_GET['action'] ?? '';

$formErrors = [];
$cars = readCars();
$editCar = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $formErrors = addCar($_POST);
        if (!$formErrors) {
            header('Location: index.php');
            exit;
        }
    }
    if ($action === 'edit' && isset($_GET['id'])) {
        $data = $_POST;
        $data['main_image'] = $_POST['main_image_current'] ?? '';
        $data['alt_image1'] = $_POST['alt_image1_current'] ?? '';
        $data['alt_image2'] = $_POST['alt_image2_current'] ?? '';
        $data['alt_image3'] = $_POST['alt_image3_current'] ?? '';
        $formErrors = updateCar((int)$_GET['id'], $data);
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

if ($action === 'edit' && isset($_GET['id'])) {
    $editCar = findCarById($cars, (int)$_GET['id']);
}

// Renderizado de vistas
if ($action === 'edit' || $action === 'add') {
    require __DIR__ . '/views/form.php';
} else {
    require __DIR__ . '/views/list.php';
}
