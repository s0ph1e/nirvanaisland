<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta  http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src=<?=base_url('data/js/jqeasy.dropdown.js');?>></script>
	<script type="text/javascript" src=<?=base_url('data/js/jquery.form-2.4.0.min.js');?>></script>
	<script type="text/javascript" src=<?=base_url('data/js/jquery.blockUI.js');?>></script>
	<script type="text/javascript" src=<?=base_url('data/js/blockUI.use.js');?>></script>
	<script type="text/javascript" >
	var site_url = '<?=site_url()?>';
	var base_url = '<?=base_url()?>';
	<?php if(isset($id)){echo 'var page_id='.$id.';';}?>
	</script>
	<title><?=$title;?></title>
	<?=link_tag('data/css/style.css');?>
</head>
<body><div id="wrap">
       <div id="header">
			<div id="basket">
			<?php
				echo anchor(site_url('/shopcart/view'), 'Корзина', 'class="btnbasket"');
			?>
			</div>
			<div id="signbtn">
			<?php if($this->ion_auth->logged_in())
			{
				echo anchor(site_url('auth/logout'), 'Выйти', 'class="btnsignout" id="btnsignout"');
			}
			else 
			{
				echo anchor(site_url('auth/login'), 'Войти', 'class="btnsignin"');
			}
			?>
			</div>
			<div id="frmsignin">
				<?php echo form_open("auth/login", array('id'=>'signin'));?>
				<p>
					<label for="identity">Email:</label>
					<?php echo form_input(array('id'=>'identity', 'name'=>'identity','value'=>$identity));?>
				</p>
				<p>
					<label for="password">Пароль:</label>
					<?php echo form_input(array('id'=>'password', 'name'=>'password','value'=>$password, 'type'=>'password'));?>
				</p>
				<p>
				  <label for="remember">Запомнить меня:</label>
				  <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
				</p>
				<p><?php echo form_submit('submit', 'Войти', 'id="submitbtn"');?></p>
				<?php echo form_close();?>
			<p><?=anchor(site_url('auth/registration'),'Регистрация');?></p>
			<p id="msg"></p>
			</div>
					
					<div id="menu">
						<ul>                                                                       
						<li><a href=<?=site_url()?>>Главная</a></li>
						<li <?php echo ($this->uri->rsegment(1) == 'page'&&$this->uri->rsegment(2) =='about')?'class="selected"':'';?>><a href=<?=site_url('page/about')?>>О нас</a></li>
						<li <?php echo ($this->uri->rsegment(1) == 'catalog'||$this->uri->rsegment(1) =='product')?'class="selected"':'';?>><a href=<?=site_url('catalog');?>>Продукты</a></li>
						<li <?php echo ($this->uri->rsegment(1) == 'page'&&$this->uri->rsegment(2) =='contacts')?'class="selected"':'';?>><a href=<?=site_url('page/contacts')?>>Контакты</a></li>
						</ul>
					</div>			
			   </div>
			   <div id="content">