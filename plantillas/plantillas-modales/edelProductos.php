<?php

$stmt = $pdo->prepare("SELECT * FROM productos");
$stmt->execute();
$productos = $stmt->fetchAll();

$mensaje_usuario = "";

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT);

    if ($id_categoria) {
        try {
            if ($accion === 'eliminar') {
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE categoria = :id");
                $stmtCheck->execute([':id' => $id_categoria]);
                $enUso = (int) $stmtCheck->fetchColumn();

                if ($enUso > 0) {
                    $mensaje_usuario = "No se puede eliminar: hay $enUso producto(s) usando esta categoría.";
                } else {
                    $stmt = $pdo->prepare("DELETE FROM categoria WHERE id = :id");
                    $stmt->execute([':id' => $id_categoria]);
                    $mensaje_usuario = "Eliminado correctamente.";
                }
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

            header("Location: " . URL_BASE . "modulos/modulos.php?stlabel=" . urlencode($_GET['stlabel'] ?? '') . "&cat_status=" . urlencode($mensaje_usuario));
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

<?php if (!empty($productos)): ?>
    <?php foreach ($productos as $producto): ?>
        <form action="<?= URL_BASE ?>modulos/modulos.php?stlabel=<?= htmlspecialchars($_GET['stlabel'] ?? '') ?>" method="POST" class="form-producto" data-id="<?= $producto['id'] ?>">

            <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">

            <label>Nombre</label>
            <input required class="input input-nombre" type="text" name="nombre-producto" maxlength="60"
                value="<?= htmlspecialchars($producto['nombre']) ?>"
                data-original="<?= htmlspecialchars($producto['nombre']) ?>">
            <label>Precio</label>
            <input required class="input input-nombre" type="text" name="nombre-producto" maxlength="60"
                value="<?= htmlspecialchars($producto['nombre']) ?>"
                data-original="<?= htmlspecialchars($producto['nombre']) ?>">
            <label>Stock</label>
            <input required class="input input-nombre" type="text" name="nombre-producto" maxlength="60"
                value="<?= htmlspecialchars($producto['nombre']) ?>"
                data-original="<?= htmlspecialchars($producto['nombre']) ?>">
            <label>N</label>
            <input required class="input input-nombre" type="text" name="nombre-producto" maxlength="60"
                value="<?= htmlspecialchars($producto['nombre']) ?>"
                data-original="<?= htmlspecialchars($producto['nombre']) ?>">

            

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

    const btnCerrarModal = document.getElementById('btnCerrar');
    const modalCategorias = document.getElementById('modal');
    if (btnCerrarModal && modalCategorias) {
        btnCerrarModal.addEventListener('click', () => {
            modalCategorias.close();
        });
    }
</script>