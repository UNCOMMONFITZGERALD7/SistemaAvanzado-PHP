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