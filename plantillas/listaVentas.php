<section class="seccion-ventas">
    <div class="contenedor-ventas">
        <h2>Ventas</h2>
        <?php
        $stmt = $pdo->prepare("
            SELECT v.*, p.nombre AS nombre_producto, p.precio, (p.precio * v.cantidad) AS total
            FROM ventas v
            INNER JOIN productos p ON v.producto_id = p.id
            ORDER BY v.fecha DESC
        ");
        $stmt->execute();
        $ventas = $stmt->fetchAll();
        ?>
        <table class="tabla-ventas">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($ventas)): ?>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?= htmlspecialchars($venta['nombre_producto']) ?></td>
                            <td><?= htmlspecialchars($venta['cantidad']) ?></td>
                            <td><?= htmlspecialchars($venta['total']) ?></td>
                            <td><?= htmlspecialchars($venta['fecha']) ?></td>
                            <td>
                                <form action="<?= URL_BASE . 'eliminar.php?estado=edvnt' ?>" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('¿Eliminar esta venta? Se devolverá el stock al producto.')">
                                    <input type="hidden" name="id-venta" value="<?= htmlspecialchars($venta['id']) ?>">
                                    <button id="eliminar-venta" class="opcion-est" type="submit">
                                        <!-- SVG -->
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5"><em>Todavía no hay ventas registradas.</em></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="formulario-venta">
        <h3>Registrar venta</h3>

        <?php
        $stmt = $pdo->prepare("SELECT * FROM productos ORDER BY nombre ASC");
        $stmt->execute();
        $productosDisponibles = $stmt->fetchAll();
        ?>

        <?php if (!empty($productosDisponibles)): ?>
            <form class="formato-form-venta" action="<?= URL_BASE . 'funciones/agregarVenta.php' ?>" method="POST">
                <label for="producto-venta">Producto</label>
                <select required class="input" name="producto-venta" id="producto-venta">
                    <option value="">--Elija un producto--</option>
                    <?php foreach ($productosDisponibles as $producto): ?>
                        <option value="<?= htmlspecialchars($producto['id']) ?>"
                            data-precio="<?= htmlspecialchars($producto['precio']) ?>"
                            data-stock="<?= htmlspecialchars($producto['stock']) ?>">
                            <?= htmlspecialchars($producto['nombre']) ?> (Stock: <?= htmlspecialchars($producto['stock']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="cantidad-venta">Cantidad</label>
                <input required class="input" type="number" id="cantidad-venta" name="cantidad-venta" min="1">

                <button class="formato-boton" type="submit">Registrar venta</button>
            </form>
        <?php else: ?>
            <p>No tienes productos registrados para vender.</p>
        <?php endif; ?>
    </div>
</section>