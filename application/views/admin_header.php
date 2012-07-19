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
					<div id="menu">
						<ul>          
						<li <?php echo ($this->uri->rsegment(1) == 'admin'&&$this->uri->rsegment(2) =='view_buyings')?'class="selected"':'';?>><a href=<?=site_url('admin/view_buyings');?>>Заказы</a></li>
						<li <?php echo ($this->uri->rsegment(1) == 'admin'&&($this->uri->rsegment(2) =='edit_content'||$this->uri->rsegment(2) =='prod_edit'||$this->uri->rsegment(2) =='prod_add'))?'class="selected"':'';?>><a href=<?=site_url('admin/edit_content');?>>Изменение контента</a></li>
						<li><a href=<?=site_url();?>>Выход</a></li>
						</ul>
					</div>			
			   </div>
			   <div id="content">