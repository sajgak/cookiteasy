<?
namespace system\core;

class Input {

    public $controller = 'main';
    public $method = 'index';
    public $args = array();
    private $uri;

    public function __construct() {
        if(isset($_COOKIE['PHPSESSID']))
            session_id($_COOKIE['PHPSESSID']);
        session_start();
        if (isset($_SERVER['PATH_INFO'])) {
            $data = explode('/', $_SERVER['PATH_INFO']);
            if ($data[1]) {
                $this->uri = $_SERVER['PATH_INFO'];
                $this->parse_routing();
                $this->set_routing();
            }
        }
    }

    private function parse_routing($routes) {
        $routes = require BASEPATH . DIRECTORY_SEPARATOR . APPATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'routes' . EXT;
        foreach ($routes as $route => $forward) {
            if (preg_match('#' . $route . '#isU', $this->uri)) {
                $this->uri = preg_replace('#' . $route . '#isU', $forward, $this->uri);
                break;
            }
        }
    }

    private function set_routing() {
        $data = explode('/', $this->uri);
        if (!empty($data[1])) {
            $this->controller = $this->filter_uri(str_replace('.', '/', $data[1]));
        }
        if (!empty($data[2]))
            $this->method = $this->filter_uri($data[2]);
        if (sizeof($data > 3)) {
            for ($i = 3; $i < sizeof($data); $i++) {
                $this->args[] = $this->filter_uri($data[$i]);
            }
        }
    }

    private function filter_uri($str) {
        if (!empty($str) && !is_numeric($str)) {
            if (!preg_match("#^[a-z A-Z 0-9 _ - /]+$#isU", $str) || in_array($str, array('__construct', '__destruct', 'config', 'get_instance', 'model', 'library', 'module', 'helper', 'view'))) {
                trigger_error('Submitted URI has disallowed chars', E_USER_ERROR);
            }
        }
        return $str;
    }

}