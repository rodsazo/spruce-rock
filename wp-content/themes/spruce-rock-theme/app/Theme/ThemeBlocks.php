<?php
namespace App\Theme;

use App\Helpers\BlockCategory;

class ThemeBlocks
{
    function __construct() {
        $blocks = new BlockCategory('Spruce Rock');
        $blocks->addBlock( 'home-hero', 'Main Hero', '', ['main', 'hero']);
        $blocks->addBlock( 'stats', 'Stats', '', ['stats']);
        $blocks->addBlock( 'grid-image', 'Media And Text', '', ['media','text']);
        $blocks->addBlock( 'grid-icons', 'Grid with Icons', '', ['grid','icons']);
        $blocks->addBlock( 'grid-icons-font', 'Cards Grid', '', ['grid','icons','font']);
        $blocks->addBlock( 'team', 'Team Grid', 'Meet Team', ['team']);
        $blocks->addBlock( 'last-blogs', 'Latest posts', 'Latest posts', ['latest','posts']);
        $blocks->addBlock( 'page-hero', 'Page Hero', '', ['page', 'hero']);
        $blocks->addBlock( 'simple-text', 'Simple Text Block', '', ['simple', 'text']);
    }
}