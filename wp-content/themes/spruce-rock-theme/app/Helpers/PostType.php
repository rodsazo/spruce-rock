<?php

namespace App\Helpers;

class PostType {

    protected static $is_class_setup;

    protected $_labels_setup = FALSE;
    protected $_post_type;
    protected $_slug;
    protected $_args;


    protected $label = '[Custom Post Label]';
    public $description = '';

    /**
     * @var bool
     * @default FALSE
     */
    public $use_gutenberg = FALSE;

    /**
     * @var bool
     * @default FALSE
     */
    public $has_archive = FALSE;

    /**
     * @var bool
     * @default TRUE
     */
    public $public = TRUE;

    /**
     * @var bool
     * @default TRUE
     */
    public $show_ui = TRUE;

    /**
     * @var bool
     * @default TRUE
     */
    public $show_in_menu = TRUE;

    /**
     * @var bool
     * @default null
     */
    public $menu_position = null;

    /**
     * @var string
     * @default 'post'
     */
    public $capability_type = 'post';

    /**
     * @var string
     * @default FALSE
     */
    public $hierarchical = FALSE;

    /**
     * @var bool
     * @default TRUE
     */
    public $query_var = TRUE;

    /**
     * @var bool
     * @default FALSE
     */
    public $exclude_from_search = FALSE;

    /**
     * @var array
     * @default array('title', 'editor');
     */
    public $supports = array('title');

    /**
     * @var array
     * @default array()
     */

    public $taxonomies = array();

    public $menu_icon;

    /*
     * FOR COLUMNS
     * --------------------------------------------------------------------------------
     */

    protected $_columns = array();

    /*
     * FOR SAVING A POST
     * --------------------------------------------------------------------------------
     */

    protected $_savePublishedActions = array();



    /*
     * FOR LATER REFERENCE
     * --------------------------------------------------------------------------------
     */

    protected static $post_types = array();


    /*
     * LABELS
     * --------------------------------------------------------------------------------
     */

    public $l_singular_name;
    public $l_add_new;
    public $l_add_new_item;
    public $l_edit;
    public $l_edit_item;
    public $l_menu_name;
    public $l_name;
    public $l_not_found;
    public $l_not_found_in_trash;
    public $l_parent;
    public $l_parent_colon;
    public $l_search_items;
    public $l_view;
    public $l_view_item;

    /*
     * Enter Title Here
     * --------------------------------------------------------------------------------
     */
    public $enter_title_here;

    /**
     * @param $post_type
     * @return PicolPostType
     */

    static function getObject( $post_type ) {
        if(isset(self::$post_types[$post_type])){
            return self::$post_types[$post_type];
        }

        return FALSE;
    }

    /**
     * Things that should be run just once
     */

    static function classSetup(){

        if(!isset(self::$is_class_setup)){
            add_filter('enter_title_here', array(static::class, '_enterTitleHereFilter'));

            self::$is_class_setup = TRUE;
        }

    }

    static function _enterTitleHereFilter( $enter_title_here ){

        $screen = get_current_screen();

        if(isset(self::$post_types[ $screen->post_type ])){
            if( self::$post_types[ $screen->post_type ]->enter_title_here ) {
                return self::$post_types[ $screen->post_type ]->enter_title_here;
            }
        }

        return $enter_title_here;

    }


    /*
     * FUNCTIONS
     * --------------------------------------------------------------------------------
     */

    function __construct($post_name, $slug){

        $this->_post_type = $post_name;
        $this->_slug = $slug;

        add_action('init', array( &$this, '_doRegisterPostType'));

        self::$post_types[ $this->_post_type ] = $this;
        self::classSetup();

        return $this;

    }

    final function autoLabels( $singular, $plural ) : self
    {

        $str_set = array(
            'add_new'            => 'Add new',
            'add_new_item'       => 'Add new %s',
            'edit'               => 'Edit',
            'edit_item'          => 'Edit %s:',
            'not_found'          => '%s not found',
            'not_found_in_trash' => '%s not found in trash',
            'parent'             => 'Parent %s',
            'parent_colon'       => 'Parent %s:',
            'search_items'       => 'Search %s',
            'view'               => 'View',
            'view_item'          => 'View %s'
        );


        $label = $plural;

        $this->label = $label;
        $this->_labels_setup = TRUE;

        $this->l_singular_name      = $singular;
        $this->l_add_new            = $str_set['add_new'];
        $this->l_add_new_item       = sprintf($str_set['add_new_item'], $singular);
        $this->l_edit               = $str_set['edit'];
        $this->l_edit_item          = sprintf($str_set['edit_item'], $singular);
        $this->l_menu_name          = $label;
        $this->l_name               = $plural;
        $this->l_not_found          = sprintf($str_set['not_found'], $plural);
        $this->l_not_found_in_trash = sprintf($str_set['not_found_in_trash'], $plural);
        $this->l_parent             = sprintf($str_set['parent'], $singular);
        $this->l_parent_colon       = sprintf($str_set['parent_colon'], $singular);
        $this->l_search_items       = sprintf($str_set['search_items'], $plural);
        $this->l_view               = sprintf($str_set['view'], $singular);
        $this->l_view_item          = sprintf($str_set['view_item'], $singular);

        return $this;
    }

