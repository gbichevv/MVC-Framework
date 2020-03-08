<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MVC;

/**
 * Description of FrontController, Singleton
 *
 * @author gbichev
 */
class FrontController {

    private static $_instance = NULL;
    private $namespace = NULL;
    private $controller = NULL;
    private $method = NULL;
    private $router = NULL;

    private function __construct() {
        
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
     * @param router and use IRouter interface -> getURI
     */
    function setRouter(\MVC\Routers\IRouter $router) {
        $this->router = $router;
    }

        
    /**
     * Call routers method 
     */
    public function dispatch() {
        if($this->router == NULL){
            throw new Exception('No valid router found', 500);
        }
        $uri = $this->router->getURI();
        $routes = \MVC\App::getInstance()->getConfig()->routes;
        $rc = NULL;
        if ((is_array($routes)) && (count($routes) > 0)) {
            foreach ($routes as $key => $value) {
                //check key on router is on start (0) position
                if (stripos($uri, $key) === 0 && ($uri == $key || stripos($uri, $key . '/') === 0) && $value['namespace']) {
                    $this->namespace = $value['namespace'];
                    $uri = substr($uri, strlen($key) + 1);
                    //make cache
                    $rc = $value;
                    //good practice to use break when we found what we need from foreach
                    break;
                }
            }
        } else {
            throw new \Exception('Default route missing', 500);
        }
        if ($this->namespace == NULL && $routes['*']['namespace']) {
            $this->namespace = $routes['*']['namespace'];
            $rc = $routes['*'];
        } else if ($this->namespace == NULL && !$routes['*']['namespace']) {
            throw new \Exception('Default route missing', 500);
        }
        $params = explode('/', $uri);
        if ($params[0]) {
            $this->controller = strtolower($params[0]);
            //if we do not have controller and method we do not have params
            if ($params[1]) {
                $this->method = strtolower($params[1]);
            } else {
                $this->method = $this->getDefaultMethod();
            }
        } else {
            $this->controller = $this->getDefaultController();
            $this->method = $this->getDefaultMethod();
        }
        if (is_array($rc) && $rc['controllers']){
           if($rc['controllers'][$this->controller]['methods'][$this->method]){
                $this->method = strtolower($rc['controllers'][$this->controller]['methods'][$this->method]);
            }
            if(isset($rc['controllers'][$this->controller]['to'])){
                $this->controller = strtolower($this->controller = $rc['controllers'][$this->controller]['to']);
            } 
        }
        //TODO fix it
        $namespace_controller =  $this->namespace . '\\'. ucfirst($this->controller);
        $newController = new $namespace_controller;
        //TODO 
        $newController->{$this->method}();
    }

    /**
     * @description This method check default controller is set in config and if is not
     * @return index
     */
    public function getDefaultController() {
        $controller = \MVC\App::getInstance()->getConfig()->app['default_controller'];
        if ($controller) {
            return strtolower($controller);
        }
        return 'index';
    }

    /**
     * @description This method check default method is set in config and if is not
     * @return index
     */
    public function getDefaultMethod() {
        $method = \MVC\App::getInstance()->getConfig()->app['default_method'];
        if ($method) {
            return strtolower($method);
        }
        return 'index';
    }

    /**
     * @return type \MVC\FrontController
     */
    public static function getInstance() {
        if (self::$_instance == NULL) {
            self::$_instance = new \MVC\FrontController();
        }
        return self::$_instance;
    }

}
