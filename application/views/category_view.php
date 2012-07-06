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
	echo "<div id='name'>$cat_name</div>";

	// Выводим категории
	if($subcategories) // Если подкатегории есть
	{
		foreach ($subcategories as $row)
		{
			$subcat_list[] = anchor('catalog/category/'.$row->id, $row->category);
		}
		$new_subcat_list = $this->table->make_columns($subcat_list, 4);
		echo "<div id = 'subcategories'>".$this->table->generate($new_subcat_list)."</div>";	
	}

	// Выводим товары
	if($items)
	{
		foreach ($items as $row)
		{
			// Выводим картинку и имя
			$str = "<div id='item'><div id='item_img' style='background-image:url(".base_url($row->thumb).")'></div><div id='item_name'>".anchor(site_url('product/view').'/'.$row->id, $row->name)."</div>";
			$str .= "<div id='item_details'><p><b>Артикул: </b>".$row->article."</p>";	// Артикул
			$str .=  "<p><b>Описание: </b>".character_limiter($row->description,64)."</p>";		// Описание
			$str .=  "<p><b>Цена: </b>".$row->price." грн.</p></div></div>";		// Цену
			$items_list[] = $str;			// Добавляем блок объекта в массив
		}
		$new_items_list = $this->table->make_columns($items_list, 2);
		echo $this->table->generate($new_items_list);
	}