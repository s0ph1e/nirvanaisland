<?php
	if($message) echo $message;
	if ($buyings)
	{
	// Установка шаблона
	$tmpl = array (
                    'table_open'          => '<table border="0" class="cart_table">',
                    'heading_row_start'   => '<tr class="cart_table_heading">',
                    'row_start'           => '<tr class="cart_white_row">',
					'row_end'             => '</tr>',
                    'row_alt_start'       => '<tr class="cart_white_row">', 
					'row_alt_end'         => '</tr>',					
              );
	
	$this->table->set_template($tmpl);		// Применение шаблона
	$this->table->set_heading('id', 'Пользователь', 'Адрес доставки', 'Телефон','Заказ');		// Формирование заголовка
	echo $this->table->generate($buyings);
	}
	
?>