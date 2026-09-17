<?php

require_once(dirname(__FILE__) . '/conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {

        $stmt = $pdo->prepare(
            "INSERT INTO categoria (nombre, descripcion)
            VALUES (:nombre_cat, :descripcion_cat)"
        );

        $stmt->execute([
            'nombre_cat' => $_POST['nombre-categoria'],
            'descripcion_cat' => $_POST['descripcion-categoria'],
        ]);

        header('Location: index.php?estado=cat_creada');
        exit();

    } catch (PDOException $e) {
        error_log('Error al insertar categoria: ' . $e->getMessage());

        $mensajeError = interpretarError($e);
        header('Location: index.php?estado=cat_error&mensaje=' . urlencode($mensajeError));
        exit;
    }
}