<?
namespace system\exceptions;

class SystemException{
    
    private $errno;
    private $errstr;
    private $errfile;
    private $errline;

    private $base;

    private $error_levels = array(
	E_ERROR             =>	'Error',
	E_WARNING           =>	'Warning',
	E_PARSE             =>	'Parsing Error',
	E_NOTICE            =>	'Notice',
	E_CORE_ERROR        =>	'Core Error',
	E_CORE_WARNING      =>	'Core Warning',
	E_COMPILE_ERROR     =>	'Compile Error',
	E_COMPILE_WARNING   =>	'Compile Warning',
	E_USER_ERROR        =>	'User Error',
	E_USER_WARNING      =>	'User Warning',
	E_USER_NOTICE       =>	'User Notice',
	E_STRICT            =>	'Runtime Notice'
    );
    
    private $fatal_errors = array(
        E_ERROR,
	E_PARSE,
	E_CORE_ERROR,
        E_COMPILE_ERROR,
	E_COMPILE_WARNING,
        E_USER_ERROR
    );
    
    public function __construct($errno, $errstr, $errfile, $errline){
        $this->errno = $errno;
        $this->errstr = $errstr;
        $this->errfile = $errfile;
        $this->errline = $errline;
        $this->base = get_instance();
        if(!$this->base)
            $this->base = new \system\core\Manager();
        $this->process_error();
    }
    
    private function process_error(){
        $severity = ( ! isset($this->error_levels[$this->errno])) ? $this->errno : $this->error_levels[$this->errno];
        if (FALSE !== strpos($this->errfile, DIRECTORY_SEPARATOR)){
            $x = explode('/', $this->errfile);
            $filepath = $x[count($x)-3].DIRECTORY_SEPARATOR.$x[count($x)-2].DIRECTORY_SEPARATOR.end($x);
	}
        if (($this->errno & error_reporting()) == $this->errno)
            $this->show_php_error($severity, $this->errstr, $filepath, $this->errline);
        if($this->base->config('main')->log_errors)
            $this->log($severity, $this->errstr, $this->errfile, $this->errline);
        if(in_array($this->errno, $this->fatal_errors)){
            if (($this->errno & error_reporting()) != $this->errno)
                $this->show_fake_error();
            exit(1);
        }
        
    }
    
    private function show_php_error($severity, $message, $filepath, $line){
        echo '<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">
        <h4>A PHP Error was encountered</h4>

        <p>Severity: '.$severity.'</p>
        <p>Message:  '.$message.'</p>
        <p>Filename: '.$filepath.'</p>
        <p>Line Number: '.$line.'</p>

        </div>';
        //debug_print_backtrace();
    }
    
    private function show_fake_error(){
        echo '<div style="width:600px;margin:-10px -300px;border:1px solid #990000;text-align:center;position:absolute;top:50%;left:50%;">
        <h4>В данный момент наш сервис становится лучше</h4>
        <p>Пожалуйста, наберитесь терпения. Скоро все снова заработает.</p>
        </div>';
    }
    
    private function log($severity, $message, $filepath, $line){
        ob_start();
        debug_print_backtrace();
        $debug = ob_get_contents();
	ob_end_clean();
        $this->base->library('mongo')->insert('error_log', array(
            'severity' => $severity, 
            'message' => $message, 
            'file' => $filepath, 
            'line' => $line, 
            'trace' => $debug, 
            'date' => time(), 
            'application' => APPATH
        ));
    }
    
}
