/**
 * main.js - JavaScript Propio del Menú Digital "La Pizza Nostra"
 * Proporciona interactividad y mejoras de experiencia en el Menú Digital.
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('🍕 Menú Digital La Pizza Nostra - JS Propio inicializado correctamente.');

    // Inicializar animación de aparición de las tarjetas del catálogo
    if (document.querySelector('.product-card')) {
        animarTarjetasCatalogo();
    }
});

/**
 * Aplica una animación suave de entrada a las tarjetas de pizza del menú
 */
function animarTarjetasCatalogo() {
    const tarjetas = document.querySelectorAll('.product-card');

    tarjetas.forEach(function (tarjeta, index) {
        tarjeta.style.opacity = '0';
        tarjeta.style.transform = 'translateY(12px)';
        tarjeta.style.transition = 'opacity .4s ease, transform .4s ease';

        setTimeout(function () {
            tarjeta.style.opacity = '1';
            tarjeta.style.transform = 'translateY(0)';
        }, index * 60);
    });
}
