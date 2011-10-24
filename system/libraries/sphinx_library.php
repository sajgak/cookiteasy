<?php

class sphinx_library extends SphinxClient{

    public function  __construct() {
        parent::__construct();
        $this->SetServer('127.0.0.1', 3312);
        $this->SetMatchMode(SPH_MATCH_EXTENDED2);
        $this->SetSortMode(SPH_SORT_RELEVANCE);
    }
 
    public function limit($limit, $skip = 0){
        parent::SetLimits($skip, $limit);
    }

    public function q($string, $index){
        $data = parent::Query($string, $index);
        if(isset($data['matches'])){
            $out = array();
            foreach($data['matches'] as $match){
                $out[] = $match['attrs']['_id'];
            }
            return $out;
        }
        else return FALSE;
    }

}

?>
