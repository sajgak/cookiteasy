<?
class main_model extends \system\core\Model{
    
    public function login(){
        $this->base->library('session')->setData(array('is_admin' => 1));
    }
    
    public function logout(){
        $this->base->library('session')->deleteData('is_admin');
    }
    
    public function is_logged(){
        return ($this->base->library('session')->getData('is_admin') == 1);
    }
    
    public function getCategories(){
        $data = $this->db->find('categories', array(), array('title'))->sort(array('title' => 1));
        $out = array();
        foreach($data as $category){
            $out[] = $category['title'];
        }
        return $out;
    }
    
    public function addCategory($title){
        $this->db->insert('categories', array('title' => $title));
    }
    
    public function getSubCategories($title){
        $data = $this->db->findOne('categories', array('title' => $title), array('sub'));
        if(isset($data['sub']))
            return $data['sub'];
        else return array();
    }
    
    public function addSubCategory($category_title, $title){
        $this->db->update('categories', array('title' => $category_title), array('$addToSet' => array('sub' => $title)));
    }
    
    public function getCountry($id){
        return $this->db->findOne('countries', array('_id' => new MongoId($id)));
    }
    
    public function searchCountry($q, $limit = 5){
        $q = str_replace('-', '\-', $q);
        $this->base->library('sphinx')->limit($limit);
        $countries = $this->base->library('sphinx')->q($q . '*', 'countries');
        $data = array();
        $i = 0;
        if ($countries) {
            foreach ($countries as $country) {
                $temp = $this->getCountry($country);
                $data[$i]['name'] = $temp['name'];
                $data[$i]['flag'] = $temp['flag'];
                $i++;
            }
        }
        return $data;
    }
    
    public function getIngredient($id){
        return $this->db->findOne('ingredients', array('_id' => new MongoId($id)));
    }
    
    public function searchIngredient($q, $limit = 5){
        $q = str_replace('-', '\-', $q);
        $this->base->library('sphinx')->limit($limit);
        $ingredients = $this->base->library('sphinx')->q($q . '*', 'ingredients');
        $data = array();
        if ($ingredients) {
            foreach ($ingredients as $ingredient) {
                $data[] = $this->getIngredient($ingredient);
            }
        }
        return $data;
    }
    
    public function getIngredientsCategories(){
        $data = $this->db->find('ingredients_categories', array(), array('title'))->sort(array('title' => 1));
        $out = array();
        foreach($data as $category){
            $out[] = $category['title'];
        }
        return $out;
    }
    
    public function addIngredient($category_title, $title){
        $category = $this->db->findOne('ingredients_categories', array('title' => $category_title), array());
        if(!$category)
            return false;
        $this->db->insert('ingredients', array('cat' => $category_title, 'title' => $title));
    }
    
    public function getCategoryByName($category, $sub_category = FALSE){
        $where['title'] = $category;
        if($sub_category)
            $where['sub'] = $sub_category;
        return $this->db->findOne('categories',$where);
    }
    
    public function getCountryByName($name){
        return $this->db->findOne('countries', array('name' => $name));
    }
    
    public function getRecipeByName($name){
        return $this->db->findOne('recipes', array('title' => $name));
    }
    
    public function addRecipe($title, $category_name, $sub_category, $time, $output_count, $ingredients, $ingredients_count, $kitchen, $options, $text){
        $category = $this->getCategoryByName($category_name, $sub_category);
        $country = $this->getCountryByName($kitchen);
        $recipe_with_same_title = $this->getRecipeByName($title);
        if(!$category || !$country || $recipe_with_same_title){$this->base->out['error'] = 'Data validation'; return false;}
        $final_ingredients = array();
        $i = 0;
        foreach($this->db->find('ingredients', array('title' => array('$in' => $ingredients))) as $ingredient){
            $final_ingredients[] = array(
                'cat' => $ingredient['cat'], 
                'title' => $ingredient['title'], 
                'count' => $ingredients_count[$i]
            );
            $i++;
        }
        if(count($final_ingredients) != count($ingredients))
            $this->base->out['error'] = 'Not all ingredients were saved';
        $insert_array = array(
            'title' => $title,
            'category' => $category_name,
            'time' => intval($time),
            'output_count' => intval($output_count),
            'ingredients' => $final_ingredients,
            'citchen' => array(
                'cid' => $country['_id'],
                'region' => $country['region']
            ),
            'desc' => $text
        );
        
        if(!empty($options))
            $insert_array['options'] = $options;
        
        if($sub_category)
            $insert_array['sub_category'] = $sub_category;
        
        try{
            $this->db->insert('recipes', $insert_array, array('safe' => true));
        }
        catch(Exception $e){
            $this->base->out['error'] = 'Insertation error';
            return false;
        }
        
        return $this->db->lastObj('recipes');        
    }
    
}