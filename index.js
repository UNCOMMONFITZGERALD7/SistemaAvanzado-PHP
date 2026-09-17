const modal = document.getElementById('modal');
const btnAbrir = document.getElementById('btnAbrirModal');
const btnAbrirEditar = document.getElementById('editar-producto');

const botonCerrarHTML = '<button type="button" id="btnCerrar">Cerrar</button>';

if (btnAbrirEditar && modal) {
    btnAbrirEditar.addEventListener('click', (e) => {
        e.preventDefault();
        const archivoPro = btnAbrirEditar.getAttribute('da-ach');
        const plantillaPro = `/examen/plantillas/plantillas-modales/${archivoPro}`;

        fetch(plantillaPro)
            .then(Response => {
                if (!Response.ok) {
                    throw new Error('No se pudo cargar la plantilla');
                }
                return Response.text();
            })
            .then(html => {
                modal.innerHTML = botonCerrarHTML + html;

                reprogramarBotonCerrar();
                activarBotonesCambiantes(modal);
                modal.showModal();
            })
            .catch(error => {
                console.error('Error:', error);
                modal.innerHTML = botonCerrarHTML + '<p>Error al cargar el modal</p>';
                reprogramarBotonCerrar();
                modal.showModal();
            });
    });
}

if (btnAbrir && modal) {
    btnAbrir.addEventListener('click', (e) => {
        e.preventDefault();
        const archivoCat = btnAbrir.getAttribute('da-ach');
        const plantillaCat = `/examen/plantillas/plantillas-modales/${archivoCat}`;

        fetch(plantillaCat)
            .then(Response => {
                if (!Response.ok) {
                    throw new Error('No se pudo cargar la plantilla');
                }
                return Response.text();
            })
            .then(html => {
                modal.innerHTML = botonCerrarHTML + html;
                reprogramarBotonCerrar();
                activarBotonesCambiantes(modal);
                modal.showModal();
            })
            .catch(error => {
                console.error('Error:', error);
                modal.innerHTML = botonCerrarHTML + '<p>Error al cargar el modal</p>';
                reprogramarBotonCerrar();
                modal.showModal();
            });
    });
}

function activarBotonesCambiantes(contenedor) {
    contenedor.querySelectorAll('.form-producto, .form-categoria').forEach((formulario) => {
        const inputNombre = formulario.querySelector('.input-nombre');
        const inputDesc = formulario.querySelector('.input-desc');
        const inputPrecio = formulario.querySelector('.input-precio');
        const inputStock = formulario.querySelector('.input-stock');
        const inputCategoria = formulario.querySelector('.input-categoria');
        const contenedorBoton = formulario.querySelector('.contenedor-boton');
        const tieneVentas = parseInt(formulario.dataset.ventas, 10) > 0;

        if (!contenedorBoton) return;

        const evaluarCambios = () => {
            const huboCambio =
                (inputNombre && inputNombre.value !== inputNombre.dataset.original) ||
                (inputDesc && inputDesc.value !== inputDesc.dataset.original) ||
                (inputPrecio && inputPrecio.value !== inputPrecio.dataset.original) ||
                (inputStock && inputStock.value !== inputStock.dataset.original) ||
                (inputCategoria && inputCategoria.value !== inputCategoria.dataset.original);

            if (huboCambio) {
                contenedorBoton.innerHTML = '<button type="submit" name="accion" value="editar" class="btn-accion btn-editar">Editar</button>';
            } else {
                const disabledAttr = tieneVentas ? 'disabled title="No se puede eliminar: tiene ventas asociadas"' : '';
                contenedorBoton.innerHTML = `<button type="submit" name="accion" value="eliminar" class="btn-accion btn-eliminar" ${disabledAttr}>Eliminar</button>`;
            }
        };

        [inputNombre, inputDesc, inputPrecio, inputStock].forEach((input) => {
            if (input) input.addEventListener('input', evaluarCambios);
        });
        if (inputCategoria) inputCategoria.addEventListener('change', evaluarCambios);
    });
}

function reprogramarBotonCerrar() {
    const nuevoBtnCerrar = document.getElementById('btnCerrar');
    if (nuevoBtnCerrar && modal) {
        nuevoBtnCerrar.addEventListener('click', () => {
            modal.close();
            modal.innerHTML = botonCerrarHTML;
        });
    }
}

reprogramarBotonCerrar();

const inputsInformacion = document.querySelectorAll(".input");

inputsInformacion.forEach(input => {
    input.addEventListener(
        "keydown",
        (evt) => {
            const clases = evt.target.name;

            if (clases === 'nombre-producto' || clases === 'nombre-categoria' || clases === 'descripcion-categoria') {
                const unableCharsInput = '@#$%&*=+_[]{}/\\|;:<>?!¿¡^~`"\'';

                if (unableCharsInput.includes(evt.key)) {
                    evt.preventDefault()
                }
            } else if (clases === 'precio-producto' || clases == 'stock-producto') {
                const unableCharsInput = 'e@-#$%&*=+_[]{}/\\|;:<>?!¿¡^~`"\'';

                if (unableCharsInput.includes(evt.key)) {
                    evt.preventDefault()
                }
            }
        }
    )
})