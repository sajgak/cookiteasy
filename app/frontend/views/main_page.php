<?foreach ($recipes as $recipe):?>
<h3><?=$recipe['category']?><?if(isset($recipe['sub_category'])):?> :: <?=$recipe['sub_category']?><?endif?> :: <?=$recipe['title']?></h3>
<table>
    <tr>
        <td>
            <img src="/recipes/images_orig/<?=$recipe['_id']?>.jpg" alt="<?=$recipe['title']?>"/><br/>
            Кухня: <?=$recipe['citchen']['region']?>
        </td>
        <td>
            <small>Ингредиенты: <?foreach($recipe['ingredients'] as $ingredient):?><?=$ingredient['title']?><?endforeach?></small><br/><br/>
            <?=$recipe['desc']?>
        </td>
    </tr>
</table>
<? endforeach; ?>