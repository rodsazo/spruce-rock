<?php

use App\Theme\ThemePostTypes;

require_once 'common.php';
require_once 'vendor/autoload.php';
require_once 'setup.php';

function getPostDateString( $post ) {
    $date_string = '';
    switch( $post->post_type ) {
        case ThemePostTypes::BLOG:
        case ThemePostTypes::PODCAST:
            $date_string = mysql2date('M j, Y', $post->post_date);
            break;
        case ThemePostTypes::EVENT:
            $firstStartDate = get_field('event_start_date',$post->ID);
            $firstEndDate = get_field('event_end_date',$post->ID);
            $firstStartTime = get_field('event_start_time',$post->ID);
            $firstEndTime = get_field('event_end_time',$post->ID);
            $date_string = format_date_range($firstStartDate,$firstEndDate,$firstStartTime,$firstEndTime);
            break;
    }

    return $date_string;
}

function getPostTypeName ( $post )
{
    return match( $post->post_type ) {
        ThemePostTypes::PODCAST => 'Podcast',
        ThemePostTypes::EVENT => 'Event',
        default => 'News',
    };
}
