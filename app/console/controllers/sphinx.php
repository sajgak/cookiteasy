<?

class sphinx extends \system\core\Manager {
    
    public function __construct(){
        parent::__construct();
        if (isset($_SERVER['REMOTE_ADDR'])) die('Все пропало...');
    }
    
    public function countries_indexer(){
        $this->out['countries'] = $this->model('console')->cache_getCountries();
        $this->view('console/countries_indexer');
    }
    
    public function ingredients_indexer(){
        $this->out['ingredients'] = $this->model('console')->cache_getIngredients();
        $this->view('console/ingredients_indexer');
    }
    
}
