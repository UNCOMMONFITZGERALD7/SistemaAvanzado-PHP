<?php
require_once(dirname(__FILE__, 2) . '/globals.php');
require_once ROOT_PATH . 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {

        $stmt = $pdo->prepare(
            "INSERT INTO productos (nombre, precio, stock, categoria)
            VALUES (:nombre, :precio, :stock, :categoria)"
        );

        $stmt->execute([
            'nombre' => $_POST['nombre-producto'],
            'precio' => $_POST['precio-producto'],
            'stock' => $_POST['stock-producto'],
            'categoria' => $_POST['categoria-producto'],
        ]);

        header('Location: ' . URL_BASE . 'index.php?estado=ingresado');
        exit();
    } catch (PDOException $e) {
        error_log('Error al insertar producto: ' . $e->getMessage());

        $mensajeError = interpretarError($e);
        header('Location: ' . URL_BASE . 'index.php?estado=error&mensaje=' . urlencode($mensajeError));
        exit;
    }
}

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

?>