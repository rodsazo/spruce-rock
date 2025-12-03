<?php

namespace App\Helpers;
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

final class Taxonomy
{

    public $l_name;
    public $l_singular_name;
    public $l_search_items;
    public $l_all_items;
    public $l_parent_item;
    public $l_parent_item_colon;
    public $l_edit_item;
    public $l_update_item;
    public $l_add_new_item;
    public $l_new_item_name;
    public $l_menu_name;

    public $l_filter_showall;

    public $hierarchical = TRUE;
    public $show_ui = TRUE;
    public $show_admin_column = TRUE;
    public $query_var = TRUE;
    public $rewrite = array('slug' => 'new-taxonomy');

    protected $labels = FALSE;

    protected $tax_name;
    protected $slug;
    protected $post_types;
    protected $filter_post_types;

    static protected $taxonomies = array();

    function __construct ( $tax_name, $slug, $post_types )
    {

        $this->tax_name   = $tax_name;
        $this->slug       = $slug;
        $this->post_types = (array)$post_types;

        add_action( 'init', array(&$this, '_doRegisterTaxonomy') );
        add_action( 'restrict_manage_posts', array(&$this, '_restrictAddFilter') );

        self::$taxonomies[$tax_name] = $this;

    }

    function getPostTypes ()
    {
        return $this->post_types;
    }

    function autoLabels ( $singular, $plural, $masculino = TRUE )
    {

        $masculine_str = array(
            'search_items' => 'Search %s',
            'all_items' => 'All %s',
            'parent_item' => 'Parent %s',
            'parent_item_colon' => 'Parent %s:',
            'edit_item' => 'Edit %s',
            'update_item' => 'Update %s',
            'add_new_item' => 'Add new %s',
            'new_item_name' => 'New %s name',
            'filter_showall' => 'Show all %s',
        );

        $feminine_str = array(
            'search_items' => 'Search %s',
            'all_items' => 'All %s',
            'parent_item' => 'Parent %s',
            'parent_item_colon' => 'Parent %s:',
            'edit_item' => 'Edit %s',
            'update_item' => 'Update %s',
            'add_new_item' => 'Add new %s',
            'new_item_name' => 'New %s name',
            'filter_showall' => 'Show all %s',
        );

        $str_set = $masculino ? $masculine_str : $feminine_str;

        $this->l_name              = "$plural";
        $this->l_singular_name     = "$singular";
        $this->l_search_items      = sprintf( $str_set['search_items'], $plural );
        $this->l_all_items         = sprintf( $str_set['all_items'], $plural );
        $this->l_parent_item       = sprintf( $str_set['parent_item'], $singular );
        $this->l_parent_item_colon = sprintf( $str_set['parent_item_colon'], $singular );
        $this->l_edit_item         = sprintf( $str_set['edit_item'], $singular );
        $this->l_update_item       = sprintf( $str_set['update_item'], $singular );
        $this->l_add_new_item      = sprintf( $str_set['add_new_item'], $singular );
        $this->l_new_item_name     = sprintf( $str_set['new_item_name'], $singular );
        $this->l_menu_name         = "$plural";

        $this->l_filter_showall = sprintf( $str_set['filter_showall'], $plural );
    }

    function enableFilter ( $post_types = array() )
    {

        $post_types = (array)$post_types;

        if (empty( $post_types )) {
            $post_types = $this->post_types;
        }

        $this->filter_post_types = $post_types;

    }

    function _restrictAddFilter ()
    {

        global $typenow;

        if (!empty( $this->filter_post_types )) {
            if (in_array( $typenow, $this->filter_post_types )) {

                $tax_obj = get_taxonomy( $this->slug );

                $output = array();
                $terms  = get_terms( $tax_obj->name, array('hide_empty' => false) );

                printf( '<select name="%1$s">', $tax_obj->name );

                $output[] = sprintf( '<option value="">%s</option>', $this->l_filter_showall );

                foreach ($terms as $one_term) {
                    $selected = selected( _g( $tax_obj->name ), $one_term->slug, false );
                    $output[] = sprintf( '<option %3$s value="%1$s">%2$s</option>', $one_term->slug, $one_term->name, $selected );
                }

                echo implode( '', $output );

                echo '</select>';

            }
        }

    }

    function _doRegisterTaxonomy ()
    {

        $tax_args = $this->getArgs();

        register_taxonomy( $this->tax_name, $this->post_types, $tax_args );

    }

    function getArgs ()
    {
        $tax_labels = array(
            'name' => $this->l_name,
            'singular_name' => $this->l_singular_name,
            'search_items' => $this->l_search_items,
            'all_items' => $this->l_all_items,
            'parent_item' => $this->l_parent_item,
            'parent_item_colon' => $this->l_parent_item_colon,
            'edit_item' => $this->l_edit_item,
            'update_item' => $this->l_update_item,
            'add_new_item' => $this->l_add_new_item,
            'new_item_name' => $this->l_new_item_name,
            'menu_name' => $this->l_menu_name
        );

        $tax_args = array(
            'labels' => $tax_labels,
            'hierarchical' => $this->hierarchical,
            'show_ui' => $this->show_ui,
            'show_in_rest' => true,
            'show_admin_column' => $this->show_admin_column,
            'query_var' => $this->query_var,
            'rewrite' => array('slug' => $this->slug)
        );

        return $tax_args;
    }

    static function CptUiPrintJson ()
    {
        $json = array();
        /**
         * @var Taxonomy $taxonomy
         */
        foreach (self::$taxonomies as $tax_name => $taxonomy) {
            $def_args                   = self::CptUiGetDefaultArgs();
            $def_args                   = array_merge( $def_args, $taxonomy->getArgs() );
            $def_args['name']           = $tax_name;
            $def_args['object_types']   = $taxonomy->getPostTypes();
            $slug                       = $def_args['rewrite']['slug'];
            $def_args['rewrite']        = 'true';
            $def_args['rewrite_slug']   = $slug;
            $def_args['label']          = $def_args['labels']['name'];
            $def_args['singular_label'] = $def_args['labels']['singular_name'];

            $json[$tax_name] = $def_args;
        }

        echo json_encode( $json );
        die;
    }

    protected static function CptUiGetDefaultArgs ()
    {
        return array(
            "name" => "the_slug",
            "label" => "Etiqueta Plural",
            "singular_label" => "Etiqueta Plural",
            "description" => "",
            "public" => "true",
            "publicly_queryable" => "true",
            "hierarchical" => "false",
            "show_ui" => "true",
            "show_in_menu" => "true",
            "show_in_nav_menus" => "true",
            "query_var" => "true",
            "query_var_slug" => "",
            "rewrite" => "true",
            "rewrite_slug" => "",
            "rewrite_withfront" => "1",
            "rewrite_hierarchical" => "0",
            "show_admin_column" => "false",
            "show_in_rest" => "true",
            "show_in_quick_edit" => "",
            "rest_base" => "",
            "rest_controller_class" => "",
            "labels" => array(
                "menu_name" => "",
                "all_items" => "",
                "edit_item" => "",
                "view_item" => "",
                "update_item" => "",
                "add_new_item" => "",
                "new_item_name" => "",
                "parent_item" => "",
                "parent_item_colon" => "",
                "search_items" => "",
                "popular_items" => "",
                "separate_items_with_commas" => "",
                "add_or_remove_items" => "",
                "choose_from_most_used" => "",
                "not_found" => "",
                "no_terms" => "",
                "items_list_navigation" => "",
                "items_list" => ""
            ),
            "meta_box_cb" => "",
            "default_term" => "",
            "object_types" => array()
        );
    }


}