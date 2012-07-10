<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta  http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src=<?=base_url('data/js/jquery.form-2.4.0.min.js');?>></script>
	<script type="text/javascript" src=<?=base_url('data/js/jqeasy.dropdown.min.js');?>></script>
	<title><?=$title;?></title>
	<?=link_tag('data/css/style.css');?>
</head>
<body><div id="wrap">
       <div class="header">
			<div id="basket">
			<?php
				echo anchor(site_url(), 'Корзина', 'class="btnbasket"');
			?>
			</div>
			<div id="signbtn">
			<?php if($this->ion_auth->logged_in())
			{
				echo anchor(site_url('auth/logout'), 'Выйти', 'class="btnsignout"');
			}
			else 
			{
				echo anchor(site_url('auth/login'), 'Войти', 'class="btnsignin"');
			}
			?>
			</div>
			<div id="frmsignin">
				<?php echo form_open("auth/login");?>
				<p>
					<label for="identity">Email:</label>
					<?php echo form_input($identity);?>
				</p>
				<p>
					<label for="password">Пароль:</label>
					<?php echo form_input($password);?>
				</p>
				<p>
				  <label for="remember">Запомнить меня:</label>
				  <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
				</p>
				<p><?php echo form_submit('submit', 'Войти', 'id="submitbtn"');?></p>
				<?php echo form_close();?>
			<p><a href="forgot_password">Забыли пароль?</a></p>
			
			<p id="msg"></p>
			</div>
					
					<div id="menu">
						<ul>                                                                       
						<li><a href="index.html">Главная</a></li>
						<li><a href="about.html">О нас</a></li>
						<li <?php echo ($this->uri->rsegment(1) == 'catalog'||$this->uri->rsegment(1) =='product')?'class="selected"':'';?>><a href=<?=site_url('catalog');?>>Продукты</a></li>
						<li><a href="specials.html">Контакты</a></li>
						</ul>
					</div>			
			   </div>
			   <div id="content">