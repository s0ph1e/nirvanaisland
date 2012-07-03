<html>
<head>
	<meta  http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Добавлено!</title>
	<?=link_tag('/data/css/style.css');?>
</head>
<body>
	<div id = "page">
	<div id = "header"></div>
	<div id = "content">
		<h1>Добавлено!</h1>
		<p><?=$text;?></p>
		<p><?=anchor('catalog/upload_'.$type.'/'.$cat_id, 'Загрузить еще');?>
		<?=anchor('catalog/category/'.$cat_id, 'Вернуться к категории');?></p>
	</div>
	<div id = "footer"></div>
	</div>
</body>
</html>