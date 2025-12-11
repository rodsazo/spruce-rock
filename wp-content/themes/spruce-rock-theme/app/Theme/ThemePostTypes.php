<?php

namespace App\Theme;
use App\Helpers\PostType;

class ThemePostTypes
{
    const BLOG = 'blog';
    const EVENT = 'event';
    const PODCAST = 'podcast';
    const TEAM = 'team';
    const COMMUNITY = 'community';
    public function __construct ()
    {
        /*
        $blog = new PostType( self::BLOG, 'blog');
        $blog->autoLabels('Post', 'Posts');
        $blog->l_menu_name = 'Blog';
        $blog->supports = ['title', 'editor', 'thumbnail'];
        // $blog->taxonomies = ['category'];

        $event = new PostType( self::EVENT, 'events');
        $event->autoLabels('Event', 'Events');
        $event->supports = ['title', 'editor', 'thumbnail'];

        $podcast = new PostType( self::PODCAST, 'podcasts');
        $podcast->autoLabels('Episode', 'Episodes');
        $podcast->l_menu_name = 'Podcast';
        $podcast->menu_icon = 'dashicons-microphone';
        $podcast->supports = ['title', 'editor', 'thumbnail'];*/

        $teamblock = new PostType( self::TEAM, 'team');
        $teamblock->autoLabels('Member', 'Members');
        $teamblock->l_menu_name = 'Team';
        $teamblock->menu_icon = 'dashicons-groups';
        $teamblock->supports = ['title', 'thumbnail'];

        /*$community = new PostType( self::COMMUNITY, 'communities');
        $community->autoLabels('Community Group', 'Communities');
        $community->l_menu_name = 'Communities';
        $community->menu_icon = 'dashicons-buddicons-buddypress-logo';
        $community->supports = ['title', 'editor', 'thumbnail'];*/

    }
}