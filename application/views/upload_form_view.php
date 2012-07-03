<html>
<head>
	<meta  http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Добавление товара</title>
	<?=link_tag('/data/css/style.css');?>
</head>
<body>
	<div id = "page">
	<div id = "header"></div>
	<div id = "content">
	<h1>Добавление товара</h1>
	<?php
		echo "Добавить с помощью формы\t";
		echo anchor('catalog/upload_csv/'.$cat_id, 'Импорт из CSV');
		if ($text) echo $text;
		echo form_open_multipart('catalog/upload_form/'.$cat_id);
	?>
		<table id='add_table'>
		<tr><td>Артикyл</td><td><input type='text' name='article' class='myinput' value="<?=set_value('article');?>"/></td></tr>
		<tr><td>Имя</td><td><input type='text' name='name' class='myinput' value="<?=set_value('name');?>"/></td></tr>
		<tr><td>Описание</td><td><textarea rows = '3' name='description' class='myinput'><?=set_value('description');?></textarea></td></tr>
		<tr><td>Цена</td><td><input type='text' name='price' class='myinput'value="<?=set_value('price');?>"/></td></tr>
		<tr><td>Изображение</td><td><input type='file' name='item_image' class='myinput'/></td></tr>
		<tr><td colspan='2'><center><input type='submit' name='submit_form' value='Загрузить' /></td></tr>
		</table></form>
	<?php
		echo '<p>',anchor('catalog/category/'.$cat_id, 'Вернуться к категории'),'</p>';
	?>
	</div>
	<div id = "footer"></div>
	</div>
</body>
</html>