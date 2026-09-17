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
                <th>Valor</th>
                <th>Eliminar</th>
                <th>Editar</th>
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
                        <td><?php echo htmlspecialchars($producto['precio'] * $producto['stock']); ?></td>

                        <td>
                            <form action="<?= URL_BASE . 'eliminar.php' ?>" method="POST" style="display:inline;"
                                onsubmit="return confirm('¿Eliminar este producto?')">
                                <input type="hidden" name="id-producto" value="<?php echo htmlspecialchars($producto['id']); ?>">
                                <button id="eliminar-est" class="opcion-est" type="submit">
                                    <!-- SVG -->
                                </button>
                            </form>
                        </td>

                        <td>
                            <button id="editar-producto" type="button" class="opcion-est btn-editar"
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
    <h2>Categorias de productos</h2>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM categoria");
    $stmt->execute();
    $categorias = $stmt->fetchAll();
    ?>
    <table class="tabla-productos">
        <thead>
            <th>Categoría</th>
            <th>Descripción</th>
        </thead>
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
    <?php if ($_GET['stlabel'] === 'prdc'): ?>
        <button type="button" id="btnAbrirModal">Administrar categorias</button>
    <?php endif; ?>
</section>