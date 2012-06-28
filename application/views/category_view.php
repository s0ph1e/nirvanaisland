<html>
<head>
	<meta charset="utf-8">
	<title><?=$title;?></title>
	<?=link_tag('/data/css/style.css');?>
</head>
<body>
	<div id = "page">
	<div id = "header"></div>
	<div id = "content">
		<h1><?=$title;?></h1>
		<?php
		//Выводим путь к категории
		$parents = count($path); //Кол-во родителей категории
		$counter = 0;					// Счетчик для цикла. Криво, но работает
		foreach($path as $id => $category) 
		{ 	
			$counter++;
			if($counter==$parents) //Если последний, то просто текст без стрелочки
			{
				echo "<b>",$category,"</b>";
			}
			else //Если элемент не последний, то добавляем ссылку на категорию и стрелочки
			{
				echo "<b>", anchor('catalog/category/'.$id, $category)." -> </b>"; 
			}	
		} 
		
		//Выводим содержимое категории
		echo "<ul>";
		foreach ($content as $row)
		{
			echo "<li>", anchor('catalog/category/'.$row->id, $row->category), "</li>";
		}
		echo "</ul>";
		?>
	</div>
	<div id = "footer"></div>
	</div>
</body>
</html>