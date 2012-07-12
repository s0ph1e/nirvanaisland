<?php
	// Выводим путь к категории
	$parents = count($path); // Кол-во родителей категории
	$counter = 0;					// Счетчик для цикла
	echo "<p id='path'>";
	foreach($path as $cat_id => $category) 
	{ 	
		$counter++;
		if($counter==$parents) //Если последний, то просто текст без стрелочки
		{
			echo "<b>",$category,"</b></p>";
		}
		else // Если элемент не последний, то добавляем ссылку на категорию и стрелочки
		{
			echo "<b>", anchor('catalog/category/'.$cat_id, $category)." >> </b>"; 
		}	
	}
?>
	
	<div id='name'><?=$name?></div>
	
	<div id='view_item'><div id='view_item_img' style='background-image:url(<?=base_url($image)?>)'></div>
	<div id='view_item_info'>
	<p><b>Артикул: </b><?=$article?></p>
	<p><b>Название: </b><?=$name?></p>
	<p><b>Описание: </b><?=$description?></p>
	<p><b>Цена: </b><?=$price?> грн.</p>
	<?php echo form_open("shopcart/add_to_cart/".$id);?>
	<center><?php echo form_submit('submit', 'В корзину', 'id="basketbtn"');?>
	<?php echo form_close();?>
	</div></div>

	<?php // Вывод комментариев
		foreach($comments as $row)
		{
			echo '<div class="comment"><div class="comment_top">'.$row->name.'</div><div class="comment_date">'.date('j M y G:i:s', strtotime($row->datetime)).'</div>';
			echo '<p class="comment_message">'.$row->comment.'</p></div>';
		}
	?>
	<div id="addCommentContainer">
	<div class="comment_top">Ваш отзыв:</div>
		<?php echo form_open("comment/add_comment/".$id, array('id'=>'addCommentForm'));?>
			<p>
				<?php echo form_textarea(array('id'=>'textarea_comment', 'name'=>'comment', 'rows'=>2));?>
			</p>
			<p><?php echo form_submit('submit', 'Отправить', 'id="commentbtn"');?></p>
			<?php echo form_close();?>
	</div>
	
