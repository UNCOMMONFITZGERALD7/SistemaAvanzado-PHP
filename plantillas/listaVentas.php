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
            <?php if (!empty($ventas)): ?>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
            <?php endif; ?>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
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