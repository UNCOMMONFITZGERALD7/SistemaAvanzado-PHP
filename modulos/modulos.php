<?php
require_once(dirname(__FILE__, 2) . '/globals.php');
require_once ROOT_PATH . 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === 'POST' && ($_GET['stlabel'] ?? '') === 'prdc') {
    include(ROOT_PATH . 'plantillas/plantillas-modales/edelProductos.php');
    include(ROOT_PATH . 'plantillas/plantillas-modales/edelCategorias.php');
    exit;
}

$titulo = 'Documento';

if ($_GET['stlabel'] === 'prdc') {
    $titulo = 'Tienda - Productos';
} elseif ($_GET['stlabel'] === 'vnts') {
    $titulo = 'Tienda - Ventas';
} elseif ($_GET['stlabel'] === 'prvd') {
    $titulo = 'Tienda - Proveedores';
}
;

$stmt = $pdo->prepare("SELECT * FROM proveedores");
$stmt->execute();
$proveedores = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= URL_BASE . 'css/styles.css' ?>">
    <title><?= $titulo ?></title>
</head>

<body>
    <?php include(ROOT_PATH . 'plantillas/header.php') ?>

    <?php if ($_GET['stlabel'] === 'prdc'): ?>
        <?php include(ROOT_PATH . 'plantillas/listaProductos.php') ?>
    <?php elseif ($_GET['stlabel'] === 'prvd'): ?>
        <?php include(ROOT_PATH . 'plantillas/listarProveedores.php') ?>
    <?php elseif ($_GET['stlabel'] === 'vnts'): ?>
        <?php include(ROOT_PATH . 'plantillas/listaVentas.php') ?>
    <?php else: ?>
        <?= 'Hola, nada por aquí aún' ?>
    <?php endif; ?>

    <dialog id="modal"></dialog>
    <footer class="footer">
        <p>Tienda Mi Papelería — Sistema de gestión</p>
    </footer>
    <script src="../index.js"></script>
</body>

</html>