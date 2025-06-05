<?php
/** Data and model utilities */

const DATA_FILE = __DIR__ . '/cars.json';

class Car {
    public function __construct(
        public int $id,
        public string $brand,
        public string $model,
        public int $year,
        public float $price,
        public int $mileage,
        public ?string $image = null
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
            (int)$item['mileage'],
            $item['image'] ?? null
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
            'image' => $car->image,
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

function validateCarData(array $data, bool $imageRequired = true): array {
    $errors = [];
    if (empty(trim($data['brand'] ?? ''))) {
        $errors[] = 'La marca es obligatoria.';
    }
    if (empty(trim($data['model'] ?? ''))) {
        $errors[] = 'El modelo es obligatorio.';
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
    if ($imageRequired && empty($data['image'])) {
        $errors[] = 'La imagen es obligatoria.';
    }
    return $errors;
}

function addCar(array $data): array {
    $errors = validateCarData($data);
    if ($errors) {
        return $errors;
    }
    $cars = readCars();
    $id = getNextId($cars);
    $car = new Car(
        $id,
        trim($data['brand']),
        trim($data['model']),
        (int)$data['year'],
        (float)$data['price'],
        (int)$data['mileage'],
        $data['image'] ?? null
    );
    $cars[] = $car;
    saveCars($cars);
    return [];
}

function updateCar(int $id, array $data): array {
    $cars = readCars();
    $existing = findCarById($cars, $id);
    if (!$existing) {
        return ['Coche no encontrado.'];
    }
    $errors = validateCarData($data, false);
    if ($errors) {
        return $errors;
    }
    foreach ($cars as $index => $car) {
        if ($car->id === $id) {
            $cars[$index] = new Car(
                $id,
                trim($data['brand']),
                trim($data['model']),
                (int)$data['year'],
                (float)$data['price'],
                (int)$data['mileage'],
                $data['image'] !== null ? $data['image'] : $existing->image
            );
            break;
        }
    }
    saveCars($cars);
    return [];
}

/**
 * Delete car and return removed image path if any
 */
function deleteCar(int $id): ?string {
    $cars = readCars();
    $image = null;
    $cars = array_filter($cars, function (Car $c) use ($id, &$image) {
        if ($c->id === $id) {
            $image = $c->image;
            return false;
        }
        return true;
    });
    saveCars($cars);
    return $image;
}
