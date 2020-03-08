<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MVC;

/**
 * Description of InputData Singalton
 *
 * @author gbichev
 */
class InputData {

    private static $instance = NULL;
    private $get = array();
    private $post = array();
    private $cookies = array();

    private function __construct() {
        $this->cookies = $_COOKIE;
    }

    /**
     * Setter Get
     * @param array $get
     */
    function setGet($get_array) {
        if (is_array($get_array)) {
            $this->get = $get_array;
        }
    }

    /**
     * Setter Post
     * @param array $post
     */
    function setPost($post_array) {
        if (is_array($post_array)) {
            $this->post = $post_array;
        }
    }

    /**
     * Check get exist
     * @param type $id
     * @return type
     */
    public function hasGet($id) {
        return array_key_exists($id, $this->get);
    }

    /**
     * Check post exist
     * @param type $name
     * @return type
     */
    public function hasPost($name) {
        return array_key_exists($name, $this->post);
    }
    /**
     * Check cookies
     * @param type $name
     * @return type
     */
    public function hasCookies($name) {
        return array_key_exists($name, $this->cookies);
    }
    /**
     * Get, position, normalize and if is null get default position 
     * @param type $id
     * @param type $normalize
     * @param type $default
     */
    public function get($id, $normalize = NULL, $default = NULL){
     if($this->hasGet($id)){
         if($normalize != NULL){
             return \MVC\Common::normalize($this->get[$id], $normalize);
         }
         return $this->get[$id];
     }   
     return $default;
     //how to normilize
//     $this->get(0, 'trim|string|xss', 'new');
    }

     /**
     * Post, position, normalize and if is null get default position 
     * @param type $name
     * @param type $normalize
     * @param type $default
     */
    public function post($name, $normalize = NULL, $default = NULL){
     if($this->hasPost($name)){
         if($normalize != NULL){
             return \MVC\Common::normalize($this->post[$name], $normalize);
         }
         return $this->post[$name];
     }   
     return $default;
    }
    
    /**
     * Cookies, position, normalize and if is null get default position 
     * @param type $name
     * @param type $normalize
     * @param type $default
     */
    public function cookies($name, $normalize = NULL, $default = NULL){
     if($this->hasCookiesS($name)){
         if($normalize != NULL){
             return \MVC\Common::normalize($this->cookies[$name], $normalize);
         }
         return $this->cookies[$name];
     }   
     return $default;
    }
    
    /**
     * Instance class 
     * @return \MVC\InputData
     */
    public static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new \MVC\InputData();
        }
        return self::$instance;
    }

}
