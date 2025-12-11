jQuery( function ($){
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
    const $f_button = $('<li><a class="btn-first" href="'+$firstButton.attr('href')+'">'+$firstButton.text()+'</a></li>');
    $nav.append($f_button);
    $btn.on('click', toggleMenu );
    function toggleMenu(){
        if( is_open ) {
            $body.removeClass('mob-menu-open');
            $menu.fadeOut();
            $btn.removeClass('active');
            $barmenu.removeClass('barmenu--active');
            const current_scroll = window.scrollY;
            if( current_scroll > sticky_threshold) {
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