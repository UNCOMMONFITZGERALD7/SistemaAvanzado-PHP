<section>
    <?php
    $stmt = $pdo->prepare("
        SELECT p.*, c.nombre AS nombre_categoria 
        FROM productos p
        INNER JOIN categoria c ON p.categoria = c.id
        ORDER BY p.id DESC
        ");
    $stmt->execute();
    $productos = $stmt->fetchAll();
    ?>
    <table class="tabla-productos">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Fecha creación</th>
                <th>Eliminar</th>
                <th>Editar</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                        <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                        <td><?php echo htmlspecialchars($producto['fecha_ingreso']); ?></td>

                        <td>
                            <form action="<?= URL_BASE . 'eliminar.php' ?>" method="POST" style="display:inline;"
                                onsubmit="return confirm('¿Eliminar este producto?')">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">
                                <button id="eliminar-est" class="opcion-est" type="submit">
                                    <!-- SVG -->
                                </button>
                            </form>
                        </td>

                        <td>
                            <button type="button" class="opcion-est btn-editar"
                                data-id="<?php echo htmlspecialchars($producto['id']); ?>">
                                <!-- SVG -->
                            </button>
                        </td>

                        <td>
                            <button type="button" class="opcion-est btn-notas"
                                data-id="<?php echo htmlspecialchars($producto['id']); ?>">
                                <!-- SVG -->
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8"><em>Todavía no hay productos registrados.</em></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>