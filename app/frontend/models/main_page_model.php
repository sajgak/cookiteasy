<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of main_page_model
 *
 * @author sajgak
 */
class main_page_model extends \system\core\Model{

    public function getLastRecipes($limit){
        $out = array();
        foreach($this->db->find('recipes')->limit($limit) as $reciple)
            $out[] = $reciple;
        return $out;
    }    
    
}

?>
