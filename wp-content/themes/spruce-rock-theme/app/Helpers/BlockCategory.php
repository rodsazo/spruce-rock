<?php

namespace App\Helpers;
class BlockCategory
{
    public $name;
    public $slug;
    public $parent;
    public $blocks = [];
    function __construct( $name, $parent = null) {
        $this->parent = $parent;
        $this->name   = $name;
        $this->slug = sanitize_title( $name );
        add_filter('block_categories_all', [ $this, 'filterCategories'], 10, 2);
        add_action('acf/init', [ $this, 'registerBlocks'] );
    }

    function addBlock( $name, $title, $description = '', $keywords = [] ) {

        $block_data            = [
            'name' => $name,
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'render_callback' => [$this, 'renderBlock'],
            'supports' => [
                'jsx' => true
            ],
            'category' => $this->slug,
            'example' => [
                'attributes' => [
                    'data' => [
                        'title' => 'Lorem Ipsum Dolor Sit Amaet',
                        'eyebrow' => 'Eyebrow Text',
                        'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                        'buttons' => [['link' => ['url' => '#','title' => 'Button' ]]],
                        'is_preview' => true,
                    ]
                ]
            ],
        ];

        if ($this->parent){
            $block_data['parent'] = 'acf/' . $this->parent;
            $block_data['icon'] = 'excerpt-view';
        }

        $this->blocks[] = $block_data;
    }

    function registerBlocks ()
    {
        if( function_exists( 'acf_register_block_type' )) {
            usort( $this->blocks, fn($a, $b) => strcmp( $a['title'], $b['title'] ) );
            foreach( $this->blocks as $block ) {
                acf_register_block_type( $block );
            }
        }
    }

    function filterCategories( $categories, $post ) {
        $new_categories = [
            [
                'slug'  => $this->slug,
                'title' => $this->name,
            ],
        ];

        foreach ($categories as $category) {
            $new_categories[] = $category;
        }

        return $new_categories;
    }

    function renderBlock( $block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false ) {
        $is_live_preview = $block['data']['is_preview'] ?? false;

        $block_name = $block['name'];
        $parts = explode('/', $block_name);
        $block_name = array_pop($parts);

        if( $is_live_preview && $this->showPreviewImage( $block_name ) ) {
            return;
        }

        $top_spacing = get_field('block_top_spacing');
        $bottom_spacing = get_field('block_bottom_spacing');
        $background = get_field('block_background');
        $layout = get_field('block_layout');
        $top_inside_spacing = get_field('block_top_inside_spacing');
        $bottom_inside_spacing = get_field('block_bottom_inside_spacing');
        $block_border_top = get_field('block_border_top');
        $block_border_bottom = get_field('block_border_bottom');

        $extra_classes = [];
        if( $top_spacing ) {
            $extra_classes[] = 'spacing--top-' . $top_spacing;
        }
        if( $bottom_spacing ) {
            $extra_classes[] = 'spacing--bottom-' . $bottom_spacing;
        }
        if( $background ) {
            $extra_classes[] = 'gcBlock--bg-' . $background;
        }
        if( $layout ) {
            $extra_classes[] = 'gcBlock--layout-' . $layout;
        }
        if( $top_inside_spacing ) {
            $extra_classes[] = 'inside-spacing--top-' . $top_inside_spacing;
        }
        if( $bottom_inside_spacing ) {
            $extra_classes[] = 'inside-spacing--bottom-' . $bottom_inside_spacing;
        }
        if( $block_border_top ) {
            $extra_classes[] = 'border-top';
        }
        if( $block_border_bottom ) {
            $extra_classes[] = 'border-bottom';
        }

        $anchor_id = get_field('block_anchor_id');
        $anchor_attr = $anchor_id ? ' id="' . $anchor_id . '"' : '';

        ?>
        <div <?php echo $anchor_attr; ?> class="gcBlock <?php echo implode(' ', $extra_classes ) ?>">
            <?php get_template_part( 'parts/blocks/' . $block_name . '-block' ); ?>
        </div>
        <?php
    }

    protected function showPreviewImage( $block_name ) {
        $preview_uri = '/block-previews/' . $block_name . '.png';
        if( file_exists( get_stylesheet_directory() . $preview_uri )) {
            ?>
            <img src="<?php echo get_stylesheet_directory_uri() . $preview_uri; ?>" style="display: block; width: 100%; height: auto" />
            <?php
            return true;
        }
        return false;
    }
}