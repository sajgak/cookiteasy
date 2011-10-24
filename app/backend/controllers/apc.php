<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of apc
 *
 * @author sajgak
 */
class apc extends \system\core\Manager{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function clear(){
        apc_clear_cache('user');
    }
    
}

?>
