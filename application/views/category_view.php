<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta  http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?=$cat_name;?></title>
	<?=link_tag('data/css/style.css');?>
</head>
<body><div id="wrap">
       <div class="header">
       		<div id="basket">
				<div id="basket_icon"></div>
				<p>В корзине 2 товара на сумму $100</p>
				<center><p><a href="#"><b>Перейти</b></a>
				</p>
			</div>         
			<div id="menu">
				<ul>                                                                       
				<li><a href="index.html">Главная</a></li>
				<li><a href="about.html">О нас</a></li>
				<li class="selected"><a href="catalog">Продукты</a></li>
				<li><a href="specials.html">Контакты</a></li>
				</ul>
			</div>			
       </div>
	   
	   <div id="main">
	   <div id="content">
		<?php
		// Выводим путь к категории
		$parents = count($path); // Кол-во родителей категории
		$counter = 0;					// Счетчик для цикла
		echo "<p id='category_path'>";
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
		echo "<div id='category'>$cat_name</div>";
		
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
				$str = "<div id='item'><div id='item_img' style='background-image:url(".base_url($row->thumb).")'></div><div id='item_name'>".anchor('#', $row->name)."</div>";
				$str .= "<div id='item_details'><p><b>Артикул: </b>".$row->article."</p>";	// Артикул
				$str .=  "<p><b>Описание: </b>".character_limiter($row->description,64)."</p>";		// Описание
				$str .=  "<p><b>Цена: </b>".$row->price." грн.</p></div></div>";		// Цену
				$items_list[] = $str;			// Добавляем блок объекта в массив
			}
			$new_items_list = $this->table->make_columns($items_list, 2);
			echo $this->table->generate($new_items_list);
		}
		?>
	   </div>
	   </div>    
       <div class="footer">
		<a href="#">Главная</a>
		<a href="#">О нас</a>
		<a href="#">Контакты</a>
		<p>Flower shop &copy; 2012</p>
        </div>
	</body>
</html>