jQuery(function ($) {
  $('.accordion__content-right-sub-title-head').on('click', function () {
    const $this = $(this);
    const $body = $this.next('.accordion__content-right-body');
    const isActive = $this.hasClass('active');

    // Cierra todos
    $('.accordion__content-right-sub-title-head').removeClass('active');
    $('.accordion__content-right-body').slideUp(200);

    // Si no estaba activo, abrir
    if (!isActive) {
      $this.addClass('active');
      $body.css({
        display: 'flex',
        flexDirection: 'column'
      }).hide().slideDown(200, function () {
        // Centrar con scroll-behavior: smooth
        const element = $this[0];
        element.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
      });
    }
  });
});
jQuery(function ($) {
  const $track = $('.gallery__content-right-track');
  const $items = $('.gallery__content-right-subcontent');
  const $container = $('.gallery__content-right');
  const total = $items.length;
  let totalMove = 0;
  let currentIndex = 0;
  function updateGallery() {
    const itemWidth = $items.outerWidth(); // solo el ancho del ítem
    const gap = parseInt($track.css('gap')) || 0; // lee el gap definido en CSS
    totalMove = currentIndex * (itemWidth + gap);
    $track.css('transform', `translateX(-${totalMove}px)`);
  }
  $('.btn-gallery-right').on('click', function () {
    const itemWidth = $items.outerWidth();
    const gap = parseInt($track.css('gap')) || 0;
    const containerWidth = $('.gallery__content-right').width();
    const trackWidth = (itemWidth + gap) * total - gap;
    const newWidth = trackWidth - currentIndex * (itemWidth + gap) - containerWidth;
    if (newWidth > 0) {
      currentIndex++;
      updateGallery();
    }
  });
  $('.btn-gallery-left').on('click', function () {
    if (totalMove != 0) {
      currentIndex--;
      updateGallery();
    }
  });
});
jQuery(function ($) {
  const $body = $('body');
  const $btn = $('.barmenu__mobile-button');
  const $menu = $('.mob-menu');
  const $nav = $('.mob-menu__nav');
  const $barmenu = $('.barmenu');
  const $footer = $('.site-footer');
  const $logoActivo = $barmenu.find('.barmenu__logo--active');
  const $logoInactivo = $barmenu.find('.barmenu__logo');
  const firstButton = JSON.parse($menu.attr('data-firstbutton'));
  const secondButton = JSON.parse($menu.attr('data-secondbutton'));
  let sticky_threshold = 0;
  const $members = $menu.find('.menu-members');
  const $menufooter = $footer.find('.menu');
  let is_open = false;
  const $title = $('<li class="back-btn"><h2 class="mob-menu__title t-caption t-uppercase t-trim">Navigation</h2></li>');
  const $give = $(`<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children--arrow45 menu-item-139"><a href="${firstButton.url}">${firstButton.title}</a></li>`);
  const $watch = $(`<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children--arrow45 menu-item-139"><a href="${secondButton.url}">${secondButton.title}</a></li>`);
  $nav.prepend($title);
  $nav.append($give);
  $nav.append($watch);
  const $give2 = $(`<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children--arrow45 menu-item-139"><a href="${firstButton.url}">${firstButton.title}</a></li>`);
  const $watch2 = $(`<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children--arrow45 menu-item-139"><a href="${secondButton.url}">${secondButton.title}</a></li>`);
  $menufooter.append($give2);
  $menufooter.append($watch2);
  const $subtitle = $('<li class="back-btn"><h2 class="mob-menu__title--29 t-caption t-uppercase t-trim c-800">Members</h2></li>');
  $members.prepend($subtitle);
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
  // 🔹 Agregar flechas y botón "Atrás" a los submenús
  $nav.find('.menu-item-has-children').each(function () {
    const $item = $(this);
    const $submenu = $item.children('.sub-menu');
    if ($submenu.length) {
      // Obtener el texto del enlace principal
      const $link = $item.children('a');
      const titleText = $link.text().trim();
      const href = $link.attr('href') || '#';
      // Agregar botón "Atrás" al inicio del submenú

      const $menuTitle = $(`<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-142"><a href="${href}">${titleText}</a></li>`);
      $submenu.prepend($menuTitle);
      const $back = $('<li class="back-btn"><h2 class="mob-menu__title t-caption t-uppercase t-trim"><svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.49841 7.04167L6.76612 11.3094L6.00008 12.0833L0.416748 6.50001L6.00008 0.916672L6.76612 1.69063L2.49841 5.95834H11.5834V7.04167H2.49841Z" fill="#252E34"/></svg><span>BACK</span></h2></li>');
      $submenu.prepend($back);

      // Abrir submenú al hacer click en el enlace principal
      $item.children('a').on('click', function (e) {
        e.preventDefault();
        // Oculta todos los submenús antes de abrir el nuevo
        $nav.find('.sub-menu').removeClass('active');
        // Activa solo el submenú del item clicado
        $submenu.addClass('active');
        $nav.addClass('active');
        $members.addClass('active');
      });

      // Volver al menú principal
      $back.on('click', function (e) {
        e.preventDefault();
        $submenu.removeClass('active');
        $nav.removeClass('active');
        $members.removeClass('active');
      });
    }
  });
});
jQuery(function ($) {
  $('.player').each(function (i, el) {
    const $player = $(el);
    let is_playing = 0;
    let is_sound = 0;
    let isDragging = false;
    let issoundDragging = false;
    let playback_rate = 1;
    const playback_increment = 0.25;
    const playback_max_rate = 2;
    const audio = $player.find('audio')[0];
    const url = audio.currentSrc; // más sólido que audio.src
    const $playPauseBtn = $player.find('.player__playPause');
    const $playSoundBtn = $player.find('.player__soundOnOff');
    const $playbackRateBtn = $player.find('.player__playbackRate');
    const $playbackRateText = $player.find('.player__playbackRateText');
    const $forward10 = $player.find('.player__forward10');
    const $rewind10 = $player.find('.player__replay10');
    const $totalTime = $player.find('.player__duration');
    const $currentTime = $player.find('.player__currentTime');
    const $progress = $('.player__progress');
    const $soundprogress = $('.player__sound-progress');
    $totalTime.html(formatTime(audio.duration));
    const $descriptionDuration = $player.find('.player__description-duration');
    const $fileZise = $player.find('.player__description-file-zise');

    // Obtener tamaño del archivo
    fetch(url, {
      method: 'HEAD'
    }).then(res => {
      const size = res.headers.get('Content-Length');
      if (!size) {
        $fileZise.text('Size: unknown');
        return;
      }
      const sizeMB = (size / 1024 / 1024).toFixed(2);
      $fileZise.text(`${sizeMB} MB`);
    }).catch(err => console.error(err));
    $descriptionDuration.html(formatTime(audio.duration));
    /*
    Listeneres
     */
    audio.addEventListener('loadedmetadata', function () {
      $totalTime.html(formatTime(audio.duration));
      $descriptionDuration.html(formatTime(audio.duration));
    });
    $playPauseBtn.on('click', function () {
      if (is_playing) {
        pause();
      } else {
        play();
      }
    });
    $playSoundBtn.on('click', function () {
      if (is_sound) {
        on();
      } else {
        off();
      }
    });
    $rewind10.on('click', rewind10);
    $forward10.on('click', forward10);
    $playbackRateBtn.on('click', updatePlaybackRate);
    audio.addEventListener('timeupdate', function () {
      var progress = audio.currentTime / audio.duration;
      $currentTime.html(formatTime(audio.currentTime));
      setProgress(progress);
    });
    audio.addEventListener('volumechange', function () {
      setsoundProgress(audio.volume);
    });
    audio.addEventListener('ended', function () {
      pause();
    });
    $progress.on('mousedown', function (e) {
      isDragging = true;
      updateAudioTime(e);
    });
    $soundprogress.on('mousedown', function (e) {
      issoundDragging = true;
      updateSoundAudioTime(e);
    });
    $(document).on('mousemove', function (e) {
      if (isDragging) {
        updateAudioTime(e);
      }
      if (issoundDragging) {
        updateSoundAudioTime(e);
      }
    });
    $(document).on('mouseup', function () {
      isDragging = false;
      issoundDragging = false;
    });

    /*
    Methods
     */

    function play() {
      is_playing = true;
      audio.play();
      $player.addClass('playing');
    }
    function pause() {
      is_playing = false;
      audio.pause();
      $player.removeClass('playing');
    }
    function on() {
      is_sound = false;
      audio.volume = 1;
      $player.removeClass('sound');
    }
    function off() {
      is_sound = true;
      audio.volume = 0;
      $player.addClass('sound');
    }
    function setProgress(progress) {
      $player[0].style.setProperty('--player-progress', progress);
    }
    function setsoundProgress(progress) {
      $player[0].style.setProperty('--player-sound-progress', progress);
    }
    function rewind10() {
      audio.currentTime = Math.max(0, audio.currentTime - 10);
    }
    function forward10() {
      audio.currentTime = Math.min(audio.duration, audio.currentTime + 10);
    }
    function updateAudioTime(e) {
      const offset = $progress.offset().left;
      const width = $progress.width();
      let x = e.pageX - offset;
      x = Math.max(0, Math.min(x, width)); // clamp
      const percent = x / width;
      if (audio.duration) {
        audio.currentTime = percent * audio.duration;
      }
    }
    function updateSoundAudioTime(e) {
      const offset = $soundprogress.offset().left;
      const width = $soundprogress.width();
      let x = e.pageX - offset;
      x = Math.max(0, Math.min(x, width)); // clamp
      const percent = x / width;
      audio.volume = Math.min(percent, 1);
      if (audio.volume == 0) {
        $player.addClass('sound');
      } else {
        $player.removeClass('sound');
      }
    }
    function updatePlaybackRate() {
      let new_playback_rate = playback_rate + playback_increment;
      if (new_playback_rate > playback_max_rate) {
        new_playback_rate = 1;
      }
      audio.playbackRate = new_playback_rate;
      $playbackRateText.html(new_playback_rate);
      playback_rate = new_playback_rate;
    }
    function formatTime(seconds) {
      const mins = Math.floor(seconds / 60);
      const secs = Math.floor(seconds % 60);
      return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }
  });
});
jQuery(function ($) {
  const $carousel = $('.post-carousel');
  $carousel.each(function (i, el) {
    const $this_carousel = $(el);
    const $track = $this_carousel.find('.post-carousel__content-right-track');
    const $items = $this_carousel.find('.post-carousel__content-right-subcontent');
    const $container = $this_carousel.find('.post-carousel__content-right');
    const total = $items.length;
    let totalMove = 0;
    let currentIndex = 0;
    function updateCarousel() {
      const itemWidth = $items.outerWidth(); // solo el ancho del ítem
      const gap = parseInt($track.css('gap')) || 0; // lee el gap definido en CSS
      totalMove = currentIndex * (itemWidth + gap);
      $track.css('transform', `translateX(-${totalMove}px)`);
    }
    $this_carousel.find('.btn-post-carousel-right').on('click', function () {
      const itemWidth = $items.outerWidth();
      const gap = parseInt($track.css('gap')) || 0;
      const containerWidth = $this_carousel.find('.post-carousel__content-right').width();
      const trackWidth = (itemWidth + gap) * total - gap;
      const newWidth = trackWidth - currentIndex * (itemWidth + gap) - containerWidth;
      if (newWidth > 0) {
        currentIndex++;
        updateCarousel();
      }
    });
    $this_carousel.find('.btn-post-carousel-left').on('click', function () {
      if (totalMove != 0) {
        currentIndex--;
        updateCarousel();
      }
    });
  });
});
jQuery(function ($) {
  $('.btn-menu').on('click', function () {
    $('body').addClass('overflow');
    $('.rightmenu').addClass('rightmenu--active');
  });
  $('.rightmenu__close-area').on('click', function () {
    $('body').removeClass('overflow');
    $('.rightmenu').removeClass('rightmenu--active');
  });
  $('.menu > li').on('mouseenter', function () {
    $('.menu > li').removeClass('active');
    $(this).addClass('active');
  });
});
jQuery(function ($) {
  if (WP_DATA.isFrontPage) {
    $('body').addClass('is-front');
  }
  const $menu = $('.stickyHeader');
  let scrolling = false;
  const min_scroll_sticky = 300;
  let sticky_threshold = 0;
  let previous_scroll = window.scrollY;
  const $globalBanner = $('.topBar');
  $(window).on('scroll', function () {
    if (!scrolling) {
      scrolling = true;
      requestAnimationFrame(scroll);
    }
  });
  if ($globalBanner.length) {
    onResize();
    $(window).on('resize', onResize);
  }
  function scroll() {
    const current_scroll = window.scrollY;
    const scrolling_up = current_scroll - previous_scroll < 0;
    if (scrolling_up && current_scroll >= min_scroll_sticky) {
      $menu.addClass('sticky in');
      if ($('body').hasClass('is-front')) {
        $menu.addClass('barmenu--font');
      }
    } else if (!scrolling_up && current_scroll >= min_scroll_sticky) {
      $menu.removeClass('in');
      if ($('body').hasClass('is-front')) {
        $menu.removeClass('barmenu--font');
      }
    } else if (scrolling_up && current_scroll <= sticky_threshold) {
      $menu.removeClass('sticky');
      if ($('body').hasClass('is-front')) {
        $menu.removeClass('barmenu--font');
      }
    }
    previous_scroll = current_scroll;
    scrolling = false;
  }
  function onResize() {
    sticky_threshold = $globalBanner.outerHeight();
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