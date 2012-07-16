<div id='name'>Отправка заказа</div>
<?php
	if($this->ion_auth->logged_in())
	{
?>
	<?php if($message)
	echo "<div id='form_error'>$message</div>";?>
	<div id="form">
	<?php echo form_open("shopcart/order");?>
      
	<p>Имя:<br />
    <?php echo form_input(array('id'=>'username', 'name'=>'username','value'=>$this->ion_auth->user()->row()->username, 'readonly'=>'true'));?>
    </p>
	  
	<p>Адрес:<br />
    <?php echo form_textarea(array('id'=>'textarea_addres', 'name'=>'addres','value'=>$addres));?>
    </p>
      
    <p>Телефон:<br />
    <?php echo form_input(array('id'=>'phone', 'name'=>'phone','value'=>$phone));?>
	</p>
	  
	<p><?php echo form_submit('order_submit', 'Отправить заказ','id="ordbtn"');?></p>
 
    <?php echo form_close();?>
	</div>
<?php
	}
	else echo 'Чтобы заказать товары, нужно войти или '.anchor('auth/registration', 'зарегистрироваться');
?>