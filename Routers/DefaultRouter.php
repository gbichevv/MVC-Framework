<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DefaultRouter
 *
 * @author gbichev
 */

namespace MVC\Routers;

class DefaultRouter implements \MVC\Routers\IRouter{
    
    /**
     * Parse router method, need to get from url controller, method, param
     */
    public function getURI() {
        //remove from server php self -> script name
        return substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME']) + 1);
    }
}
