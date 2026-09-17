<?php
require_once(dirname(__FILE__, 2) . '/globals.php');
require_once ROOT_PATH . 'conexion.php';

$stmt = $pdo->prepare("SELECT * FROM categoria ORDER BY id DESC");
$stmt->execute();
$categorias = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        $stmt = $pdo->prepare("INSERT INTO proveedores (nombre, telefono, correo) VALUES (
            :nombre,
            :telefono,
            :email
        )");

        $stmt->execute([
            "nombre" => $_POST["nombre-proveedor"],
            "telefono" => $_POST["numero-proveedor"],
            "email" => $_POST["email-proveedor"],
        ]);

        header("Location: " . URL_BASE . "modulos/modulos.php?stlabel=prvd&estado=ingresadoprvd");
        exit();

    } catch (PDOException $e) {
        error_log('Error al insertar proveedor: ' . $e->getMessage());

        $mensajeError = interpretarError($e);
        header("Location: " . URL_BASE . "modulos/modulos.php?stlabel=prvd&estado=errorprvd&mensaje=" . urlencode($mensajeError));
        exit();
    }
}