<div id='name'>Корзина</div>
<div id="cart_content">
<?php 
if($cart)
{
	// Вывод таблицы товаров в корзине
	echo '<p id="cart_message">В корзине '.$qty.' товаров на сумму '.$total.' грн.</p>'; 
	
	// Установка шаблона для корзины
	$tmpl = array (
                    'table_open'          => '<table border="0" class="cart_table">',
                    'heading_row_start'   => '<tr class="cart_table_heading">',
                    'row_start'           => '<tr class="cart_white_row">',
					'row_end'             => '</tr>',
                    'row_alt_start'       => '<tr class="cart_white_row">', 
					'row_alt_end'         => '</tr>',					
              );
	$this->table->set_template($tmpl);		// Применение шаблона
	$this->table->set_heading('Наименование', 'Количество', 'Цена за единицу', 'Общая стоимость','Действия');		// Формирование заголовка
	echo $this->table->generate($cart);			// Генерирование таблицы
	$this->table->clear();
	echo form_open('shopcart/order');
	echo '<center>'.form_submit('cart_submit', 'Отправить заказ', 'id="ordbtn"');
	echo form_close();
}
else echo '<div id="message"><p>Корзина пуста</p><p>'.anchor(getenv("HTTP_REFERER"), 'Вернуться назад').'</p></div>';
?>
</div>
