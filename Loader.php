<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MVC;

/**
 * Description of Loader
 *
 * @author gbichev
 */
class Loader {

    private static $namespace = array();

    /**
     * Make the class impossible to extend or call from view
     */
    private function __construct() {
        
    }

    /**
     * Register autoload
     */
    public function registerAutoload() {
        spl_autoload_register(array("\MVC\Loader", 'autoload'));
    }

    /**
     * Autoload
     * @param type $class
     */
    public static function autoload($class) {
        self::loadClass($class);
    }
    /**
     * Load class
     * Call namespaces, chack for valid path, change path and include file
     * @param string $class
     */
    
    public function loadClass($class) {
        //check classes
        foreach (self::$namespace as $key => $validation) {
            //check namespace only if is on start position '===' check is only 0, without boolen
            if (strpos($class, $key) === 0) {
                //change path and validation
                $file = realpath(substr_replace(str_replace('\\', DIRECTORY_SEPARATOR, $class), $validation, 0, strlen($key)) . '.php');
                if($file && is_readable($file)){
                    include $file;
                } else {
                    throw new \Exception('File cannot be included:' . $file);
                }
                break;
            }
        }
    }

    /**
     * Register namespaces and check is they are valid  
     * @param string $namespace
     * @param string $path Full path
     */
    public function registerNamespace($namespace, $path) {
        $namespace = trim($namespace);
        if (strlen($namespace) > 0) {
            if (!$path) {
                throw new \Exception('Invalid path');
            }
            $_path = realpath($path);
            if ($_path && is_dir($_path) && is_readable($_path)) {
                self::$namespace[$namespace.'\\'] = $_path . DIRECTORY_SEPARATOR;
            } else {
                throw new \Exception('Namespace directory read error:' . $path);
            }
        } else {
            throw new \Exception('Invalid namespace:' . $namespace);
        }
    }
    
    /**
     * Get all namespaces
     * @param type $array
     * @throws Exception
     */
    public function registerNamespaces($array){
        if(is_array($array)){
            foreach ($array as $key => $value) {
                self::registerNamespace($key, $value);
            }
        } else {
            throw new Exception('Invalid namespaces');
        }
    }

        /**
     * Get namespace
     */
    public function getNamespace(){
        return self::$namespace;
    }
    /**
     * Remove namespace
     */
    public function removeNamespace($namespace){
        unset(self::$namespace[$namespace]);
    }
    /**
     * Clear Namespace
     */
    public function clearNamespace(){
        self::$namespace = array();
    }
    
}
