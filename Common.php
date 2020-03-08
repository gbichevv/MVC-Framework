<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MVC;

/**
 * Description of Common
 *
 * @author gbichev
 */
class Common {

    /**
     * Normalize Input data array 
     * @param type $data
     * @param type $types
     * @return type
     */
    public static function normalize($data, $types) {
        $types = explode('|', $types);
        if (is_array($types)) {
            foreach ($types as $value) {
                switch ($value) {
                    case 'int':
                        $data = (int)$data;
                    case 'float':
                        $data = (float)$data;
                    case 'double':
                        $data = (double)$data;
                    case 'bool';
                        $data = (bool)$data;
                    case 'string'; 
                        $data = (string)$data;
                    case 'trim':
                        $data = trim($data);
                        //TODO
                    case 'xss':
//                        $data = self::xss_clean($data);
                }
            }
        }
        return $data;
    }
    /**
     * Code is taken drom @link https://gist.github.com/1098477 description
     * @param type $data
     */
//    public static function xss_clean($data){
//        
//    }
}
