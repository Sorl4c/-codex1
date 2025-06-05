<?php
/** Upload helpers */
const UPLOAD_DIR = __DIR__ . '/uploads';

function handleUpload(array $file): array {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return [null, ['Error al subir la imagen']];
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return [null, ['La imagen supera 2MB']];
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
    if (!isset($allowed[$mime])) {
        return [null, ['Tipo de imagen no permitido']];
    }
    $ext = $allowed[$mime];
    $name = uniqid('img_', true) . '.' . $ext;
    $dest = UPLOAD_DIR . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return [null, ['No se pudo guardar la imagen']];
    }
    return ['uploads/' . $name, []];
}

function deleteFile(?string $path): void {
    if (!$path) {
        return;
    }
    $file = __DIR__ . '/' . $path;
    if (is_file($file)) {
        @unlink($file);
    }
}
