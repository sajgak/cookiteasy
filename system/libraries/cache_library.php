<?


class cache_library extends Memcached {

    private $db;

    public function __construct() {
        parent::__construct();
        $this->addServer('localhost', 11211);
        $this->db = get_instance()->library('mongo');
    }

    public function set($name, $content, $expiration = 0) {
        $final_name = md5(print_r($name, 1));
        $return = parent::set($final_name, $content, $expiration);
        if ($return){
            $this->db->insert('cache', array('id' => $name));
            parent::set($final_name.'_v', 'yes');
        }
        return $return;
    }

    public function delete($name, $delay = 0) {
        $this->db->remove('cache', array('id' => $name));
        parent::set(md5(print_r($name, 1)).'_v', 'no');
    }

    public function get($name) {
        $final_name = md5(print_r($name, 1));
        $is_valid_and_set = parent::get($final_name.'_v');
        if($is_valid_and_set === 'yes')
            return parent::get($final_name);
        else{
            if($is_valid_and_set === 'no')
                parent::set($final_name.'_v', 'yes');
            return false;
        }
    }
    
    public function smart_delete($params) {
        $criteria = array();
        $size = sizeof($params);
        if($size==0)
             throw new Exception('Size of input parameters is out of expected range');
        if($size==1){
            $criteria['id.' . 0] = $params[0];
        }
        if ($size == 2) {
            for ($i = 0; $i < 2; $i++) {
                $criteria['id.' . $i] = $params[$i];
            }
        }
        if ($size == 3) {
            for ($i = 0; $i < sizeof($params[2]); $i++) {
                $criteria['id.2.' . $i] = $params[2][$i];
            }
        } elseif ($size > 3)
            throw new Exception('Size of input parameters is out of expected range');

        $cursor = $this->db->find('cache', $criteria);

        while ($cursor->hasNext()) {
            $data = $cursor->getNext();
            $this->delete(md5(print_r($data['id'], 1)));
        }
    }

    public function flush($delay = 0) {
        parent::flush($delay);
        $this->db->drop('cache');
    }

}