// slider.js
$(document).ready(function () {
    // Inicializar el slider principal
    if ($('.slider').length > 0) {
        $('.slider').slick({
            autoplay: true,
            dots: true,
            arrows: true,
            infinite: true,
            speed: 900,
            slidesToShow: 1,
            slidesToScroll: 1
        });
    } else {
        console.error("El selector '.slider' no coincide con ningún elemento.");
    }
});