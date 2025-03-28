// whatsapp.js
$(document).ready(function () {
    // Función para verificar si es horario laboral
    function isBusinessHours() {
        const now = new Date();
        const dayOfWeek = now.getDay(); // 0 (Domingo) a 6 (Sábado)
        const hour = now.getHours();

        // Define horario laboral (ejemplo: Lunes a Viernes, 9 AM a 6 PM)
        return dayOfWeek >= 1 && dayOfWeek <= 5 && hour >= 9 && hour < 18;
    }

    // Función para verificar si hay un operador disponible
    function isOperatorAvailable() {
        // Aquí puedes implementar la lógica para verificar si hay un operador activo.
        return true; // Cambia a `false` para simular que no hay operador disponible.
    }

    // Función para mostrar el modal de WhatsApp
    function showWhatsAppModal() {
        const modal = document.getElementById('whatsapp-modal');
        const operadorDisponible = document.getElementById('operador-disponible');

        if (isBusinessHours()) {
            modal.style.display = 'block';

            if (isOperatorAvailable()) {
                operadorDisponible.style.display = 'flex';
            } else {
                operadorDisponible.style.display = 'none';
            }
        } else {
            window.location.href = "https://wa.me/521234567890?text=Hola,%20estamos%20fuera%20de%20horario%20laboral.";
        }
    }

    // Función para cerrar el modal de WhatsApp
    function closeWhatsAppModal() {
        const modal = document.getElementById('whatsapp-modal');
        modal.style.display = 'none';
    }

    // Eventos
    const whatsappButton = document.getElementById('whatsapp-button');
    const closeModalButton = document.querySelector('.close-modal');

    if (whatsappButton) {
        whatsappButton.addEventListener('click', showWhatsAppModal);
    } else {
        console.error('El botón de WhatsApp no existe en el DOM.');
    }

    if (closeModalButton) {
        closeModalButton.addEventListener('click', closeWhatsAppModal);
    } else {
        console.error('El botón para cerrar el modal no existe en el DOM.');
    }

    // Cerrar modal si se hace clic fuera de él
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('whatsapp-modal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});