<h2>Crear categoría</h2>
<button type="button" id="btnCerrar">&times;</button>
<form action="<?= URL_BASE . 'guardar_categoria.php' ?>" method="POST">
    <label for="nombre-categoria">Nombre</label>
    <input required class="input" type="text" id="nombre-categoria" name="nombre-categoria" maxlength="60">

    <label for="descripcion-categoria">Descripcion</label>
    <input required value="Sin descripción" class="input" type="text" id="descripcion-categoria" name="descripcion-categoria" maxlength="120">

    <button class="formato-boton" type="submit">Crear!</button>
</form>