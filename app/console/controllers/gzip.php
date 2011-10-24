<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gzip
 *
 * @author SkarbovskiyG
 */
class gzip extends \system\core\Manager{
    private $finfo;
    
    public function __construct() {
        parent::__construct();
        if (isset($_SERVER['REMOTE_ADDR'])) die('Все пропало...');
        
        $this->finfo = finfo_open(FILEINFO_MIME_TYPE);
    }
    
    public function gzip_static($dir = FALSE){
        
        if(!$dir)
            $dir = '/var/www/cookiteasy/static';
        $dh = opendir($dir);
        while (($file = readdir($dh)) !== false) {
            if(in_array($file, array('.', '..')))
                    continue;
            
            if(is_dir($dir.DIRECTORY_SEPARATOR.$file)){
                $this->gzip_static($dir.DIRECTORY_SEPARATOR.$file);
                continue;
            }
            if(in_array(finfo_file($this->finfo, $dir.DIRECTORY_SEPARATOR.$file), array('text/plain', 'text/html', 'text/x-c'))){
                exec('gzip -9c \''.$dir.DIRECTORY_SEPARATOR.$file.'\' > \''.$dir.DIRECTORY_SEPARATOR.$file.'.gz\'');
            }
        }
        closedir($dh);
    }
    
}

?>
