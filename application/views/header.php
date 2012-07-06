<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta  http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?=$title;?></title>
	<?=link_tag('data/css/style.css');?>
</head>
<body><div id="wrap">
       <div class="header">
       		<div id="basket">
				<div id="basket_icon"></div>
				<p>В корзине 2 товара на сумму $100</p>
				<center><p><a href="#"><b>Перейти</b></a>
				</p>
			</div>         
			<div id="menu">
				<ul>                                                                       
				<li><a href="index.html">Главная</a></li>
				<li><a href="about.html">О нас</a></li>
				<li <?php echo ($this->uri->rsegment(1) == 'catalog')?'class="selected"':'';?>><a href=<?=base_url('index.php/catalog');?>>Продукты</a></li>
				<li><a href="specials.html">Контакты</a></li>
				</ul>
			</div>			
       </div>
	   <div id="content">