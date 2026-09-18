<?php
require_once(dirname(__FILE__, 3) . '/globals.php');
require_once ROOT_PATH . 'conexion.php';
$stmt = $pdo->prepare("
    SELECT p.*,
           (SELECT COUNT(*) FROM ventas v WHERE v.producto_id = p.id) AS ventas_count
    FROM productos p
    ORDER BY p.id DESC
");
$stmt->execute();
$productos = $stmt->fetchAll();

$stmtCat = $pdo->prepare("SELECT id, nombre FROM categoria ORDER BY nombre");
$stmtCat->execute();
$categoriasDisponibles = $stmtCat->fetchAll();

$mensaje_usuario = "";

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);

    if ($id_producto) {
        try {
            if ($accion === 'eliminar') {
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM ventas WHERE producto_id = :id");
                $stmtCheck->execute([':id' => $id_producto]);
                $enUso = (int) $stmtCheck->fetchColumn();

                if ($enUso > 0) {
                    $mensaje_usuario = "No se puede eliminar: hay $enUso venta(s) asociada(s) a este producto.";
                } else {
                    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = :id");
                    $stmt->execute([':id' => $id_producto]);
                    $mensaje_usuario = "Eliminado correctamente.";
                }
            } elseif ($accion === 'editar') {
                $nombre = $_POST['nombre-producto'] ?? '';
                $precio = $_POST['precio-producto'] ?? '';
                $stock = $_POST['stock-producto'] ?? '';
                $categoria = filter_input(INPUT_POST, 'categoria-producto', FILTER_VALIDATE_INT);

                $stmt = $pdo->prepare("UPDATE productos SET nombre = :nombre, precio = :precio, stock = :stock, categoria = :categoria WHERE id = :id");
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':precio' => $precio,
                    ':stock' => $stock,
                    ':categoria' => $categoria,
                    ':id' => $id_producto
                ]);
                $mensaje_usuario = "Actualizado correctamente.";
            }

            header("Location: " . URL_BASE . "modulos/modulos.php?stlabel=prdc&cat_status=" . urlencode($mensaje_usuario));
            exit;
        } catch (PDOException $e) {
            error_log("Error en DB: " . $e->getMessage());
            $mensaje_usuario = interpretarError($e);
        }
    }
}
?>
<h2>Mis Productos</h2>

<?php if (!empty($productos)): ?>
    <?php foreach ($productos as $producto): ?>
        <?php $tieneVentas = (int) $producto['ventas_count'] > 0; ?>
        <form action="<?= URL_BASE ?>modulos/modulos.php?stlabel=prdc" method="POST"
            class="form-producto" data-id="<?= $producto['id'] ?>" data-ventas="<?= (int) $producto['ventas_count'] ?>">

            <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">

            <label>Nombre</label>
            <input required class="input input-nombre" type="text" name="nombre-producto" maxlength="80"
                value="<?= htmlspecialchars($producto['nombre']) ?>"
                data-original="<?= htmlspecialchars($producto['nombre']) ?>">

            <label>Precio</label>
            <input required class="input input-precio" type="number" step="0.01" min="0" name="precio-producto"
                value="<?= htmlspecialchars($producto['precio']) ?>"
                data-original="<?= htmlspecialchars($producto['precio']) ?>">

            <label>Stock</label>
            <input required class="input input-stock" type="number" step="1" min="0" name="stock-producto"
                value="<?= htmlspecialchars($producto['stock']) ?>" data-original="<?= htmlspecialchars($producto['stock']) ?>">

            <label>Categoría</label>
            <select required class="input input-categoria" name="categoria-producto"
                data-original="<?= $producto['categoria'] ?>">
                <?php foreach ($categoriasDisponibles as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $producto['categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Fecha de ingreso</label>
            <input class="input" type="text" value="<?= htmlspecialchars($producto['fecha_ingreso']) ?>" disabled>

            <?php if ($tieneVentas): ?>
                <p class="aviso-ventas">Tiene <?= (int) $producto['ventas_count'] ?> venta(s) asociada(s): no se puede eliminar.</p>
            <?php endif; ?>

            <div class="contenedor-boton">
                <button type="submit" name="accion" value="eliminar" class="btn-accion btn-eliminar" <?= $tieneVentas ? 'disabled title="No se puede eliminar: tiene ventas asociadas"' : '' ?>>
                    Eliminar
                </button>
            </div>
        </form>
    <?php endforeach; ?>
<?php endif; ?>