<?php

/*
 * MVC Framework 
 * @version 1.0.0
 * @author gbichev <gbichevv@gmail.com>
 */


/**
 * Application clas start MVC Framework - Singleton
 * @author gbichev <gbichevv@gmail.com>
 */

namespace MVC;

include_once '../MVC/Loader.php';

class App {

    private static $_instance = NULL;
    private $config = NULL;
    private $router = NULL;
    private $frontController = NULL;
    private $db_connections = array();

    private function __construct() {
        \MVC\Loader::registerNamespace('MVC', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        \MVC\Loader::registerAutoload();
        //get instance on config class
        $this->config = \MVC\Config::getInstance();
        if ($this->config->getConfigFolder() == NULL) {
            $this->setConfigFolder('config');
        }
    }

    /**
     * Setter Config Folder
     * @param type $path to config 
     */
    function setConfigFolder($path) {
        $this->config->setConfigFolder($path);
    }

    /**
     * Getter config folder
     * @return type
     */
    function getConfigFolder() {
        return $this->configFolder;
    }

    /**
     * Getter router
     * @return type
     */
    function getRouter() {
        return $this->router;
    }

    /*
     * Setter router
     * @param router 
     */

    function setRouter($router) {
        $this->router = $router;
    }

    /**
     * 
     * @return \MVC\Config
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Run Application
     */
    public function run() {
        //if config folder is not set, use defaut
        if ($this->config->getConfigFolder() == NULL) {
            $this->setConfigFolder('config');
        }
        //run frontcontroller 
        $this->frontController = \MVC\FrontController::getInstance();
        if ($this->router instanceof \MVC\Routers\IRouter) {
            $this->frontController->setRouter($this->router);
        } elseif ($this->router == 'JsonRPCRouter') {
            //TODO
            $this->frontController->setRouter(new \MVC\Routers\DefaultRouter());
        } elseif ($this->router == 'CLIRouter') {
            //TODO
            $this->frontController->setRouter(new \MVC\Routers\DefaultRouter());
        } else {
            $this->frontController->setRouter(new \MVC\Routers\DefaultRouter());
        }
        $this->frontController->dispatch();
    }

    /**
     * DB connection / Better way
     * @param type $connection = default
     */
    public function getConnection($connection = 'default') {
        if (!$connection) {
            throw new Exception('No connection identifler providet', 500);
        }
        if ($this->db_connections[$connection]) {
            return $this->db_connections[$connection];
        }
        $config = $this->getConfig()->database;
        if (!$config[$connection]) {
            throw new Exception('No valid connection identificator is provided', 500);
        }
        $dbh = new \PDO($config[$connection]['connection_uri'], $config[$connection]['username'], $config[$connection]['password'], $config[$connection]['pdo_options']);
        $this->db_connections[$connection] = $dbh;
        return $dbh;
    }

    /**
     * Instance class 
     * @return \MVC\App 
     */
    public static function getInstance() {
        if (self::$_instance == NULL) {
            self::$_instance = new \MVC\App();
        }
        return self::$_instance;
    }

}
