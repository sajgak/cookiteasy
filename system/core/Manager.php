<?
namespace system\core;
class Manager {
    private static $models = array();
    private static $libraries = array();
    private static $controllers = array();
    private static $helpers_manager = FALSE;
    private static $config = array();
    private static $instance;
    private static $output_buffer;
    public $out = array();
    
    public function __construct() {
        self::$instance = & $this;
        self::$helpers_manager = new \system\core\Helpers_manager();
        self::get_config('main');
    }
    
    public static function config($file) {
        if (isset(self::$config[$file])) return self::$config[$file];
        else return self::get_config($file);
    }
    
    private static function get_config($file) {
        if(self::$config[$file] = apc_fetch(APPATH.$file)){
            return self::$config[$file];
            
        }
        else{
            self::$config[$file] = (object)require (BASEPATH . DIRECTORY_SEPARATOR . APPATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . $file . EXT);
            apc_add(APPATH.$file, self::$config[$file]);
            return self::$config[$file];
        }
        
    }
    
    public static function get_instance() {
        return self::$instance;
    }
    
    public static function model($name) {
        return self::call(__FUNCTION__, APPATH, 'models', $name);
    }
    
    public static function library($name) {
        return self::call(__FUNCTION__, SYSPATH, 'libraries', $name);
    }
    
    /*public static function module($name) {
        return self::call(__FUNCTION__, APPATH, 'modules', $name);
    }*/
    
    public static function helper($name) {
        return self::$helpers_manager->call($name);
    }
    
    private static function call($sufix, $start_folder, $type, $name) {
        $arg = & self::$$type;
        if (isset($arg[$name])) return $arg[$name];
        else return self::get($sufix, $start_folder, $type, $name);
    }
    
    private static function get($sufix, $start_folder, $type, $name) {
        $arg = & self::$$type;
        require BASEPATH . DIRECTORY_SEPARATOR . $start_folder . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $name . '_' . $sufix . EXT;
        $real_name = explode(DIRECTORY_SEPARATOR, $name);
        $real_name = $real_name[sizeof($real_name) - 1] . '_' . $sufix;
        $arg[$name] = new $real_name();
        return $arg[$name];
    }
    
    public function view($_FW_name = FALSE, $_FW_save = FALSE) {
        if($_FW_name)
            extract($this->out);
        ob_start();
        if($_FW_name)
            require BASEPATH . DIRECTORY_SEPARATOR . APPATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $_FW_name . EXT;
        else{
            foreach($this->out as $temp)
                echo $temp;
        }
        $buffer = ob_get_contents();
        if(ob_get_level() > 2 && !$_FW_save){
            ob_end_flush();
            return;
        }
        ob_end_clean();
        if ($_FW_save) return $buffer;
        self::$output_buffer .= $buffer;
    }
    
    protected function display() {
        echo self::$output_buffer;
    }
    
    public function __destruct(){
        $this->display();
    }
}