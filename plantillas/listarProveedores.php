<section>
    <div class="contenedor-proveedores">
        <h2>Proveedores</h2>
        <?php if (!empty($proveedores)): ?>
            <ol class="listaO-tarjetas">
                <?php foreach ($proveedores as $proveedor): ?>
                    <li>
                        <div class="tarjeta-proveedor">
                            <h3>
                                <?= htmlspecialchars($proveedor['nombre']) ?>
                            </h3>
                            <h4>Contactos:</h4>
                            <p>Telefono:
                                <?= htmlspecialchars($proveedor['telefono']) ?>
                            </p>
                            <p>Telefono:
                                <?= htmlspecialchars($proveedor['correo']) ?>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <h4>¡No tienes proveedores registrados!</h4>
        <?php endif; ?>

    </div>
    <div class="formulario-proveedor">
        <h3>Agregar proveedor</h3>
        <form action="<?= URL_BASE . 'funciones/agregarProveedor.php' ?>" method="POST">
            <label for="nombre-proveedor">Nombre</label>
            <input required class="input" type="text" id="nombre-proveedor" name="nombre-proveedor" maxlength="60"
                minlength="5">

            <label for="numero-proveedor">Numero</label>
            <input required placeholder="Numero del proveedor..." class="input" type="number" id="numero-proveedor"
                name="numero-proveedor" maxlength="14" minlength="8">

            <label for="email-proveedor">Email</label>
            <input required placeholder="(Opcional)" class="input" type="email" id="email-proveedor"
                name="email-proveedor" maxlength="40">

            <button class="formato-boton" type="submit">Agregar proveedor</button>
        </form>
    </div>
</section>