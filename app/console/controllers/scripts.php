<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of scripts
 *
 * @author SkarbovskiyG
 */
class scripts extends \system\core\Manager{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function test(){
        $j = 0;
        foreach($this->library('mongo')->find('insert_jobs', array('category' => 'Салаты')) as $recipe){
            $final = array();
            $final['category'] = $recipe['category'];
            $final['link'] = $recipe['link'];
            if($recipe['sub_category'] != '')
                $final['sub_category'] = $recipe['sub_category'];
            $content = file_get_contents('http://ivona.bigmir.net'.$recipe['link']);
            preg_match('#<h2 class="g-pr">(.*)</h2>#isU',$content, $title);
            preg_match('#<div class="g-large b-article_text _articleContent">(.*)</div>#isU', $content, $desc);
            preg_match('#{ \'src\' : \'(.*)\', \'text\' : \'#isU', $content, $image);

            preg_match('#<!-- b-recipe -->(.*)<!--end b-recipe -->#isU', $content, $ingredients);
            if(!empty($ingredients)){
                preg_match_all('#<span class="g-fl">(.*)</span>.*<span class="g-fr">(.*)</span>#isU', iconv('windows-1251', 'utf-8', $ingredients[1]), $ingredients);
            }
            $final['title'] = iconv('windows-1251', 'utf-8', $title[1]);
            $final['desc'] = iconv('windows-1251', 'utf-8', $desc[1]);
            if(!empty($ingredients)){
                unset($ingredients[0]);
                $final['ingredients'] = $ingredients;
            }
            $this->library('mongo')->insert('ingredients_tmp', $final);
            $obj = $this->library('mongo')->lastObj('ingredients_tmp');
            if(isset($image[1]))
                $this->library('gearman')->doBackground('download_image', json_encode(array('link' => $image[1], 'id' => $obj['_id']->__toString())));
            
        }
    }
    
    public function test2(){
        $files = scandir ('/var/www/cookiteasy/static/recipes/images_orig/');
        unset($files[0]);
        unset($files[1]);
        $j = 0;
        foreach($files as $file){
            $file = substr($file, 0, -4); 
            $obj = $this->library('mongo')->findOne('reciples_tmp', array('_id' => new MongoId($file)));
            if(!$obj)
                $j++;
        }
        echo $j;
    }
    //put your code here
}

?>
