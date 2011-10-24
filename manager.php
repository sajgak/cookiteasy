<?php
//xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
define('START_TIME', microtime());
error_reporting(E_ALL);


if(!apc_load_constants('main_constants_backend')){
    define('APPATH', 'app/backend');
    define('SYSPATH', 'system');

    define('EXT', '.php');
    define('BASEPATH', realpath(''));

    if (realpath(SYSPATH) === FALSE || realpath(APPATH) === FALSE) {
        exit('Provided path are not correct');
    }
    apc_define_constants('main_constants_backend', array('APPATH' => APPATH, 'SYSPATH' => SYSPATH, 'EXT' => EXT, 'BASEPATH' => BASEPATH));
}

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

/*
 * Full working time (in secs)
 */
//echo '<br/><br/>' . (microtime() - START_TIME);

/*
 * Peak memory usage (in Mb)
 */
//echo '<br/><br/>' . (memory_get_usage(TRUE) / 1048576);

/*$xhprof_data = xhprof_disable();




$XHPROF_ROOT = realpath(dirname(__FILE__) .'/../..');
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

// save raw data for this profiler run using default
// implementation of iXHProfRuns.
$xhprof_runs = new XHProfRuns_Default();

// save the run under a namespace "xhprof_foo"
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo1");

echo '<a href="http://tripcatcher.ru/xhprof_html/index.php?run='.$run_id.'&source=xhprof_foo1" target="_blank">профилирование</a>';*/
