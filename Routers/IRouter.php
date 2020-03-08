<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MVC\Routers;

/**
 *
 * @author gbichev
 */
interface IRouter{
    /**
     * Every object, method, who extend this interface, need to use getURI();
     */
    public function getURI();
}
