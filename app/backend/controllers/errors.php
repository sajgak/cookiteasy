<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of errors
 *
 * @author Usver
 */
class errors extends \system\core\Manager{
    
    public function __construct() {
        parent::__construct();
        if(!$this->model('main')->is_logged()){
            $this->helper('headers')->redirect('main/login');
        }
    }
    
    public function index(){
        $this->out['errors'] = $this->model('errors')->getErrors();
        $this->view('errors');
    }
    
}

?>
