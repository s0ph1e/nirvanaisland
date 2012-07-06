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
	
	echo "<div id='view_item'><div id='view_item_img' style='background-image:url(".base_url($image).")'></div>";
	echo "<div id='view_item_info'>";
	echo "<p><b>Артикул: </b>".$article."</p>";
	echo "<p><b>Название: </b>".$name."</p>";
	echo "<p><b>Описание: </b>".$description."</p>";
	echo "<p><b>Цена: </b>".$price." грн.</p>";
	echo "<center><input type='submit' name='add_product' id='add_button' value='В корзину'/>";
	echo "</div></div>";
?>
