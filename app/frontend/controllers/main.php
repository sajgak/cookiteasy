<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of main
 *
 * @author SkarbovskiyG
 */
class main extends \system\core\Manager {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
    trigger_error('test', E_USER_ERROR);
        $this->out['recipes'] = $this->model('main_page')->getLastRecipes(10);
        $this->view('main_page');
    }
    
}

?>
