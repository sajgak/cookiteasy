<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mongo
 *
 * @author Usver
 */
class mongo_library extends Mongo {

    private $db;
    
    public function __construct() {
        parent::__construct('mongodb://localhost:27017');
        $this->db = $this->selectDB('test');
    }

    public function find($collection, $parameters = array(), $requsted_fields = array()) {
        $cursor = $this->db->$collection->find($parameters, $requsted_fields);
        if(rand(0, 1000) == 1000)
                $this->insert('explains', $cursor->explain());
        return $cursor;
    }

    public function findOne($collection, $parameters = array(), $requsted_fields = array()) {
        return $this->db->$collection->findOne($parameters, $requsted_fields);
    }

    public function insert($collection, $array, $options = array()) {
        return $this->db->$collection->insert($array, $options);
    }

    public function remove($collection, $criteria = array(), $options = array()) {
        return $this->db->$collection->remove($criteria, $options);
    }

    public function update($collection, $criteria, $newobj, $options = array()) {
        return $this->db->$collection->update($criteria, $newobj, $options);
    }

    public function drop($collection){
        return $this->db->$collection->drop();
    }

    public function group ($collection, $keys, $initial , $reduce, $options = array()){
        return $this->db->$collection->group($keys, $initial, $reduce, $options);
    }

    public function command($cmd){
        return $this->db->command($cmd);
    }
    
    public function listCollections(){
        return $this->db->listCollections();
    }
    
    public function lastObj($collection){
        return $this->find($collection)->sort(array('_id' => -1))->getNext();
    }


}