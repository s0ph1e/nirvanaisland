<?='<p id="admin_path">'.anchor(site_url('admin_edit_content/'.$cat_id),  'Назад').'</p>';?>
<div id="name">Добавление товара</div>

<?php if($text)
echo "<div id='form_error'>$text</div>";?>
<div id="form">
<?php echo form_open_multipart('admin/prod_add/'.$cat_id);?>
  <p>Артикул:<br />
  <?php echo form_input(array('name'=>'article','value'=>$article));?>
  </p>
  
  <p>Название:<br />
  <?php echo form_input(array('name'=>'name','value'=>$name));?>
  </p>
  
  <p>Описание:<br />
  <?php echo form_textarea(array('id'=>'textarea_addres', 'name'=>'description','value'=>$description));?>
  </p>
  
  <p>Цена:<br />
  <?php echo form_input(array('name'=>'price','value'=>$price));?>
  </p>
  
  <p>Изображение:<br />
  <input type="file" name="item_image" size="20" />
  </p>
  
  <p><?php echo form_submit('submit', 'Добавить','id="regbtn"');?></p>

<?php echo form_close();?>
</div>
