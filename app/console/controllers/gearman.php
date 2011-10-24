<?php

class gearman extends \system\core\Manager{
    
    public function __construct() {
        parent::__construct();
        $worker = new GearmanWorker();
        $worker->addServer();
        $worker->addFunction('download_image', array($this, 'download_image'));
        while (true){
          $ret = $worker->work();
          if ($worker->returnCode() != GEARMAN_SUCCESS) {
            echo $worker->returnCode();
            break;
          }
        }
    }
    
    public function download_image($job){
        $params = json_decode($job->workload(), true);
        file_put_contents('/var/www/cookiteasy/static/recipes/images_orig/'.$params['id'].'.jpg', file_get_contents($params['link']));
    }
    
}

?>
