<?php

namespace system\core;

class Helpers_manager {
    private $helper_files = array();
    private $current_file = false;
    
    public function __call($func, $params){
        if (true == function_exists($func)) {
                  return call_user_func_array($func, $params);
        }
        else{
            trigger_error($current_file.' function "'.$func.'" not found', E_USER_ERROR);
        }
    }
    
    public function call($file){
        $this->current_file = $file;
        if(!in_array($file, $this->helper_files)){
            require BASEPATH . DIRECTORY_SEPARATOR . SYSPATH . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . $file . EXT;
            $this->helper_files[] = $file;
        }
        return $this;
    }
    
}

?>
