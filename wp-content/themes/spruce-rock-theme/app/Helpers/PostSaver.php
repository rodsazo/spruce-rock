<?php
namespace App\Helpers;

defined('ABSPATH') or die("No script kiddies please!");

class PostSaver{

    protected static $savers;
    protected static $prepared;

    protected $savePublished = array();
    protected $savePost = array();
    protected $beforeDelete = array();
    protected $afterDelete = array();

    protected $post_types;

    protected static final function prepare(){
        if(isset(self::$prepared)){
            return;
        }

        add_action('save_post', array(static::class,'_doSavePost'), 10, 1);
        add_action('before_delete_post', array(static::class,'_beforeDelete'), 10, 1);
        add_action('deleted_post', array(static::class,'_afterDelete'), 10, 1);

        self::$prepared = true;
    }

    protected static final function addSaver( $saver ) {
        foreach( $saver->post_types as $post_type ) {
            self::$savers[ $post_type ][] = $saver;
        }
    }

    function __construct( $post_types ){
        $this->post_types = (array) $post_types;

        self::prepare();

        self::addSaver( $this );

    }


    final function onSavePost($callable) {
        $this->savePost[] = $callable;
    }

    final function onSavePublished($callable){
        $this->savePublished[] = $callable;
    }

    final function beforeDelete($callable){
        $this->beforeDelete[] = $callable;
    }

    final function afterDelete($callable){
        $this->afterDelete[] = $callable;
    }


    static final function _doSavePost($post_id){

        $post_type = get_post_type( $post_id );

        $registered_post_types = array_keys( self::$savers );

        $should_save = !wp_is_post_autosave( $post_id )
            && !wp_is_post_revision( $post_id )
            && in_array( $post_type, $registered_post_types );

        if( $should_save ) {

            $is_published = get_post_status( $post_id ) == 'publish';

            if(!empty( self::$savers[ $post_type ])) {
                foreach (self::$savers[$post_type] as $saver) {
                    foreach ($saver->savePost as $callable) {
                        call_user_func($callable, $post_id);
                    }

                    if ($is_published) {
                        foreach ($saver->savePublished as $callable) {
                            call_user_func($callable, $post_id);
                        }
                    }
                }
            }
        }
    }


    static final function _beforeDelete($post_id){

        $post_type = get_post_type( $post_id );


        if(!empty( self::$savers[ $post_type ])){
            foreach( self::$savers[ $post_type ] as $saver ){
                foreach($saver->beforeDelete as $callable){
                    call_user_func( $callable, $post_id );
                }
            }
        }
    }

    static final function _afterDelete( $post_id ) {
        $post_type = get_post_type( $post_id );

        if(!empty( self::$savers[ $post_type ])){
            foreach( self::$savers[ $post_type ] as $saver ){
                foreach($saver->beforeDelete as $callable){
                    call_user_func( $callable, $post_id );
                }
            }
        }
    }


}