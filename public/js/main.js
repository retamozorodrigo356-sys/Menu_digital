/**
 * main.js - JavaScript Propio del Menú Digital "La Pizza Nostra"
 * Proporciona interactividad, cálculo dinámico en tiempo real y validación para el Menú Virtual en Local.
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('🍕 Menú Virtual La Pizza Nostra - JS Propio inicializado correctamente.');

    // Inicializar cálculo si estamos en el formulario de pedido POST
    if (document.getElementById('form-pedido-post')) {
        actualizarPrecioRealTime();
        configurarValidacionFormulario();
    }
});

/**
 * Tabla de precios y multiplicadores para JavaScript
 */
const CONFIG_PRECIOS = {
    tamanos: {
        'personal': 0.8,
        'mediana': 1.0,
        'familiar': 1.4,
        'jumbo': 1.8
    },
    masas: {
        'tradicional': 0.00,
        'delgada': 0.00,
        'borde_queso': 2.50,
        'integral': 1.50
    }
};

/**
 * Recalcula el precio total en tiempo real según la configuración elegida por el usuario
 */
function actualizarPrecioRealTime() {
    const selectPizza = document.getElementById('pizza_id');
    const selectTamano = document.getElementById('tamano');
    const selectMasa = document.getElementById('tipo_masa');
    const inputCantidad = document.getElementById('cantidad');
    const checkboxesExtras = document.querySelectorAll('.extra-checkbox');

    if (!selectPizza || !selectTamano || !selectMasa || !inputCantidad) return;

    // Obtener opción seleccionada de la pizza base
    const pizzaOption = selectPizza.options[selectPizza.selectedIndex];
    const precioBaseUnitario = parseFloat(pizzaOption.getAttribute('data-precio') || 0);

    // Multiplicador de tamaño
    const tamanoVal = selectTamano.value;
    const multiplicador = CONFIG_PRECIOS.tamanos[tamanoVal] || 1.0;

    // Adicional por tipo de masa
    const masaVal = selectMasa.value;
    const precioMasaExtra = CONFIG_PRECIOS.masas[masaVal] || 0.0;

    // Precio base unitario ajustado por tamaño y masa
    const precioUnitarioAjustado = (precioBaseUnitario * multiplicador) + precioMasaExtra;

    // Sumar extras seleccionados
    let totalExtrasUnitario = 0;
    checkboxesExtras.forEach(cb => {
        if (cb.checked) {
            totalExtrasUnitario += parseFloat(cb.getAttribute('data-precio') || 0);
        }
    });

    // Cantidad
    const cantidad = Math.max(1, parseInt(inputCantidad.value) || 1);

    // Cálculos finales (Atención Exclusiva en Local: $0 Delivery)
    const subtotal = (precioUnitarioAjustado + totalExtrasUnitario) * cantidad;
    const iva = subtotal * 0.12;
    const totalFinal = subtotal + iva;

    // Actualizar elementos DOM del resumen
    document.getElementById('calc-base').textContent = '$' + precioUnitarioAjustado.toFixed(2);
    document.getElementById('calc-extras').textContent = '$' + totalExtrasUnitario.toFixed(2);
    document.getElementById('calc-cant-lbl').textContent = cantidad;
    document.getElementById('calc-subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('calc-iva').textContent = '$' + iva.toFixed(2);
    document.getElementById('calc-total-final').textContent = '$' + totalFinal.toFixed(2);

    // Cambiar la imagen del resumen dinámicamente si existe la asociación
    actualizarVistaPreviaImagen(selectPizza.value);
}

/**
 * Cambia la imagen y título en la tarjeta de vista previa del pedido
 */
function actualizarVistaPreviaImagen(pizzaId) {
    const previewImg = document.getElementById('preview-pizza-img');
    const previewNombre = document.getElementById('preview-pizza-nombre');

    if (!previewImg || !previewNombre) return;

    const mapaImagenes = {
        '1': { nombre: 'Pizza Margherita Tradizionale', src: 'assets/images/margherita.jpg' },
        '2': { nombre: 'Pepperoni Speciale', src: 'assets/images/pepperoni.jpg' },
        '3': { nombre: 'Quattro Formaggi Gourmet', src: 'assets/images/quattro_formaggi.jpg' },
        '4': { nombre: 'BBQ Chicken & Smoke', src: 'assets/images/bbq_chicken.jpg' }
    };

    if (mapaImagenes[pizzaId]) {
        previewImg.src = mapaImagenes[pizzaId].src;
        previewNombre.textContent = mapaImagenes[pizzaId].nombre;
    }
}

/**
 * Configura la validación interactiva y la alerta SweetAlert2 para el envío POST en local
 */
function configurarValidacionFormulario() {
    const form = document.getElementById('form-pedido-post');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos Incompletos',
                    text: 'Por favor ingresa tu Nombre y Número de Mesa.',
                    confirmButtonColor: '#d9381e'
                });
            }
        } else {
            // Animación de envío exitoso
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Enviando Pedido a Cocina...',
                    text: 'Guardando datos en $_POST y reservando en tu sesión temporal.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }

        form.classList.add('was-validated');
    });
}
