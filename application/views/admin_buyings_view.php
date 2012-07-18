<script type="text/javascript" >
<?php if(isset($cur_type)){echo 'var view_id='.$cur_type.';';}?>
</script>
<div id='name'>Таблица заказов</div>
<?php
	echo '<div id="view_types">';
	foreach ($types as $key=>$value)
	{
		if($key == $cur_type) echo '<span id="cur_type">'.$value.'</span>';
		else echo anchor('admin/view_buyings/'.$key, $value). ' ';
	}
	echo '</div>';
	if($message) echo $message;
	echo '<div id="buying">';
	if ($buyings)
	{
	// Установка шаблона
	$tmpl = array (
                    'table_open'          => '<table border="0" class="admin_table">',
                    'heading_row_start'   => '<tr class="cart_table_heading">',
                    'row_start'           => '<tr class="cart_white_row">',
                    'row_alt_start'       => '<tr class="cart_white_row">', 				
              );
	
	$this->table->set_template($tmpl);		// Применение шаблона
	$this->table->set_heading('Пользователь', 'Адрес доставки', 'Телефон','Заказ', 'Статус');	// Формирование заголовка
	echo $this->table->generate($buyings);
	}
	else echo '<p>Заказов с введенными параметрами нет.</p>';
	echo '</div>';
	
?>