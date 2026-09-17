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
    <?php elseif ($_GET['stlabel'] === 'prvd'): ?>
        <section>
            <?php 
            $stmt = $pdo->prepare("SELECT * FROM proveedores");
            $stmt->execute();
            $proveedores = $stmt->fetchAll();
            ?>
            <div class="contenedor-proveedores">
                <h2>Proveedores</h2>
                <?php if (!empty($proveedores)): ?>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <div class="tarjeta-proveedor">
                            <h3><?= htmlspecialchars($proveedor['nombre']) ?></h3>
                            <h4>Contactos:</h4>
                            <p>Telefono: <?= htmlspecialchars($proveedor['telefono']) ?></p>
                            <p>Telefono: <?= htmlspecialchars($proveedor['email']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="formulario-proveedor">
                <h3>Agregar proveedor</h3>
                <form action="<?= ROOT_PATH . 'funciones/proveedorAgregar.php' ?>" method="POST"></form>
            </div>
        </section>
    <?php else: ?>
        <?= 'Hola, nada por aquí aún' ?>
    <?php endif; ?>
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