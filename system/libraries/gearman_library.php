<?php

class gearman_library extends GearmanClient{

    public function  __construct() {
        parent::__construct();
        $this->addServer();
    }

}
?>
