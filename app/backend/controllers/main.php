<?

class main extends \system\core\Manager {
    
    public function __construct() {
        parent::__construct();
        if(!$this->model('main')->is_logged()){
            $this->login();
            exit(0);
        }
    }
    
    public function index(){
        $data = array();
        if(isset($_POST['title'], $_POST['category'], $_POST['sub_category'], $_POST['time'], $_POST['output_count'], $_POST['ingredients'], $_POST['ingredients_count'], $_POST['kitchen'], $_POST['text'])){
            $options = array();
            if(isset($_POST['vegetarian']))
                $options[] = 'vegetarian';

            if(isset($_POST['child']))
                $options[] = 'child';

            if(isset($_POST['diet']))
                $options[] = 'diet';

            $new_recipe = $this->model('main')->addRecipe(
                    htmlspecialchars(trim($_POST['title']), ENT_NOQUOTES),
                    htmlspecialchars(trim($_POST['category']), ENT_NOQUOTES),
                    htmlspecialchars(trim($_POST['sub_category']), ENT_NOQUOTES),
                    htmlspecialchars(trim($_POST['time']), ENT_NOQUOTES),
                    htmlspecialchars(trim($_POST['output_count']), ENT_NOQUOTES),
                    $_POST['ingredients'],
                    $_POST['ingredients_count'],
                    htmlspecialchars(trim($_POST['kitchen']), ENT_NOQUOTES),
                    $options,
                    trim($_POST['text'])
            ); 
            if($new_recipe){
                if(isset($_FILES['image']) && $_FILES['image']['type'] == 'image/jpeg'){
                    move_uploaded_file($_FILES['image']['tmp_name'], 'recipes/images_orig/'.$new_recipe['_id'].'.jpg');
                }
                $this->out['new_title'] = $new_recipe['title'];
            }
        }
         
        $this->view('add_form', $data);
    }
    
    public function login(){
        if(!isset($_POST['login']) || !isset($_POST['password'])){
            $this->view('login');
        }
        else{
            if($_POST['login'] == 'root' && $_POST['password'] == 'test'){
                $this->model('main')->login();
                $this->helper('headers')->redirect('main/index');
            }
            else
                $this->view('login');
        }
    }
    
    public function logout(){
        $this->model('main')->logout();
        $this->helper('headers')->redirect('main/login');
    }
    
    public function categories(){
        echo json_encode($this->model('main')->getCategories());
    }
    
    public function add_category(){
        $title = htmlspecialchars(trim($_POST['title'], ENT_NOQUOTES));
        $this->model('main')->addCategory($title);
    }
    
    public function sub_categories(){
        $title = htmlspecialchars(trim($_POST['title'], ENT_NOQUOTES));
        echo json_encode($this->model('main')->getSubCategories($title));
    }
    
    public function add_sub_category(){
        $title = htmlspecialchars(trim($_POST['title'], ENT_NOQUOTES));
        $category_title = htmlspecialchars(trim($_POST['category'], ENT_NOQUOTES));
        $this->model('main')->addSubCategory($category_title, $title);
    }
    
    public function search_country(){
        echo json_encode($this->model('main')->searchCountry(
                htmlspecialchars(trim($_POST['str']), ENT_NOQUOTES))
             );
    }
    
    public function search_ingredient(){
        echo json_encode($this->model('main')->searchIngredient(
                htmlspecialchars(trim($_POST['str']), ENT_NOQUOTES))
             );
    }
    
    public function ingredients_categories(){
        echo json_encode($this->model('main')->getIngredientsCategories());
    }
    
    public function save_ingredient(){
        $title = htmlspecialchars(trim($_POST['title'], ENT_NOQUOTES));
        $category_title = htmlspecialchars(trim($_POST['category'], ENT_NOQUOTES));
        $this->model('main')->addIngredient($category_title, $title);
    }
    
