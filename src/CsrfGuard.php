<?php

namespace PP\Common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CsrfGuard
 *
 * @author user
 */
class CsrfGuard
{
    public static function getGuard()
    {
        $name = mt_rand(0, mt_getrandmax());
        $value = bin2hex(openssl_random_pseudo_bytes(16));
        
        $_SESSION['_guardName'] = $name;
        $_SESSION['_guardValue'] = $value;
        
        return array($name,$value);
    }
    
    
    public static function isValid()
    {
        if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== $_SERVER['HTTP_HOST']) {
            return FALSE;
        }
        
        if ( !isset($_SESSION['_guardName']) ) {
            return FALSE;
        }
        
        if (!isset($_SESSION['_guardValue']) ){
            return FALSE;
        }
        
        if ( !isset($_POST[$_SESSION['_guardName']]) ){
            return FALSE;
        }
        
        if ($_POST[$_SESSION['_guardName']] !== $_SESSION['_guardValue']) {
            return FALSE;
        }
        
        return true;
    }
}
