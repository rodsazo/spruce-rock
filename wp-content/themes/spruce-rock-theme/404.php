<?php
$content_id = get_field('404_page', 'options');
get_header();

if( $content_id ) {
    $error_page = get_post( $content_id );
    echo apply_filters('the_content', $error_page->post_content);
}

get_footer();
