<?php
// Lógica y modelo de coches (sin HTML)

const DATA_FILE = __DIR__ . '/cars.json';

class Car {
    public function __construct(
        public int $id,
        public string $brand,
        public string $model,
        public string $short_desc,
        public int $year,
        public int $mileage,
        public string $fuel,
        public string $power,
        public float $price,
        public string $main_image,
        public string $alt_image1,
        public string $alt_image2,
        public string $alt_image3,
        public string $long_desc,
        public string $brand_logo
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
            (int)($item['id'] ?? 0),
            (string)($item['brand'] ?? ''),
            (string)($item['model'] ?? ''),
            (string)($item['short_desc'] ?? ''),
            (int)($item['year'] ?? 0),
            (int)($item['mileage'] ?? 0),
            (string)($item['fuel'] ?? ''),
            (string)($item['power'] ?? ''),
            (float)($item['price'] ?? 0),
            (string)($item['main_image'] ?? ''),
            (string)($item['alt_image1'] ?? ''),
            (string)($item['alt_image2'] ?? ''),
            (string)($item['alt_image3'] ?? ''),
            (string)($item['long_desc'] ?? ''),
            (string)($item['brand_logo'] ?? '')
        );
    }
    return $cars;
}

function saveCars(array $cars): void {
    $data = [];
    foreach ($cars as $car) {
        if (!$car instanceof Car) continue;
        $data[] = [
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'short_desc' => $car->short_desc,
            'year' => $car->year,
            'mileage' => $car->mileage,
            'fuel' => $car->fuel,
            'power' => $car->power,
            'price' => $car->price,
            'main_image' => $car->main_image,
            'alt_image1' => $car->alt_image1,
            'alt_image2' => $car->alt_image2,
            'alt_image3' => $car->alt_image3,
            'long_desc' => $car->long_desc,
            'brand_logo' => $car->brand_logo
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

function validateCarData(array $data): array {
    $errors = [];
    if (empty(trim($data['brand'] ?? ''))) {
        $errors[] = 'La marca es obligatoria.';
    }
    if (empty(trim($data['model'] ?? ''))) {
        $errors[] = 'El modelo es obligatorio.';
    }
    if (empty(trim($data['short_desc'] ?? ''))) {
        $errors[] = 'La descripción breve es obligatoria.';
    }
    if (!isset($data['year']) || !is_numeric($data['year']) || (int)$data['year'] <= 0) {
        $errors[] = 'El año debe ser un número positivo.';
    }
    if (!isset($data['price']) || !is_numeric($data['price']) || (float)$data['price'] < 0) {
        $errors[] = 'El precio debe ser un número positivo.';
    }
    if (!isset($data['mileage']) || !is_numeric($data['mileage']) || (int)$data['mileage'] < 0) {
        $errors[] = 'El kilometraje debe ser un número positivo.';
    }
    if (empty(trim($data['fuel'] ?? ''))) {
        $errors[] = 'El combustible es obligatorio.';
    }
    if (empty(trim($data['power'] ?? ''))) {
        $errors[] = 'La potencia es obligatoria.';
    }
    // Solo la imagen principal es obligatoria (ver lógica de subida de archivos más abajo)
    if (empty($_FILES['main_image']['name']) && empty($data['main_image'])) {
        $errors[] = 'La foto principal es obligatoria.';
    }
    if (empty(trim($data['long_desc'] ?? ''))) {
        $errors[] = 'La descripción extendida es obligatoria.';
    }
    // Logo NO obligatorio
    return $errors;
}

function addCar(array $data): array {
    $errors = validateCarData($data);
    if ($errors) {
        return $errors;
    }
    $cars = readCars();
    $id = getNextId($cars);
    // Manejo de subida de imágenes
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $mainImageName = '';
    if (!empty($_FILES['main_image']['name'])) {
        $mainImageName = uniqid('main_') . '_' . basename($_FILES['main_image']['name']);
        move_uploaded_file($_FILES['main_image']['tmp_name'], $uploadDir . $mainImageName);
    }
    $altImages = [];
    foreach ([1,2,3] as $i) {
        $key = 'alt_image' . $i;
        if (!empty($_FILES[$key]['name'])) {
            $altName = uniqid($key . '_') . '_' . basename($_FILES[$key]['name']);
            move_uploaded_file($_FILES[$key]['tmp_name'], $uploadDir . $altName);
            $altImages[$i] = $altName;
        } else {
            $altImages[$i] = '';
        }
    }
    $car = new Car(
        $id,
        trim($data['brand']),
        trim($data['model']),
        trim($data['short_desc']),
        (int)$data['year'],
        (int)$data['mileage'],
        trim($data['fuel']),
        trim($data['power']),
        (float)$data['price'],
        $mainImageName,
        $altImages[1],
        $altImages[2],
        $altImages[3],
        trim($data['long_desc']),
        trim($data['brand_logo'] ?? '')
    );
    $cars[] = $car;
    saveCars($cars);
    return [];
}

function updateCar(int $id, array $data): array {
    $errors = validateCarData($data);
    if ($errors) {
        return $errors;
    }
    $cars = readCars();
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $mainImageName = $data['main_image'] ?? '';
    if (!empty($_FILES['main_image']['name'])) {
        $mainImageName = uniqid('main_') . '_' . basename($_FILES['main_image']['name']);
        move_uploaded_file($_FILES['main_image']['tmp_name'], $uploadDir . $mainImageName);
    }
    $altImages = [];
    foreach ([1,2,3] as $i) {
        $key = 'alt_image' . $i;
        $altImages[$i] = $data[$key] ?? '';
        if (!empty($_FILES[$key]['name'])) {
            $altName = uniqid($key . '_') . '_' . basename($_FILES[$key]['name']);
            move_uploaded_file($_FILES[$key]['tmp_name'], $uploadDir . $altName);
            $altImages[$i] = $altName;
        }
    }
    foreach ($cars as $index => $car) {
        if ($car->id === $id) {
            $cars[$index] = new Car(
                $id,
                trim($data['brand']),
                trim($data['model']),
                trim($data['short_desc']),
                (int)$data['year'],
                (int)$data['mileage'],
                trim($data['fuel']),
                trim($data['power']),
                (float)$data['price'],
                $mainImageName,
                $altImages[1],
                $altImages[2],
                $altImages[3],
                trim($data['long_desc']),
                trim($data['brand_logo'] ?? '')
            );
            break;
        }
    }
    saveCars($cars);
    return [];
}

function deleteCar(int $id): void {
    $cars = readCars();
    $cars = array_filter($cars, fn(Car $c) => $c->id !== $id);
    saveCars($cars);
}
