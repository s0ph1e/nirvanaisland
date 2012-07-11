<div class='mainInfo'>

	<div id="name">Регистрация</div>
	
	<?php if($message)
	echo "<div id='error'>$message</div>";?>
	<div id="create_user_form">
    <?php echo form_open("auth/create_user");?>
      <p>Логин:<br />
      <?php echo form_input($first_name);?>
      </p>
      
      <p>Email:<br />
      <?php echo form_input($email);?>
      </p>
      
      <p>Пароль:<br />
      <?php echo form_input($password);?>
      </p>
      
      <p>Подтвержение пароля:<br />
      <?php echo form_input($password_confirm);?>
      </p>
      
      <p><?php echo form_submit('submit', 'Зарегистрироваться','id="submitbtn"');?></p>
 
    <?php echo form_close();?>
	</div>
</div>
</div>