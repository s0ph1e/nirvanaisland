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
		echo anchor('catalog/upload_form/'.$cat_id, 'Добавить с помощью формы');
		echo "\tИмпорт из CSV";
		if ($text) echo $text;
		echo form_open_multipart('catalog/upload_csv/'.$cat_id);
		echo <<<END
		<input type="file" name="csv_item" size="20" />
		<input type="submit" name="submit_csv" value="Загрузить" />
		</form>
END;
		echo '<p>',anchor('catalog/category/'.$cat_id, 'Вернуться к категории'),'</p>';
	?>
	</div>
	<div id = "footer"></div>
	</div>
</body>
</html>