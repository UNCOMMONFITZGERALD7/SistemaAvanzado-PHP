<section>
    <?php
    $stmt = $pdo->prepare("
        SELECT p.*, c.nombre AS nombre_categoria,
            (SELECT COUNT(*) FROM ventas v WHERE v.producto_id = p.id) AS ventas_count
        FROM productos p
        INNER JOIN categoria c ON p.categoria = c.id
        ORDER BY p.id DESC
    ");
    $stmt->execute();
    $productos = $stmt->fetchAll();
    ?>
    <h1>Productos</h1>
    <?php $mensaje_usuario = $_GET['cat_status'] ?? ''; ?>
    <?php if ($mensaje_usuario): ?>
        <p>
            <?= htmlspecialchars($mensaje_usuario) ?>
        </p>
    <?php endif; ?>
    <table class="tabla-productos">
        <?php if (!empty($productos)): ?>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Fecha creación</th>
                    <th>Valor</th>
                    <th>Eliminar</th>
                    <th>Editar</th>
                </tr>
            </thead>
        <?php endif; ?>
        <tbody>
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                        <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                        <td><?php echo htmlspecialchars($producto['fecha_ingreso']); ?></td>
                        <td><?php echo htmlspecialchars($producto['precio'] * $producto['stock']); ?></td>

                        <td>
                            <?php $tieneVentas = (int) $producto['ventas_count'] > 0; ?>
                            <form action="<?= URL_BASE . 'eliminar.php?estado=edpro' ?>" method="POST" style="display:inline;"
                                onsubmit="return confirm('¿Eliminar este producto?')">
                                <input type="hidden" name="id-producto"
                                    value="<?php echo htmlspecialchars($producto['id']); ?>">
                                <button id="eliminar-est" class="opcion-est" type="submit" <?= $tieneVentas ? 'disabled title="No se puede eliminar: tiene ventas asociadas"' : '' ?>>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </td>

                        <td>
                            <button id="editar-producto" type="submit" class="opcion-est btn-editar"
                                data-id="<?php echo htmlspecialchars($producto['id']); ?>" da-ach="edelProductos.php">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8"><em>Todavía no hay productos registrados!</em></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>
    <h2>Categorias de productos</h2>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM categoria");
    $stmt->execute();
    $categorias = $stmt->fetchAll();
    ?>
    <table class="tabla-productos">
        <?php if (!empty($categorias)): ?>
            <thead>
                <th>Categoría</th>
                <th>Descripción</th>
            </thead>
        <?php else: ?>
            <tr>
                <td colspan="8"><em>Todavía no hay etiquetas registradas!</em></td>
            </tr>
        <?php endif; ?>
        <tbody>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                        <td><?= htmlspecialchars($categoria['descripcion']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if ($_GET['stlabel'] === 'prdc' && !empty($categorias)): ?>
        <button type="button" id="btnAbrirModal" class="btn-tercero boton-admcat" da-ach="edelCategorias.php">Administrar
            categorias</button>
    <?php endif; ?>
</section>