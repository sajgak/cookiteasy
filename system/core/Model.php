<?php

namespace system\core;
class Model {
    
    protected $base;
    protected $db;
    
    public function __call($method, $params) {
        $self = get_called_class();
        preg_match('#cache_(.*)#', $method, $method_self);
        if (isset($method_self[1]) && true == method_exists($this, $method_self[1])) {
            $cache_name = array($self, $method_self[1], $params);
            $return = $this->base->library('cache')->get($cache_name);
            if ($return === FALSE) {
                $data = call_user_func_array(array(&$this, $method_self[1]), $params);
                $this->base->library('cache')->set($cache_name, $data);
                return $data;
            } 
            else return $return;
        } 
        else trigger_error('Called method '.$method.' of model '.$self.' not found', E_USER_ERROR);
    }
    
    public function __construct(){
        $this->base = get_instance();
        $this->db = $this->base->library('mongo');
    }
    
}

?>
