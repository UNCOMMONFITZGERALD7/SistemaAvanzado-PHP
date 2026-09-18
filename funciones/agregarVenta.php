<?php
require_once(dirname(__FILE__, 2) . '/globals.php');
require_once ROOT_PATH . 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_producto = filter_input(INPUT_POST, 'producto-venta', FILTER_VALIDATE_INT);
    $cantidad = filter_input(INPUT_POST, 'cantidad-venta', FILTER_VALIDATE_INT);

    if (!$id_producto || !$cantidad || $cantidad <= 0) {
        header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=vnts&estado=error&mensaje=' . urlencode('Datos de venta inválidos.'));
        exit();
    }

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("SELECT precio, stock FROM productos WHERE id = :id FOR UPDATE");
        $stmt->execute(['id' => $id_producto]);
        $producto = $stmt->fetch();

        if (!$producto) {
            $pdo->rollBack();
            header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=vnts&estado=error&mensaje=' . urlencode('El producto no existe.'));
            exit();
        }

        if ($cantidad > $producto['stock']) {
            $pdo->rollBack();
            header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=vnts&estado=error&mensaje=' . urlencode('No hay stock suficiente para esta venta.'));
            exit();
        }

        $total = $producto['precio'] * $cantidad;

        $stmt = $pdo->prepare(
            "INSERT INTO ventas (producto_id, cantidad, fecha) VALUES (:producto_id, :cantidad, NOW())"
        );
        $stmt->execute([
            'producto_id' => $id_producto,
            'cantidad' => $cantidad,
        ]);

        $stmt = $pdo->prepare("UPDATE productos SET stock = stock - :cantidad WHERE id = :id");
        $stmt->execute([
            'cantidad' => $cantidad,
            'id' => $id_producto,
        ]);

        $pdo->commit();

        header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=vnts&estado=venta_ok');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log('Error al registrar venta: ' . $e->getMessage());

        $mensajeError = interpretarError($e);
        header('Location: ' . URL_BASE . 'modulos/modulos.php?stlabel=vnts&estado=error&mensaje=' . urlencode($mensajeError));
        exit();
    }
}