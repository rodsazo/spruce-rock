<?php

namespace App\Theme;

class ThemeSetup
{

    const MENU_MAIN = 'main_menu';
    const MENU_DRAWER = 'drawer_menu';
    const MENU_MEMBERS = 'members';
    const MENU_FOOTER = 'footer_links';
    function __construct()
    {
        add_theme_support('post-thumbnails');

        add_action('get_header', [$this, 'removeHeaderBump']);
        add_action('wp_enqueue_scripts', [ $this, 'enqueueFrontEndScripts']);
        add_action('admin_enqueue_scripts', [ $this, 'enqueueAdminScripts']);
        add_action('enqueue_block_editor_assets', [ $this, 'enqueueGutenbergScripts']);
        add_action('admin_footer', [ $this, 'preventGutenbergLinks' ]);
        add_filter('tiny_mce_before_init', [ $this, 'limitTinyMceOptions']);
        add_filter('wp_enqueue_scripts', [ $this, 'mi_script_menu']);

        register_nav_menu(self::MENU_MAIN,'Main Menu');
        /*register_nav_menu(self::MENU_MEMBERS, 'Members Menu');
        register_nav_menu(self::MENU_FOOTER, 'Footer Links');
        register_nav_menu(self::MENU_DRAWER, 'Drawer Menu');*/
    }

    public function removeHeaderBump() {
        remove_action('wp_head', '_admin_bar_bump_cb');
    }

    function enqueueFrontEndScripts() : void
    {
        $theme = wp_get_theme();
        $version = $theme->get('Version');

        wp_enqueue_style('general_styles', get_template_directory_uri() . '/dist/css/app.css', [],  $version );
        wp_enqueue_style('general_font', get_template_directory_uri() . '/dist/fonts/graphik/fonts.css', [],  $version );
        wp_enqueue_script('general_scripts', get_template_directory_uri() . '/dist/js/main.js', ['jquery'], $version, [
            'in_footer' => true
        ] );
    }

    function enqueueAdminScripts () : void
    {
        $theme = wp_get_theme();
        $version = $theme->get('Version');

        wp_enqueue_style('general_style', get_template_directory_uri() . '/dist/css/admin-styles.css', [],  $version );
    }

    function enqueueGutenbergScripts() : void
    {
        $theme = wp_get_theme();
        $version = $theme->get('Version');

        wp_enqueue_style('gutenberg', get_template_directory_uri() . '/dist/css/gutenberg.css', [], $version );
        wp_enqueue_style('general_font', get_template_directory_uri() . '/dist/fonts/graphik/fonts.css', [],  $version );
        wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Young+Serif&display=swap', [], $version );
        wp_enqueue_style('adobe-fonts', 'https://use.typekit.net/wqz3nuq.css', [], $version );
    }
    public function preventGutenbergLinks ()
    {
        if( is_gutenberg_editor() ) {
            ?>
            <script>
                jQuery( function ($){

                    $('body').on('click', '.editor-styles-wrapper a', function(e){
                        e.preventDefault();
                    });
                });
            </script>
            <?php
        }
    }

    public function limitTinyMceOptions( $options )
    {
        // Restrict to only paragraph, h2, h3, h4
        $options['block_formats'] = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4';
        return $options;
    }

    public function mi_script_menu() {
        //wp_enqueue_script('mi-menu', get_theme_file_uri('/assets/js/menu.js'), array('jquery'), null, true);

        wp_localize_script('mi-menu', 'WP_DATA', array(
            'isFrontPage' => is_front_page()
        ));
    }
}
