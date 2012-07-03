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
				<p>В корзине 2 товара</p>
				<p>На сумму $100</p>
				<p><a href="#">Перейти</a></p>
			</div>         
			<div id="menu">
				<ul>                                                                       
				<li><a href="index.html">Главная</a></li>
				<li><a href="about.html">О нас</a></li>
				<li class="selected"><a href="category.html">Продукты</a></li>
				<li><a href="specials.html">Контакты</a></li>
				</ul>
			</div>			
       </div>
	   
	   <div class="center_content">
		   <div class="left_content">
			<h1><?=$cat_name;?></h1>
		<?php
		// Выводим путь к категории
		$parents = count($path); // Кол-во родителей категории
		$counter = 0;					// Счетчик для цикла
		foreach($path as $id => $category) 
		{ 	
			$counter++;
			if($counter==$parents) //Если последний, то просто текст без стрелочки
			{
				echo "<b>",$category,"</b>";
			}
			else // Если элемент не последний, то добавляем ссылку на категорию и стрелочки
			{
				echo "<b>", anchor('catalog/category/'.$id, $category)." -> </b>"; 
			}	
		} 
		
		// Выводим категории
		echo "<ul>";
		foreach ($content as $row)
		{
			echo "<li>", anchor('catalog/category/'.$row->id, $row->category), "</li>";
		}
		echo "</ul>";
		
		// Выводим товары
		echo "<div id='items'>";
		foreach ($items as $row)
		{
			echo '<b>'.$row->name.", </b>";
		}
		echo "</div>";
		
		// Ссылка на добавление товара
		echo '<br><br>', anchor('catalog/upload_form/'.$cat_id, 'Добавить новый товар');
		// Ссылка на добавление категории
		echo '<br><br>', anchor('catalog/add_category/'.$cat_id, 'Добавить новую категорию');
		?>
		   </div><!--end of left content-->
		   <div class="right_content">
		</div><!--end of right content-->
       <div class="clear"></div>
       </div><!--end of center content-->      
       <div class="footer">
       	<div class="left_footer"><img src="images/footer_logo.gif" alt="" title="" /><br /> <a href="http://csscreme.com"><img src="images/csscreme.gif" alt="by csscreme.com" title="by csscreme.com" border="0" /></a></div>
        <div class="right_footer">
        <a href="#">home</a>
        <a href="#">about us</a>
        <a href="#">services</a>
        <a href="#">privacy policy</a>
        <a href="#">contact us</a>
        </div>
       </div>
	</div>
	</body>
</html>