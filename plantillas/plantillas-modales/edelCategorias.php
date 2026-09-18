<?php
require_once(dirname(__FILE__, 3) . '/globals.php');
require_once ROOT_PATH . 'conexion.php';

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

            header("Location: " . URL_BASE . "modulos/modulos.php?stlabel=prdc&cat_status=" . urlencode($mensaje_usuario));
            exit;
        } catch (PDOException $e) {
            error_log("Error en DB: " . $e->getMessage());
            $mensaje_usuario = "No se pudo procesar la acción.";
            header("Location: " . URL_BASE . "modulos/modulos.php?stlabel=prdc&cat_status=" . urlencode($mensaje_usuario));
            exit;
        }
    }
}
?>

<h2>Mis Categorías</h2>

<?php if (!empty($categorias)): ?>
    <?php foreach ($categorias as $categoria): ?>
        <form action="<?= URL_BASE ?>modulos/modulos.php?stlabel=prdc" method="POST" class="form-categoria" data-id="<?= $categoria['id'] ?>">

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