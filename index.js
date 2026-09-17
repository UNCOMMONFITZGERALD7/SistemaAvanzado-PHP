const modal = document.getElementById('modal');
const btnAbrir = document.getElementById('btnAbrirModal');
const btnAbrirEditar = document.getElementById('editar-producto');

// 1. DEFINIR EL STRING DEL BOTÓN DE CERRAR NATIVO
// Pon aquí las clases o estilos que ya tenías para tu botón de cerrar
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
                // Inyectamos el botón de cerrar junto con la plantilla de productos
                modal.innerHTML = botonCerrarHTML + html; //

                // LLAMADA CLAVE: Buscamos el nuevo botón inyectado y le asignamos el evento click
                reprogramarBotonCerrar(); //
                modal.showModal();
            })
            .catch(error => {
                console.error('Error:', error);
                modal.innerHTML = botonCerrarHTML + '<p>Error al cargar el modal</p>';
                reprogramarBotonCerrar(); //
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
                // Inyectamos el botón de cerrar junto con la plantilla de categorías
                modal.innerHTML = botonCerrarHTML + html; //
                
                // LLAMADA CLAVE: Volvemos a activar el botón de cerrar recién creado
                reprogramarBotonCerrar(); //
                modal.showModal();
            })
            .catch(error => {
                console.error('Error:', error);
                modal.innerHTML = botonCerrarHTML + '<p>Error al cargar el modal</p>';
                reprogramarBotonCerrar(); //
                modal.showModal();
            });
    });
}

// 2. LA FUNCIÓN COMPLETA
function reprogramarBotonCerrar() {
    const nuevoBtnCerrar = document.getElementById('btnCerrar');
    if (nuevoBtnCerrar && modal) {
        nuevoBtnCerrar.addEventListener('click', () => {
            modal.close();
            // Al cerrar, dejamos el modal limpio solo con el botón por defecto
            modal.innerHTML = botonCerrarHTML; 
        });
    }
}

// Inicializamos el comportamiento por si el modal tiene un botón de cerrar de fábrica
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