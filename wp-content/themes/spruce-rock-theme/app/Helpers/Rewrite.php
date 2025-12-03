<?php

namespace App\Helpers;
defined('ABSPATH') or die("No script kiddies please!");

class Rewrite {

    protected $query_vars;
    protected $rules;
    protected $templates;

    static protected $instance;

    static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function __construct(){
        add_filter( 'rewrite_rules_array', array(&$this, '_rewrite_rules_array') );
        add_filter( 'query_vars', array(&$this, '_query_vars') );
        add_filter( 'template_include', array(&$this, '_template_include') );

        $this->query_vars = array();
        $this->rules = array();
        $this->templates = array();
    }

    /*
     * Hooked functions
     */

    /**
     * Agrega las nuevas reglas al array de reglas de WP
     * @param $rules
     * @return array
     */

    function _rewrite_rules_array( $rules ){

        $rules = $this->rules + $rules;

        return $rules;

    }

    /**
     * Registra nuestras nuevas query_vars
     * @param $vars
     * @return mixed
     */

    function _query_vars( $vars ){
        foreach($this->query_vars as $query_var ) {
            array_push( $vars, $query_var );
        }
        return $vars;
    }

    /**
     * Verifica si las reglas son iguales a lo que WP entiende actualmente.
     * Si hay alguna diferencia, reescribe las reglas de rewrite con las
     * nuestras.
     */

    function _wp_loaded(){

        global $wp_rewrite;

        $new_rules = $this->rules;
        $rules     = get_option('rewrite_rules');

        foreach ( $new_rules as $rule => $destination ) {
            if ( !isset($rules[ $rule ]) || $rules[ $rule ] != $destination ){
                $wp_rewrite->flush_rules();
            }
        }

    }

    function _template_include( $template ){

        foreach( $this->templates as  $data ) {

            $template_candidate = $data['template'];
            $condition_arr = $data['conditions'];
            $callback = (array) $data['callback'];

            // Callback podría ya ser un array calleable

            if (is_array($callback) && is_callable($callback)){
                $callback = array( $callback );
            }

            if( $this->_query_var_match ($condition_arr )) {


                foreach($callback as $one_callback){
                    if( is_callable( $one_callback )){
                        call_user_func($one_callback);
                    }
                }

                if(!empty($template_candidate)) {

                    if (is_file($template_candidate)) {
                        $template = $template_candidate;
                    } else {
                        $template = locate_template($template_candidate);
                    }

                }

                break;
            }

        }

        return $template;
    }

    /*
     * Protected helpers
     */

    protected function _add_vars( $map ) {

        foreach( array_keys( $map ) as $query_var) {
            if(!in_array( $query_var, $this->query_vars )){
                $this->query_vars[] = $query_var;
            }
        }

    }

    protected function _map_to_destination( $map ) {

        $query_string = array();

        foreach( $map as $qv => $value ) {
            $value = $this->_filter_destination( $value );

            $query_string[] = $qv . '=' . $value;
        }

        return 'index.php?' . implode('&', $query_string);
    }

    protected function _query_var_match($condition_arr){
        $matches_all = TRUE;

        foreach( $condition_arr as $query_var => $value ) {

            $var_value = get_query_var( $query_var );

            if( $value == 'isset' || strstr( $value, '$' )) {
                $test = !empty($var_value);
            } else {
                $test = get_query_var($query_var) == $value;

            }

            $matches_all = $test && $matches_all;
        }

        return $matches_all;
    }

    protected function _filter_rule( $rule ) {
        $rule = str_replace('%','([^/]+)', $rule);

        return $rule . '/?$';
    }

    protected function _filter_destination( $destination ) {
        if(strstr( $destination, '$')) {
            $destination = '$matches[' . substr($destination, 0, strlen($destination) -1) . ']';
        }

        return $destination;
    }

    /*
     * Public functions
     */

    function add_rule( $rule , $map, $template = '', $callback = '' ) {

        if(!is_array($map)){
            throw new Exception('Rewrite::add_rule espera un array asociativo como segundo parámetro');
        }

        $this->_add_vars( $map );

        $rule = $this->_filter_rule( $rule );

        $destination = $this->_map_to_destination( $map );
        $this->rules[ $rule ] = $destination;

        if(!empty($template) || !empty($callback)){
            $this->add_template( $template, $map, $callback );
        }

        return $this;
    }

    function add_template ( $tempate_file, $query_var_conditions, $callback = null ){
        $this->templates[] = array(
            'template' => $tempate_file,
            'conditions' => $query_var_conditions,
            'callback' => $callback
        );

        return $this;
    }


}
