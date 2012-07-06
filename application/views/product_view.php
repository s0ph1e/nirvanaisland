<?php
	// Выводим путь к категории
	$parents = count($path); // Кол-во родителей категории
	$counter = 0;					// Счетчик для цикла
	echo "<p id='path'>";
	foreach($path as $id => $category) 
	{ 	
		$counter++;
		if($counter==$parents) //Если последний, то просто текст без стрелочки
		{
			echo "<b>",$category,"</b></p>";
		}
		else // Если элемент не последний, то добавляем ссылку на категорию и стрелочки
		{
			echo "<b>", anchor('catalog/category/'.$id, $category)." >> </b>"; 
		}	
	}
	//Выводим название категории
	echo "<div id='name'>$name</div>";
?>
