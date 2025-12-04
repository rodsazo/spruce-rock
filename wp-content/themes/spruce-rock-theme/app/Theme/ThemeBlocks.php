<?php
namespace App\Theme;

use App\Helpers\BlockCategory;

class ThemeBlocks
{
    function __construct() {
        $blocks = new BlockCategory('Spruce Rock');
        $blocks->addBlock( 'home-hero', 'Main Hero', '', ['banner', 'title']);
        $blocks->addBlock( 'stats', 'Stats', '', ['stats']);
    }
}