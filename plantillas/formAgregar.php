<section>
    <div>Agregar Producto</div>
    <div class="contenedor-formulario">
        <form class="formato-from" action="<?= URL_BASE . 'funciones/agregar.php' ?>" method="POST">
            <label for="nombre-producto">Nombre</label>
            <input required class="input" type="text" id="nombre-producto" name="nombre-producto" maxlength="40">

            <label for="precio-producto">Precio</label>
            <input required class="input" type="number" id="precio-producto" name="precio-producto" maxlength="9" min="1" max="999999999">

            <label for="stock-producto">Unidades</label>
            <input required class="input" type="number" id="stock-producto" name="stock-producto" maxlength="9" min="1" max="999999999">

            <?php if (!empty($categorias)): ?>
                <label for="categoria">Elija una categoría</label>
                <select name="categoria-producto" id="categoria">
                    <option value="">--Elija una categoria--</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= htmlspecialchars($categoria['id']) ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="" id="btnAbrirModal" type="button">Crear categoria</button>
            <?php else: ?>
                <p>No tienes categorias designadas.</p>
                <button class="" id="asignar-categoria" type="button">Crea una!</button>
            <?php endif; ?>
            <button class="formato-boton" type="submit">Registrar Producto</button>
        </form>
    </div>
</section>