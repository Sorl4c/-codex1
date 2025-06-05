<?php
// config.php
// Si tu Virtual Host local apunta a /tmp/.../crud-php, la URL base local es "/"
define('BASE_URL', '/tmp/carlos_pru/proyecto-coches/crud-php/');
// O, si tu proyecto vive en un subdirectorio en producción:
 // define('BASE_URL', '/subcarpeta/');
 
// Fija el charset UTF-8 desde este punto
header('Content-Type: text/html; charset=UTF-8');
?>
