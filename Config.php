<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MVC;

/**
 * Description of Config - Singleton
 *
 * @author gbichev
 */
class Config {

    private static $_instance = NULL;
    private $configFolder = NULL;
    private $configArray = array();

    private function __construct() {
        
    }
    
    /*
     * Get Config Folder
     * @return config folder directory, automaticalLy 
     */
    public function getConfigFolder(){
        return $this->configFolder;
    }


    /**
     * Set config folder
     * @param type $configFolder
     * @throws \Exception
     */
    public function setConfigFolder($configFolder) {
        if (!$configFolder) {
            throw new \Exception("Empty config folder path:");
        }
        $_configFolder = realpath($configFolder);
        if ($_configFolder != FALSE && is_dir($_configFolder) && is_readable($_configFolder)) {
            //clear old data
            $this->configArray = array();
            $this->configFolder = $_configFolder . DIRECTORY_SEPARATOR;
            //add namespaces 
            $namespace = $this->app['namespaces'];
            if(is_array($namespace)){
                \MVC\Loader::registerNamespaces($namespace);
            }
        } else {
            throw new \Exception("Config directory read error: " . $configFolder);
        }
    }
    /**
     * Include Config File
     * @param type $path
     * @throws \Exception
     */
    public function includeConfigFile($path){
        if(!$path){
            throw new \Exception;
        }
        $file = realpath($path);
        if($file != FALSE && is_file($file) && is_readable($file)){
            //file name get 0's element
            $basename = explode('.php', basename($file))[0];
            $this->configArray[$basename] = include $file;
        } else {
            throw new \Exception('Config file read error: '  . $path);
        }
    }


    /**
     * Call every time property who is not define, magical function
     * @param type $name
     */

    public function __get($name){
        //check is exist in property and add in includeConfigFile 
        if(!$this->configArray[$name]){
            
        $this->includeConfigFile($this->configFolder . $name . '.php');
        }
        //check is key exist
        if(array_key_exists($name, $this->configArray)){
            return $this->configArray[$name];
        }
        //return NULL, because in config array can implement FALSE/TRUE/0 
        return NULL;
    }
    

    /**
     * Get Instance
     * @return \MVC\Config()
     */
    public static function getInstance() {
        if (self::$_instance == NULL) {
            self::$_instance = new \MVC\Config();
        }
        return self::$_instance;
    }

}
