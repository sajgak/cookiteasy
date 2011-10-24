<?='<?xml version="1.0" encoding="utf-8"?>'?>
<sphinx:docset>

<sphinx:schema>
  <sphinx:field name="name"/>
  <sphinx:attr name="_id" type="string"/>
</sphinx:schema>

<? $i = 1; foreach ( $ingredients as $ingredient ) { ?>
<sphinx:document id="<?=$i?>">
<name><![CDATA[[<?=$ingredient['title']?>]]></name>
<_id><?=$ingredient['_id']?></_id>
</sphinx:document>
<? $i++;} ?>

</sphinx:docset>