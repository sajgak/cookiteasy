<?$this->view('parts/top')?>
<script src="<?=$this->config('main')->static_domain?>/libraries/ckeditor/ckeditor.js"></script>
<link type="text/css" rel="stylesheet" media="all" href="<?=$this->config('main')->static_domain?>/backend/styles/main.css" />
<script src="<?=$this->config('main')->static_domain?>/backend/js/main.js"></script>
<div id="content">
    <?if(isset($error)):?>Ошибка записи: <?=$error?><?endif?>
    <?if(isset($new_title)):?> Новый рецепт "<?=$new_title?>" успешно сохранен<?endif?>
    <form method="post"  enctype="multipart/form-data">
    <table>
        <tr><td class="input_name">Название</td><td><input name="title" required="required"/></td></tr>
        <tr><td class="input_name">Категория</td><td><input name="category" required="required"/></td></tr>
        <tr><td class="input_name">Подкатегория</td><td><input name="sub_category"/></td></tr>
        <tr><td class="input_name">Время приготовления</td><td>
                <select name="time" required="required">
                    <option value="1">15 минут</option>
                    <option value="2">30 минут</option>
                    <option value="3">1 час</option>
                    <option value="4">2 часа</option>
                    <option value="5">более двух часов</option>
                </select>
            </td></tr>
        <tr><td class="input_name">Кол-во порций</td><td><input name="output_count" type="number" required="required" min="1"/></td></tr>
        <tr><td class="input_name">Тип кухни</td><td><input name="kitchen" required="required"/></td></tr>
        <tr><td class="input_name">Обобенности блюда</td>
            <td>
                <input type="checkbox" name="vegetarian"/> Вегетарианское блюдо&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="child"/> Детское блюдо&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="diet"/> Низкокалорийное блюдо
            </td>
        </tr>
        <tr><td class="input_name">Инградиенты</td><td><input name="ingredients_form" required="required"/><div id="ingredients"></div></td></tr>
        <tr><td class="input_name">Описание</td><td><textarea name="text" id="ckeditor"></textarea></td></tr>
        <tr><td class="input_name">Изображение</td><td><input type="file" name="image"/></td></tr>
    </table>
    <div style="text-align: center"><input type="submit" value="Добавить рецепт"/></div>
    </form>
</div>
<script type="text/javascript">
CKEDITOR.replace( 'ckeditor',
    {
        filebrowserBrowseUrl : '/static/libraries/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : '/static/libraries/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : '/static/libraries/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : '/static/libraries/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : '/static/libraries/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : '/static/libraries/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
</script>
<?$this->view('parts/footer')?>