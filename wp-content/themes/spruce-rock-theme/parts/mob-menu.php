<?php
$firstButton = get_field('first_button', 'options');
$secondButton = get_field('second_button', 'options');
?>
<div class="mob-menu"
     data-firstbutton='<?= json_encode($firstButton); ?>'
     data-secondbutton='<?= json_encode($secondButton); ?>'>
    <div class="mob-menu__content">
        <div class="container">
            <?php wp_nav_menu([
                'theme_location' => 'main_menu',
                'menu_class' => 'mob-menu__nav',
            ]); ?>
            <?php
            wp_nav_menu([
                'theme_location' => \App\Theme\ThemeSetup::MENU_MEMBERS,
                'menu_class'     => 'menu-members',
                'container'      => false,
                'depth' => 1
            ]);
            ?>
        </div>
    </div>
</div>