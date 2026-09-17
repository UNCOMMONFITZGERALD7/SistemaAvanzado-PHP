<?php

define('ROOT_PATH', __DIR__ . '/');
define('URL_BASE', 'http://localhost/examen/');
require_once ROOT_PATH . 'conexion.php';

$estado = $_GET['estado'] ?? null;
$mensaje = $_GET['mensaje'] ?? null;
$swipdo = $pdo;
function interpretarError(PDOException $e): string
{
    $codigoError = $e->getCode();

    switch ($codigoError) {
        case '23505':
            return 'Ya existe un producto registrado con esa identificación';
        case '23502':
            return 'Los campos son obligatorios';
        case '22001':
            return 'Uno de los campos es demasiado largo';
        case '08006':
            return 'No se pudo conectar a la base de datos';
        case '42703':
            return error_log('La columna no existe en la base de datos');
        case '42P01':
            return error_log('La tabla no existe');
        default:
            return error_log('Error desconocido');
    }
}