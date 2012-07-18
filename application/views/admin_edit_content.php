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
		
		// Название и кнопка добавления категории
		echo '<div class="p_admin">Подкатегории '.anchor(site_url('admin/cat_add/'.$cat_id), img(base_url('data/images/add.png')), array('class'=>"cat_add", 'title'=>"Добавить")).'</div>';
		echo '<div id="form_add_cat">';
		echo form_open('admin/cat_add/'.$cat_id);
		echo form_input(array('id'=>'cat_name', 'name'=>'cat_name'));
		echo form_submit('submit', 'Добавить', 'id="adminbtn"');
		echo form_close();
		echo '</div>';
		
		// Вывод всех подкатегоирй
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
									anchor(site_url('product/view/'.$row->id), $row->name), 
									'<p style="text-align:justify">'.character_limiter($row->description,256).'</p>', 
									$row->price.' грн.',
									anchor(site_url('admin/prod_delete/'.$key), img('data/images/edit.png'), array('id'=>$row->id, 'class'=>"prod_edit", 'title'=>"Редактировать")).'&nbsp;'.
									anchor(site_url('admin/prod_edit/'.$key), img('data/images/delete.png'), array('id'=>$row->id, 'class'=>"prod_del", 'title'=>"Удалить"))
									);
		}
		$tmpl = array (
                    'table_open'          => '<table border="0" class="admin_change_table">',
                    'heading_row_start'   => '<tr class="cart_table_heading">',
                    'row_start'           => '<tr class="cart_white_row">',
                    'row_alt_start'       => '<tr class="cart_white_row">', 			
              );
		$this->table->set_template($tmpl);		// Применение шаблона
		$this->table->set_heading('ID', 'Фото', 'Артикул', 'Название', 'Описание', 'Цена', 'Действия');	// Формирование заголовка
		
		// Название и кнопка добавления товара
		echo '<div class="p_admin">Товары в категории '.anchor(site_url('admin/prod_add/'.$cat_id), img(base_url('data/images/add.png')), array('class'=>"prod_add", 'title'=>"Добавить")).'</div>';
		
		// Вывод всех товаров
		echo $this->table->generate($items_list);
		$this->table->clear();
	}