    final  protected function setVar( $var_name, $value ) {
        $this->{$var_name} = $value;
        return $this;
    }

    final function setMenuIcon( $value ) {
        return $this->setVar('menu_icon', $value);
    }
    final function setPublic( $value ) {
        return $this->setVar('public', $value);
    }
    final function setShowUI( $value ) {
        return $this->setVar('show_ui', $value);
    }
    final function setShowInMenu( $value ) {
        return $this->setVar('show_in_menu', $value);
    }
    final function setHierarchical( $value ) {
        return $this->setVar('hierarchical', $value);
    }
    final function setSupports( $value ) {
        return $this->setVar('supports', $value);
    }


    final function _doRegisterPostType(){

        $args = $this->getArgs();

        register_post_type($this->_post_type, $args);

    }

    final function getArgs(){
        $args = array(
            'label' => $this->label,
            'description' => $this->description,
            'has_archive' => $this->has_archive,
            'public' => $this->public,
            'show_ui' => $this->show_ui,
            'show_in_menu' => $this->show_in_menu,
            'menu_position' => $this->menu_position,
            'capability_type' => $this->capability_type,
            'hierarchical' => $this->hierarchical,
            'show_in_rest' => $this->use_gutenberg,

            'rewrite' => array(
                'slug' => $this->_slug
            ),

            'taxonomies' => $this->taxonomies,

            'query_var' => $this->query_var,
            'exclude_from_search' => $this->exclude_from_search,
            'supports' => $this->supports,

            'labels' => array(
                'singular_name'      => $this->l_singular_name,
                'add_new'            => $this->l_add_new_item,
                'add_new_item'       => $this->l_add_new_item,
                'edit'               => $this->l_edit,
                'edit_item'          => $this->l_edit_item,
                'menu_name'          => $this->l_menu_name,
                'name'               => $this->l_name,
                'not_found'          => $this->l_not_found,
                'not_found_in_trash' => $this->l_not_found_in_trash,
                'parent'             => $this->l_parent,
                'parent_colon'       => $this->l_parent_colon,
                'search_items'       => $this->l_search_items,
                'view'               => $this->l_view,
                'view_item'          => $this->l_view_item
            )
        );


        $args['menu_icon'] = $this->menu_icon;

        return $args;
    }

    final function getPostTypeName(){
        return $this->_post_type;
    }

    static function CptUiPrintJson(){
        $json = array();
        /**
         * @var PicolPostType $post_type
         */
        foreach (self::$post_types as $pt_name => $post_type) {
            $pt_args = self::CptUiGetDefaultArgs();
            $pt_args = array_merge( $pt_args, $post_type->getArgs() );
            $pt_args['singular_label'] = $post_type->l_singular_name;
            $slug = $pt_args['rewrite']['slug'];
            $pt_args['rewrite'] = true;
            $pt_args['rewrite_slug'] = $slug;
            $pt_args['name'] = $pt_name;
            $json[ $pt_name ] = $pt_args;
        }

        echo json_encode( $json ); die;
    }

    static function CptUiGetDefaultArgs(){
        return array(
            "name" => "the_name",
            "label" => "Label",
            "singular_label" => "Singular label",
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "",
            "has_archive" => false,
            "has_archive_string" => "",
            "exclude_from_search" => false,
            "capability_type" => "post",
            "hierarchical" => false,
            "rewrite" => true,
            "rewrite_slug" => "",
            "rewrite_withfront" => true,
            "query_var" => true,
            "query_var_slug" => "",
            "menu_position" => "",
            "show_in_menu" => true,
            "show_in_menu_string" => "",
            "menu_icon" => "dashicons-welcome-learn-more",
            "supports" => array(
                "title",
                "editor",
                "thumbnail"
            ),
            "taxonomies" => array(),
            "labels" => array(
                "menu_name" => "",
                "all_items" => "",
                "add_new" => "",
                "add_new_item" => "",
                "edit_item" => "",
                "new_item" => "",
                "view_item" => "",
                "view_items" => "",
                "search_items" => "",
                "not_found" => "",
                "not_found_in_trash" => "",
                "parent_item_colon" => "",
                "featured_image" => "",
                "set_featured_image" => "",
                "remove_featured_image" => "",
                "use_featured_image" => "",
                "archives" => "",
                "insert_into_item" => "",
                "uploaded_to_this_item" => "",
                "filter_items_list" => "",
                "items_list_navigation" => "",
                "items_list" => "",
                "attributes" => "",
                "name_admin_bar" => "",
                "item_published" => "",
                "item_published_privately" => "",
                "item_reverted_to_draft" => "",
                "item_scheduled" => "",
                "item_updated" => ""
            ),
            "custom_supports" => ""
        );
    }

}
