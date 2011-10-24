<?php

class console_model extends \system\core\Model{
    
    public function getCountries(){
        return iterator_to_array($this->db->find('countries'));
    }
    
    public function getIngredients(){
        return iterator_to_array($this->db->find('ingredients'));
    }
    
}

?>
