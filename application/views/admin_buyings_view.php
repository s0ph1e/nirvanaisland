<div id='name'>Таблица заказов</div>
<?php
	if($message) echo $message;
	if ($buyings)
	{
	// Установка шаблона
	$tmpl = array (
                    'table_open'          => '<table border="0" class="admin_table">',
                    'heading_row_start'   => '<tr class="cart_table_heading">',
                    'row_start'           => '<tr class="cart_white_row">',
					'row_end'             => '</tr>',
                    'row_alt_start'       => '<tr class="cart_white_row">', 
					'row_alt_end'         => '</tr>',					
              );
	
	$this->table->set_template($tmpl);		// Применение шаблона
	$this->table->set_heading('Пользователь', 'Адрес доставки', 'Телефон','Заказ', 'Статус', 'Изменить статус');		// Формирование заголовка
	echo $this->table->generate($buyings);
	}
	
?>