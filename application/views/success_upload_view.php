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
		<p><?=anchor('catalog/add_item/'.$cat_id.'/'.$type, 'Загрузить еще');?></p>
	</div>
	<div id = "footer"></div>
	</div>
</body>
</html>