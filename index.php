<?php
mail('colorix.studio@gmail.com', 'test', '1');die;
define('START_TIME', microtime());

error_reporting(E_ALL);

if(!apc_load_constants('main_constants_frontend')){
    define('APPATH', 'app/frontend');
    define('SYSPATH', 'system');

    define('EXT', '.php');
    define('BASEPATH', realpath(''));

    if (realpath(SYSPATH) === FALSE || realpath(APPATH) === FALSE) {
        exit('Provided path are not correct');
    }
    apc_define_constants('main_constants_frontend', array('APPATH' => APPATH, 'SYSPATH' => SYSPATH, 'EXT' => EXT, 'BASEPATH' => BASEPATH));
}

function __autoload($class_name) {
    $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
    require $class_name . EXT;
}

set_error_handler(function($errno, $errstr, $errfile, $errline) {
            if ($errno == E_STRICT)
                return true;
            new \system\exceptions\SystemException($errno, $errstr, $errfile, $errline);
        });

$input = new \system\core\Input();
require BASEPATH . DIRECTORY_SEPARATOR . APPATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $input->controller . EXT;
$controller = new $input->controller();

function get_instance() {
    if (\system\core\Manager::get_instance()) {
        return \system\core\Manager::get_instance();
    } else {
        new \system\core\Manager();
        return \system\core\Manager::get_instance();
    }
}

call_user_func_array(array(&$controller, $input->method), $input->args);

/*
 * Full working time (in secs)
 */
echo '<br/><br/>' . (microtime() - START_TIME);

/*
 * Peak memory usage (in Mb)
 */
echo '<br/><br/>' . (memory_get_usage(TRUE) / 1048576);
