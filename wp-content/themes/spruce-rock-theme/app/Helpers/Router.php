<?php

namespace App\Helpers;
class Router{

    const QRY_PARAM_PREFIX = 'picol-qry-';

    static $title_maps;
    static $is_setup;

    /**
     * @var PicolRouteItem[]
     */
    protected static $routes = [];

    const CUSTOM_URL = 'custom-url';

    static function setup(){
        if(empty(self::$is_setup)){
            self::$is_setup = true;
            add_filter('wp_title', array(__CLASS__, '_filterWpTitle'), 10, 2);
        }
    }

    /**
     * @param $slug
     * @param string $template
     * @param string $title
     * @param callable|callable[] $callback
     * @param array $map
     * @throws Exception
     */
    static function add_route( $slug, $template = '', $title = '', $callback = array(), $map = array() ){

        self::setup();

        // Añadir el URL a las variables
        $vars_map = array_merge(array(
            self::CUSTOM_URL => $slug
        ), $map);

        // Añadir las variables por defecto
        $vars_map = array_merge( static::getDefaultVarsMap(), $vars_map );

        $rew = Rewrite::getInstance();
        $rew->add_rule($slug, $vars_map, 'views/' . $template, $callback);

        self::registerTitle( $vars_map, $title );

    }

    protected static function registerTitle( $map, $title ) {
        self::$title_maps[] = array(
            'map' => $map,
            'title' => $title
        );
    }

    static function _filterWpTitle( $title, $sep ) {

        foreach( self::$title_maps as $one_map ) {
            $var_map = $one_map['map'];
            $one_title = $one_map['title'];

            $should_apply = true;

            foreach($var_map as $key => $value ) {
                $qv_value     = get_query_var($key);
                if( strstr($value, '$') ) {
                    $qv_validation =  !empty($qv_value);
                } else {
                    $qv_validation = ($qv_value == $value);
                }
                $should_apply = $should_apply && $qv_validation;
            }

            if($should_apply) {
                return $one_title . ' ' . $sep . ' ';
            }
        }

        return $title;
    }
    static function getDefaultVarsMap(){
        return array();
    }

    static function isUrl( $url ){
        return get_query_var( self::CUSTOM_URL ) == $url;
    }


}