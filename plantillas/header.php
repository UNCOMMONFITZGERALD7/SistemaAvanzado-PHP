<?php
require_once(dirname(__FILE__, 2) . '/globals.php');
?>
<header class="header">
    <h1>Tienda Mi Papelería</h1>
    <div class="informacion-estados <?= in_array($estado, ['error', 'cat_error', 'errorprvd']) ? 'error' : 'exito' ?>">
        <?php if ($estado === 'ingresado'): ?>
            <p>Producto ingresado correctamente!</p>
        <?php elseif ($estado === 'error'): ?>
            <?php echo htmlspecialchars($mensaje ?? 'Ocurrió un error inesperado.'); ?>
        <?php elseif ($estado === 'cat_creada'): ?>
            <p>Categoria ingresada correctamente!</p>
        <?php elseif ($estado === 'cat_error'): ?>
            <?php echo htmlspecialchars($mensaje ?? 'Ocurrió un error inesperado.'); ?>
        <?php elseif ($estado === 'errorprvd'): ?>
            <?php echo htmlspecialchars($mensaje ?? 'Ocurrió un error inesperado.'); ?>
        <?php elseif ($estado === 'ingresadoprvd'): ?>
            <p>Proveedor ingresado correctaemnte!</p>
        <?php elseif ($estado === 'eliminado'): ?>
            <p>Elemento eliminado correctamente!</p>
        <?php else: ?>
            <p><?= $infoconn ?></p>
        <?php endif; ?>
    </div>
    <div class="navegacion-listas">
        <a class="proveedores" href="<?= URL_BASE . 'modulos/modulos.php?stlabel=prvd' ?>">Proveedores</a>
        <a class="productos" href="<?= URL_BASE . 'modulos/modulos.php?stlabel=prdc' ?>">Productos</a>
        <a class="ventas" href="<?= URL_BASE . 'modulos/modulos.php?stlabel=vnts' ?>">Ventas</a>
        <?php if (isset($_GET['stlabel']) && $_GET['stlabel'] !== ''): ?>
            <a class="ventas" href="<?= URL_BASE . 'index.php' ?>">Volver</a>
        <?php endif; ?>
    </div>
</header>