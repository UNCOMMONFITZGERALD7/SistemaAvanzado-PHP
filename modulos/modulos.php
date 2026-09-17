<?php
require_once(dirname(__FILE__, 2) . '/globals.php');
require_once ROOT_PATH . 'conexion.php';

$titulo = 'Documento';

if ($_GET['stlabel'] === 'prdc') {
    $titulo = 'Tienda - Productos';
} elseif ($_GET['stlabel'] === 'vnts') {
    $titulo = 'Tienda - Ventas';
} elseif ($_GET['stlabel'] === 'prvd') {
    $titulo = 'Tienda - Proveedores';
};

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
    <?php else: ?>
        <?= 'Hola, nada por aquí aún' ?>
    <?php endif; ?>
    <button type="button" id="btnAbrirModal">Administrar categorias</button>
    <?php $mensaje_usuario = $_GET['cat_status'] ?? ''; ?>
    <?php if ($mensaje_usuario): ?>
        <p><?= htmlspecialchars($mensaje_usuario) ?></p>
    <?php endif; ?>

    <dialog id="modal">
        <?php include(ROOT_PATH . 'plantillas/plantillas-modales/edelCategorias.php') ?>
    </dialog>
    <script src="../index.js"></script>
</body>

</html>