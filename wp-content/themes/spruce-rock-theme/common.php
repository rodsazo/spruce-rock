<?php

add_image_size('huge', 1600);
define('TEMPLATE_URL', get_stylesheet_directory_uri() );
define('URL', get_bloginfo('url'));

/*
 * Remove Default WordPress Blocks
 */
add_filter( 'allowed_block_types_all', function( $allowed_blocks, $editor_context ) {
    $final_list = [];
    $blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
    $blocks = array_keys( $blocks );
    $whitelist = array(
        'core/paragraph',
        // Add other core blocks you want to allow
    );
    // Example: Whitelist specific blocks
    foreach( $blocks as $block ) {
        if( in_array( $block, $whitelist ) || str_starts_with( $block, 'acf' ) ) {
            $final_list[] = $block;
        }
    }
    return $final_list;
}, 10, 2 );

function custom_tinymce_font_classes( $settings ) {

    $style_formats = array(
        array(
            'title' => 'Small',
            'inline' => 'span',
            'classes' => 't-18 t-trim'
        ),
        array(
            'title' => 'Regular',
            'inline' => 'span',
            'classes' => 't-24 t-trim'
        ),
        array(
            'title' => 'Large',
            'inline' => 'span',
            'classes' => 't-32 t-trim'
        ),
    );

    // Convertir a JSON para TinyMCE
    $settings['style_formats'] = wp_json_encode( $style_formats );

    return $settings;
}
add_filter( 'tiny_mce_before_init', 'custom_tinymce_font_classes' );
function add_custom_fontsize_menu( $buttons ) {
    array_push( $buttons, 'styleselect' );  
    return $buttons;
}
add_filter( 'mce_buttons', 'add_custom_fontsize_menu' );

add_filter('acf/load_field/name=contact_form_id', 'cargar_forms_gravity_en_acf');
function cargar_forms_gravity_en_acf($field) {

    // Limpia opciones existentes
    $field['choices'] = array();

    // Obtener todos los formularios
    $forms = GFAPI::get_forms();

    // Llenar select: clave = ID, valor = Título
    foreach ($forms as $form) {
        $field['choices'][$form['id']] = $form['title'];
    }

    return $field;
}

function is_gutenberg_editor() {
    // Check if in admin and current screen is set
    if ( is_admin() && function_exists('get_current_screen') ) {
        $screen = get_current_screen();

        // Gutenberg (Block Editor) is used for 'post' or 'page' and the editor is block-based
        if ( method_exists( $screen, 'is_block_editor' ) ) {
            return $screen->is_block_editor();
        }

        // Fallback: Gutenberg screen ID patterns
        return isset( $screen->id ) && strpos( $screen->id, 'block-editor' ) !== false;
    }

    return false;
}

/*
 * Remove default WP types
 */

add_action( 'admin_menu', function() {
    // Hide "Posts"
    remove_menu_page( 'edit.php' );
    // Hide "Comments"
    remove_menu_page( 'edit-comments.php' );
}, 20);

// Remove from the top admin bar as well (optional)
add_action( 'admin_bar_menu', function ( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'new-post' );
    $wp_admin_bar->remove_node( 'comments' );
}, 999 );


/*
 * Tracking codes
 */

function tracking_codes( $field_name ) {
    static $enable_tracking;
    if( !isset( $enable_tracking ) ) {
        $enable_tracking = get_field( 'enable_tracking', 'options' );
    }
    if( $enable_tracking ) {
        echo get_field( $field_name, 'options' );
    }
}


/*
 * Remove Author from oEmbed
 */

add_filter( 'oembed_response_data', 'disable_embeds_filter_oembed_response_data_' );
function disable_embeds_filter_oembed_response_data_( $data ) {
    unset($data['author_url']);
    unset($data['author_name']);
    return $data;
}

/*
 * Create the button group 
 */

function the_buttons($button_repeater,$centered = false){
    ?>
    <div class="btn__group <?= $centered?'btn__group--centered':''?>">
    <?php
    foreach($button_repeater as $button_data){
        $link = $button_data['link']?? [];
        $style = $button_data['style']??'primary'; 
        the_button($link,$style);
    }
    ?>
    </div>
    <?php
}
function the_button($link,$style = 'primary'){
    if(empty($link['url'])){return;}
    ?>
    <a href="<?= $link['url']?>" class="btn btn--<?= $style?> t-16 t-trim">            
        <?= $link['title']?>
    </a>
    <?php
}
function format_date_range($startDate, $endDate, $startTime = null, $endTime = null) {
    // 🔹 Limpieza inicial
    $startDate = trim($startDate);
    $endDate   = trim($endDate);

    // 🔹 Función para convertir una fecha a timestamp desde cualquier formato
    $parseDate = function($date) {
        if (empty($date)) return false;

        $formats = [
            'Y-m-d',
            'Y/m/d',
            'd-m-Y',
            'd/m/Y',
            'm-d-Y',
            'm/d/Y',
            'Y-m-d H:i',
            'd/m/Y H:i',
            'Y-m-d H:i:s',
            'd/m/Y H:i:s',
        ];

        foreach ($formats as $format) {
            $dt = DateTime::createFromFormat($format, $date);
            if ($dt !== false) {
                return $dt->getTimestamp();
            }
        }

        // Último intento: strtotime (más flexible)
        $timestamp = strtotime($date);
        return $timestamp ?: false;
    };

    $start = $parseDate($startDate);
    $end   = $parseDate($endDate);

    if (!$start || !$end) {
        return '';
    }

    $hasTime = (!empty($startTime) && !empty($endTime));

    // 🔹 Si es el mismo día
    if (date('Ymd', $start) === date('Ymd', $end)) {
        return date_i18n('M j, Y', $start) . ($hasTime ? ", $startTime - $endTime" : '');
    }

    // 🔹 Mismo mes y año
    if (date('Ym', $start) === date('Ym', $end)) {
        return date_i18n('M j', $start) . '–' . date_i18n('j, Y', $end) . ($hasTime ? ", $startTime - $endTime" : '');
    }

    // 🔹 Mismo año, distinto mes
    if (date('Y', $start) === date('Y', $end)) {
        return date_i18n('M j', $start) . '–' . date_i18n('M j, Y', $end) . ($hasTime ? ", $startTime - $endTime" : '');
    }

    // 🔹 Años distintos
    return date_i18n('M j, Y', $start) . '–' . date_i18n('M j, Y', $end) . ($hasTime ? ", $startTime - $endTime" : '');
}
function sanitize_phone( $phone ) {
    // Remove all characters except digits and "+"
    $cleaned = preg_replace('/[^\d+]/', '', $phone);

    // Ensure only the first "+" is kept (if present)
    if (substr($cleaned, 0, 1) === '+') {
        // Keep first + and remove any others
        $cleaned = '+' . preg_replace('/[^0-9]/', '', substr($cleaned, 1));
    } else {
        // Remove all non-digits (no plus at start)
        $cleaned = preg_replace('/\D/', '', $cleaned);
    }

    return $cleaned;
}