jQuery(function ($) {
    const $slides = $(".slides");
    const slideCount = $(".slide").length;
    let slideWidth = $(".slide").outerWidth();
    const $slide = $(".slide").first();

    if( !$slides.length) {
        return;
    }

    // 🔑 índice actual
    let currentIndex = 0;

    function goToSlide(index) {
        slideWidth = $(".slide").outerWidth(); // recalcular siempre
        $slides.css("transform", `translateX(-${index * slideWidth}px)`);
        currentIndex = index;
    }

    function calculateTotalWidth() {
        slideWidth = $(".slide").outerWidth();
        const totalWidth = slideWidth * slideCount;
        $slides.css("width", totalWidth + "px");

        // 👇 Reajustamos la posición actual después del resize
        goToSlide(currentIndex);
    }

    // Observa cambios de tamaño en el slide
    const resizeObserver = new ResizeObserver(entries => {
        for (let entry of entries) {
            slideWidth = $(entry.target).outerWidth();
            calculateTotalWidth();
        }
    });
    resizeObserver.observe($slide[0]);

    $(".next").on("click", function () {
        let id = $(this).data("id");
        if (id > slideCount - 1) {
            id = 0; // volver al inicio
        }
        goToSlide(id);
    });

    $(".prev").on("click", function () {
        let id = $(this).data("id");
        if (id < 0) {
            id = slideCount - 1; // ir al último
        }
        goToSlide(id);
    });

    $(".x-btn, .carousel__wrapper").on("click", function () {
        $(".carousel__wrapper").hide();
        $(".carousel").removeClass("show").addClass("hide");
        $('body').removeClass('no-scroll');
    });

    $(".btnOpen").on("click", function () {
        let id = $(this).data("id");
        $('body').addClass('no-scroll');

        // Quitar transición momentáneamente
        $slides.css("transition", "none");
        goToSlide(id);

        // Forzar reflow
        $slides[0].offsetHeight;

        // Volver a habilitar transición
        $slides.css("transition", "");
        $(".carousel__wrapper").show();
        $(".carousel").removeClass("hide").addClass("show");
    });

    $(window).on("resize", function () {
        calculateTotalWidth();
    });

    $(document).ready(function () {
        calculateTotalWidth();
    });
});
