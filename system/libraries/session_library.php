<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of session_library
 *
 * @author Usver
 */
class session_library {
    
    public function getData($id){
        if(isset($_SESSION[$id]))
            return $_SESSION[$id];
        else return false;
    }
    
    public function setData($array){
        foreach($array as $key => $value){
            $_SESSION[$key] = $value;
        }
    }
    
    public function deleteData($id){
        unset($_SESSION[$id]);
    }
    
}

?>
