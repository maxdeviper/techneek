<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_cache_limiter(false);
session_start();

function store_state($array=array()){
    foreach ($array as $key=>$value){
        $_SESSION[$key]=$value;
    }
}



