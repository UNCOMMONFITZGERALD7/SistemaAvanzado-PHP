<?php
require_once(dirname(__FILE__) . '/globals.php');
require_once ROOT_PATH . 'conexion.php';


$stmt = $pdo->prepare("SELECT * FROM categoria ORDER BY id DESC");
$stmt->execute();
$categorias = $stmt->fetchAll();


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
            'categoria' => (int)$_POST['categoria-producto'],
        ]);

        header('Location: index.php?estado=ingresado');
        exit();
    } catch (PDOException $e) {
        error_log('Error al insertar producto: ' . $e->getMessage());

        $mensajeError = interpretarError($e);
        header('Location: index.php?estado=error&mensaje=' . urlencode($mensajeError));
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= URL_BASE . 'css/styles.css' ?>">
    <title>Tienda Mi Papeleria</title>
</head>

<body>
    <?php include('./plantillas/header.php') ?>
    <?php include('./plantillas/formAgregar.php') ?>
    <dialog id="modal">
        <?php include('./plantillas/plantillas-modales/agregarCategorias.php') ?>
    </dialog>
    <script src="index.js"></script>
</body>

</html>