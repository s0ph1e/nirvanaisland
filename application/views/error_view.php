﻿<html>
<head>
	<meta  http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Ошибка</title>
	<?=link_tag('/data/css/style.css');?>
</head>
<body>
	<div id = "page">
	<div id = "header"></div>
	<div id = "content">
		<h1>Ошибка</h1>
		<p class = "error"><?=$text;?></p>
		<p><?=anchor(getenv("HTTP_REFERER"), 'Вернуться назад');?></p>
	</div>
	<div id = "footer"></div>
	</div>
</body>
</html>