<?php
	// Выводим путь к категории
	$parents = count($path); // Кол-во родителей категории
	$counter = 0;					// Счетчик для цикла
	echo "<p id='admin_path'>";
	foreach($path as $id => $category) 
	{ 	
		$counter++;
		if($counter==$parents) //Если последний, то просто текст без стрелочки
		{
			echo "<b>",$category,"</b></p>";
		}
		else // Если элемент не последний, то добавляем ссылку на категорию и стрелочки
		{
			echo "<b>", anchor('admin/edit_content/'.$id, $category)." >> </b>"; 
		}	
	}
	
	// Выводим категории
	if($subcategories) // Если подкатегории есть
	{
		foreach ($subcategories as $row)
		{
			$subcat_list[] = array($row->id, 
									anchor('admin/edit_content/'.$row->id, $row->category),
									anchor(site_url('admin/cat_delete/'.$key), img('data/images/edit.png'), array('id'=>$row->id, 'class'=>"cat_edit", 'title'=>"Редактировать")).'&nbsp;'.
									anchor(site_url('admin/cat_edit/'.$key), img('data/images/delete.png'), array('id'=>$row->id, 'class'=>"cat_del", 'title'=>"Удалить"))
									);
		}
		$tmpl = array (
                    'table_open'          => '<table border="0" class="admin_change_table">',
                    'heading_row_start'   => '<tr class="cart_table_heading">',
                    'row_start'           => '<tr class="cart_white_row">',
                    'row_alt_start'       => '<tr class="cart_white_row">', 					
					);
	
		$this->table->set_template($tmpl);		// Применение шаблона
		$this->table->set_heading('ID', 'Категория', 'Действия');	// Формирование заголовка
		
		$image = array('src'=>base_url('data/images/add.png'), 'align'=>'middle');
		echo '<div class="p_admin">Подкатегории '.anchor(site_url('admin/cat_add/'.$cat_id), img($image), array('id'=>$cat_id, 'class'=>"cat_add", 'title'=>"Добавить")).'</div>';
		echo $this->table->generate($subcat_list);
		$this->table->clear();
	}
	
	// Выводим товары
	if($items)
	{
		foreach ($items as $row)
		{
			$items_list[] = array(	$row->id, 
									'<img width="64" height="64" src='.base_url($row->thumb).'>',
									$row->article, 
									anchor(site_url('products/view'.$row->id), $row->name), 
									character_limiter($row->description,128), 
									$row->price.' грн');
		}
		$tmpl = array (
                    'table_open'          => '<table border="0" class="admin_change_table">',
                    'heading_row_start'   => '<tr class="cart_table_heading">',
                    'row_start'           => '<tr class="cart_white_row">',
                    'row_alt_start'       => '<tr class="cart_white_row">', 			
              );
		$this->table->set_template($tmpl);		// Применение шаблона
		$this->table->set_heading('ID', 'img', 'Артикул', 'Название', 'Описание', 'Цена', 'Действия');	// Формирование заголовка
		echo '<p class="p_admin">Товары в категории</p>';
		echo $this->table->generate($items_list);
		$this->table->clear();
	}
