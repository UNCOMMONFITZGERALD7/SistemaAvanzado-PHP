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
    <dialog id="modal">
        <!-- <?php include(ROOT_PATH . 'plantillas/plantillas-modales/edelCategorias.php') ?> -->
        <?php

        $stmt = $pdo->prepare("SELECT * FROM categoria");
        $stmt->execute();
        $categorias = $stmt->fetchAll();

        $mensaje_usuario = "";

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $accion = $_POST['accion'] ?? '';
            $id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT);

            if ($id_categoria) {
                try {
                    if ($accion === 'eliminar') {
                        $stmt = $pdo->prepare("DELETE FROM categoria WHERE id = :id");
                        $stmt->execute([':id' => $id_categoria]);
                        $mensaje_usuario = "Eliminado correctamente.";
                    } elseif ($accion === 'editar') {
                        $nombre = $_POST['nombre-categoria'] ?? '';
                        $descripcion = $_POST['descripcion-categoria'] ?? '';

                        $stmt = $pdo->prepare("UPDATE categoria SET nombre = :nombre, descripcion = :desc WHERE id = :id");
                        $stmt->execute([
                            ':nombre' => $nombre,
                            ':desc' => $descripcion,
                            ':id' => $id_categoria
                        ]);
                        $mensaje_usuario = "Actualizado correctamente.";
                    }

                    header("Location: " . URL_BASE . "plantillas/plantillas-modales/edelCategorias.php?status=success");
                    exit;
                } catch (PDOException $e) {
                    error_log("Error en DB: " . $e->getMessage());
                    $mensaje_usuario = "No se pudo procesar la acción.";
                }
            }
        }
        ?>

        <h2>Mis Categorías</h2>
        <button type="button" id="btnCerrar" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
        </button>

        <?php if (!empty($categorias)): ?>
            <?php foreach ($categorias as $categoria): ?>
                <form action="<?= URL_BASE ?>plantillas/plantillas-modales/edelCategorias.php" method="POST" class="form-categoria" data-id="<?= $categoria['id'] ?>">

                    <input type="hidden" name="id_categoria" value="<?= $categoria['id'] ?>">

                    <label>Nombre</label>
                    <input required class="input input-nombre" type="text" name="nombre-categoria" maxlength="60"
                        value="<?= htmlspecialchars($categoria['nombre']) ?>"
                        data-original="<?= htmlspecialchars($categoria['nombre']) ?>">

                    <label>Descripción</label>
                    <input required class="input input-desc" type="text" name="descripcion-categoria" maxlength="120"
                        value="<?= htmlspecialchars($categoria['descripcion'] ?? 'Sin descripción') ?>"
                        data-original="<?= htmlspecialchars($categoria['descripcion'] ?? 'Sin descripción') ?>">

                    <div class="contenedor-boton">
                        <button type="submit" name="accion" value="eliminar" class="btn-accion btn-eliminar">Eliminar</button>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php endif; ?>

        <script>
            document.querySelectorAll('.form-categoria').forEach((formulario) => {
                const inputNombre = formulario.querySelector('.input-nombre');
                const inputDesc = formulario.querySelector('.input-desc');
                const contenedorBoton = formulario.querySelector('.contenedor-boton');

                const evaluarCambios = () => {
                    const nombreCambio = inputNombre.value !== inputNombre.dataset.original;
                    const descCambio = inputDesc.value !== inputDesc.dataset.original;

                    if (nombreCambio || descCambio) {
                        contenedorBoton.innerHTML = '<button type="submit" name="accion" value="editar" class="btn-accion btn-editar">Editar</button>';
                    } else {
                        contenedorBoton.innerHTML = '<button type="submit" name="accion" value="eliminar" class="btn-accion btn-eliminar">Eliminar</button>';
                    }
                };

                inputNombre.addEventListener('input', evaluarCambios);
                inputDesc.addEventListener('input', evaluarCambios);
            });
            document.getElementById('modal');
            document.getElementById('btnCerrar').addEventListener('click', () => {
                modal.close();
            });
        </script>
    </dialog>
    <script src="../index.js"></script>
</body>

</html>