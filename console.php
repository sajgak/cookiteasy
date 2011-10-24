<?
/* стандартные ограничения нам не подходят. ставим свои */
set_time_limit(0);
/* проверочка. чтобы этот скрипт по неосторожности никто не вызвал из браузера */
if (isset($_SERVER['REMOTE_ADDR'])) die('Все пропало...');

/*  вручную подменяем путь URI на основе параметров командной строки */
unset($argv[0]); /* первый параметр нам ни к чему, это имя скрипта */
$_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'] = '/' . implode('/', $argv) . '/';

/* подключаем framework */

//if(!apc_load_constants('main_constants_console')){
    define('APPATH', 'app/console');
    define('SYSPATH', 'system');

    define('EXT', '.php');
    define('BASEPATH', realpath(''));

    if (realpath(SYSPATH) === FALSE || realpath(APPATH) === FALSE) {
        exit('Provided path are not correct');
    }
    //apc_define_constants('main_constants_console', array('APPATH' => APPATH, 'SYSPATH' => SYSPATH, 'EXT' => EXT, 'BASEPATH' => BASEPATH));
//}

if (realpath(SYSPATH) === FALSE || realpath(APPATH) === FALSE) {
    exit('Provided path is incorrect');
}

function __autoload($class_name) {
    $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
    require $class_name . EXT;
}

set_error_handler(function($errno, $errstr, $errfile, $errline){
            if ($errno == E_STRICT)
                return;
            new \system\exceptions\SystemException($errno, $errstr, $errfile, $errline);
});


$input = new \system\core\Input();

require BASEPATH . DIRECTORY_SEPARATOR . APPATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $input->controller . EXT;
$controller = new $input->controller();

function get_instance(){
    return \system\core\Manager::get_instance();
}

call_user_func_array(array(&$controller, $input->method), $input->args);
 