    public function test(){
        if(empty($_POST)){
            $this->out['content'] = '<form method="post"><ol>';
            $j = 0;
            for($i = 1; $i <= 28; $i++){
                $page = 'http://ivona.bigmir.net/cooking/recipes?mfilter=%D1%E0%EB%E0%F2%FB&p='.$i;
                $content = file_get_contents($page);
                preg_match_all('#<div class="b-articles_prev_big_box">(.*)</div>#isU', $content, $array);
                foreach($array[1] as $entry){
                    preg_match('#<h5><a href="(.*)" class="g-h2">(.*)</a></h5>#isU', $entry, $title);
                    $this->out['content'] .= '<li>'.iconv('windows-1251', 'utf-8', $title[2]).' 
                    <input name="category[]" value="Салаты"/>
                    <input name="sub_category[]" value=""/>
                        <input type="hidden" name="links[]" value="'.$title[1].'"/>
                    <input type="checkbox" name="links_to_do[]" value="'.$title[1].'" checked="checked"/>
                        <a href="http://ivona.bigmir.net'.$title[1].'" target="_blank">link</a>
                    </li>';
                    $j++;
                }
            }
            $this->out['content'] .= '</ol><input type="submit"/></form>';
            $this->view();
        }
        else{
            //echo count($_POST['links_to_do']);
            $this->library('mongo')->insert('insertation',array('id' => $_POST));
        }
    }
    
    public function test2(){
        $data = $this->library('mongo')->findOne('insertation');
        $out = array();
        $i = 0;
        foreach($data['id']['links'] as $link){
            $out[$link] = array('category' => $data['id']['category'][$i], 'sub_category' => $data['id']['sub_category'][$i]);
        $i++;
            
        }
        $i = 0;
        foreach($out as $link => $prop){
            if(in_array($link, $data['id']['links_to_do'])){
                $final_out = array(
                    'link' => $link,
                    'category' => $prop['category'],
                    'sub_category' => $prop['sub_category']
                );
                $i++;
                $this->library('mongo')->insert('insert_jobs', $final_out);
            }
        }
    }
    
    public function test3(){
        $ing_total = array();
        $i = 0;
        foreach($this->library('mongo')->find('insert_jobs') as $data){
            $page = 'http://ivona.bigmir.net'.$data['link'];

            $content = file_get_contents($page);
            preg_match('#<!-- b-recipe -->(.*)<!--end b-recipe -->#isU', $content, $ingredients);
            if(!empty($ingredients)){
                //echo $ingredients[1];
                preg_match_all('#<span class="g-fl">(.*)</span>.*<span class="g-fr">(.*)</span>#isU', iconv('windows-1251', 'utf-8', $ingredients[1]), $ingredients);
                foreach($ingredients[1] as $ingredient){
                    $ingredient = explode(',', $ingredient);
                    if(is_array($ingredient)){
                        foreach($ingredient as $ing){
                            if(!in_array($ing, $ing_total)) $ing_total[] = $ing;
                        }
                    }
                    elseif(!in_array($ingredient, $ing_total))
                        $ing_total[] = $ingredient;
                }
            }
            usleep(500);
            echo ++$i."\r";
            
        }
        $this->library('mongo')->insert('ing_temp', $ing_total);
    }
    
    public function test4(){
        if(empty($_POST)){
            $select = '<select name="category[]">';
            foreach($this->library('mongo')->find('ingredients_categories') as $cat){
                $select .= '<option>'.$cat['title'].'</option>';
            }
            $select .= '</select>';
            $data = $this->library('mongo')->findOne('ing_temp');
            $this->out['content'] = '<form method="post">';
            foreach($data as $ingredient){
                $this->out['content'] .= '<input name="ingredients[]" value="'.trim($ingredient).'"/>'.$select.'<br/>';
            }
            $this->out['content'] .= '<input type="submit"/></form>';
            $this->view();
        }
        else{
            $this->library('mongo')->insert('ingredients_temp', $_POST);
        }
    }
    
    public function test5(){
        $data = $this->library('mongo')->findOne('ingredients_temp');
        $i = 0;
        foreach($data['ingredients'] as $ingredient){
            if($ingredient != ''){
                $ingredient = mb_strtolower($ingredient, 'utf-8');
                if(!$this->library('mongo')->findOne('ingredients', array('title' => $ingredient)))
                        $this->library('mongo')->insert('ingredients', array(
                            'title' => $ingredient,
                            'cat' => $data['category'][$i]
                        ));
            }
            $i++;
        }
    }
    
    
    
}
