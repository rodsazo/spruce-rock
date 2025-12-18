jQuery(function ($) {
  const observer = new IntersectionObserver(function (entries, observer) {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        entry.target.style.setProperty('--intersectDelay', index * 0.2 + 's');
        entry.target.classList.add('intersected');
      }
    });
  }, {
    threshold: 0.25
  });
  $('.intersect').each(function (i, el) {
    observer.observe(el);
  });
});
jQuery(function ($) {
  const $body = $('body');
  const $btn = $('.barmenu__mobile-button');
  const $menu = $('.mob-menu');
  const $nav = $('.mob-menu__nav');
  const $barmenu = $('.barmenu');
  const $logoActivo = $barmenu.find('.barmenu__logo--active');
  const $logoInactivo = $barmenu.find('.barmenu__logo');
  const $firstButton = $barmenu.find('.btn-first-button');
  let sticky_threshold = 0;
  let is_open = false;
  const $title = $('<li class="menu-item menu-item-extra"><a href="/">Home</a></li>');
  $nav.prepend($title);
  const $f_button = $('<li><a class="btn-first" href="' + $firstButton.attr('href') + '">' + $firstButton.text() + '</a></li>');
  $nav.append($f_button);
  $btn.on('click', toggleMenu);
  function toggleMenu() {
    if (is_open) {
      $body.removeClass('mob-menu-open');
      $menu.fadeOut();
      $btn.removeClass('active');
      $barmenu.removeClass('barmenu--active');
      const current_scroll = window.scrollY;
      if (current_scroll > sticky_threshold) {
        $barmenu.addClass('barmenu--font');
      }
      $logoActivo.css('display', 'none');
      $logoInactivo.css('display', 'block');
    } else {
      $body.addClass('mob-menu-open');
      $menu.show();
      $btn.addClass('active');
      $barmenu.addClass('barmenu--active');
      $barmenu.removeClass('barmenu--font ');
      $logoActivo.css('display', 'block');
      $logoInactivo.css('display', 'none');
    }
    is_open = !is_open;
  }
  const menu = document.querySelector('.mob-menu__nav');
  if (!menu.querySelector('.current-menu-item')) {
    menu.querySelector('.menu-item-extra').classList.add('current-menu-item');
  }
});
jQuery(function ($) {
  const $slides = $(".slides");
  const slideCount = $(".slide").length;
  let slideWidth = $(".slide").outerWidth();
  const $slide = $(".slide").first();
  if (!$slides.length) {
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