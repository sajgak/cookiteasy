<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of errors_model
 *
 * @author Usver
 */
class errors_model extends \system\core\Model{
    
    public function getErrors(){
        $cursor = $this->db->find('error_log')->sort(array('_id' => -1))->limit(10);
        $out = array();
        foreach($cursor as $error){
            $out[] = $error;
        }
        return $out;
    }
    
}

?>
