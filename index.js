const modal = document.getElementById('modal');
const btnAbrir = document.getElementById('btnAbrirModal');
const btnCerrar = document.getElementById('btnCerrar');

if (btnAbrir && modal) {
    btnAbrir.addEventListener('click', () => {
        modal.showModal();
    });
}

if (btnCerrar && modal) {
    btnCerrar.addEventListener('click', () => {
        modal.close();
    });
}

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