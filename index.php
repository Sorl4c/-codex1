<?php
declare(strict_types=1);

const DATA_FILE = __DIR__ . '/cars.json';

class Car {
    public function __construct(
        public int $id,
        public string $brand,
        public string $model,
        public int $year,
        public float $price,
        public int $mileage
    ) {}
}

function readCars(): array {
    if (!file_exists(DATA_FILE)) {
        return [];
    }
    $json = file_get_contents(DATA_FILE);
    if ($json === false) {
        return [];
    }
    $data = json_decode($json, true);
    if (!is_array($data)) {
        return [];
    }
    $cars = [];
    foreach ($data as $item) {
        $cars[] = new Car(
            (int)$item['id'],
            (string)$item['brand'],
            (string)$item['model'],
            (int)$item['year'],
            (float)$item['price'],
            (int)$item['mileage']
        );
    }
    return $cars;
}

rm -f index.php
cat <<'EOF' > index.php
<?php
declare(strict_types=1);

const DATA_FILE = __DIR__ . '/cars.json';

class Car {
    public function __construct(
        public int \$id,
        public string \$brand,
        public string \$model,
        public int \$year,
        public float \$price,
        public int \$mileage
    ) {}
}
EOF
cat index.php
    $json = file_get_contents(DATA_FILE);
    if ($json === false) {
        return [];
    }
    $data = json_decode($json, true);
    if (!is_array($data)) {
        return [];
    }
    $cars = [];
    foreach ($data as $item) {
        $cars[] = new Car(
            (int)$item['id'],
            (string)$item['brand'],
            (string)$item['model'],
            (int)$item['year'],
            (float)$item['price'],
            (int)$item['mileage']
        );
    }
    return $cars;
}

function saveCars(array $cars): void {
    $data = [];
    foreach ($cars as $car) {
        if (!$car instanceof Car) {
            continue;
        }
        $data[] = [
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'year' => $car->year,
            'price' => $car->price,
            'mileage' => $car->mileage,
        ];
    }
    file_put_contents(DATA_FILE, json_encode($data, JSON_PRETTY_PRINT));
}

function getNextId(array $cars): int {
    $max = 0;
    foreach ($cars as $car) {
        if ($car->id > $max) {
            $max = $car->id;
        }
    }
    return $max + 1;
}

function findCarById(array $cars, int $id): ?Car {
    foreach ($cars as $car) {
        if ($car->id === $id) {
            return $car;
        }
    }
    return null;
}

function addCar(array $data): void {
    $cars = readCars();
    $id = getNextId($cars);
    $car = new Car(
        $id,
        $data['brand'] ?? '',
        $data['model'] ?? '',
        (int)($data['year'] ?? 0),
        (float)($data['price'] ?? 0),
        (int)($data['mileage'] ?? 0)
    );
    $cars[] = $car;
    saveCars($cars);
}

function updateCar(int $id, array $data): void {
    $cars = readCars();
    foreach ($cars as $index => $car) {
        if ($car->id === $id) {
            $cars[$index] = new Car(
                $id,
                $data['brand'] ?? $car->brand,
                $data['model'] ?? $car->model,
                (int)($data['year'] ?? $car->year),
                (float)($data['price'] ?? $car->price),
                (int)($data['mileage'] ?? $car->mileage)
            );
            break;
        }
    }
    saveCars($cars);
}

function deleteCar(int $id): void {
    $cars = readCars();
    $cars = array_filter($cars, fn(Car $c) => $c->id !== $id);
    saveCars($cars);
}

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        addCar($_POST);
        header('Location: index.php');
        exit;
    }
    if ($action === 'edit' && isset($_GET['id'])) {
        updateCar((int)$_GET['id'], $_POST);
        header('Location: index.php');
        exit;
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
    <div class="mb-5">
        <h2><?php echo $editCar ? 'Editar Coche' : 'Añadir Coche'; ?></h2>
        <form method="post" action="?action=<?php echo $editCar ? 'edit&id=' . $editCar->id : 'add'; ?>" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Marca</label>
                <input type="text" name="brand" value="<?php echo $editCar->brand ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Modelo</label>
                <input type="text" name="model" value="<?php echo $editCar->model ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Año</label>
                <input type="number" name="year" value="<?php echo $editCar->year ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Precio</label>
                <input type="number" step="0.01" name="price" value="<?php echo $editCar->price ?? ''; ?>" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kilometraje</label>
                <input type="number" name="mileage" value="<?php echo $editCar->mileage ?? ''; ?>" class="form-control" required>
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
            <div class="col-md-6 mb-4">
                <div class="card vehicle-card position-relative">
                    <div class="row g-0">
                        <div class="col-lg-5">
                            <div class="vehicle-image">
                                <img src="https://via.placeholder.com/640x360" loading="lazy" alt="Imagen del vehiculo">
                                <div class="image-overlay">
                                    <?php echo number_format($car->price, 2, ',', '.'); ?> EUR
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="vehicle-info">
                                <div class="vehicle-header">
                                    <img class="brand-logo" src="https://via.placeholder.com/48" alt="Logo">
                                    <div class="header-content">
                                        <h3 class="vehicle-title"><?php echo htmlspecialchars($car->brand . ' ' . $car->model); ?></h3>
                                    </div>
                                </div>
                                <div class="specs-grid">
                                    <div class="spec-item">
                                        <div class="spec-content">
                                            <div class="spec-label">Año</div>
                                            <div class="spec-value"><?php echo $car->year; ?></div>
                                        </div>
                                    </div>
                                    <div class="spec-item">
                                        <div class="spec-content">
                                            <div class="spec-label">Kilometraje</div>
                                            <div class="spec-value"><?php echo $car->mileage; ?> km</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="?action=edit&id=<?php echo $car->id; ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="?action=delete&id=<?php echo $car->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este coche?');">Eliminar</a>
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
