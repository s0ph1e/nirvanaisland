<?php

class Admin extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->model(array('cart_model', 'product_model', 'catalog_model'));
		$this->load->library(array('image_lib'));
		
		if(!$this->ion_auth->is_admin()) redirect(site_url());
	}
	
	function index()
	{
		redirect(site_url('admin/view_buyings'));
	}
	
	function view_buyings($type)		// Просмотр заказов
	{
		if(!is_numeric($type)||!$this->cart_model->status_exist($type)) $type = 1;
	
		if ($type == 3) $buyings = $this->cart_model->get_all_buyings();
		else $buyings = $this->cart_model->get_buyings($type);
		
		foreach($buyings as $row)		// Для каждого заказа получаем информацию
		{	
			// $info - массив данных, которые надо показать админу
			unset($info);
			
			// Получаем юзернейм
			$info['username'] = $this->ion_auth->user($row->user_id)->row()->username;
			
			$info['addres'] = $row->addres;
			$info['phone'] = $row->phone;
			
			// Получаем все заказанные товары
			$items = $this->cart_model->get_buying_items($row->id);
			
			// Для всех товаров формируем ссылку на страницу просмотра
			foreach($items as $product)
			{
				// Получаем инфо продукта для получения названия и создания ссылки
				$product_info = $this->product_model->get_product_info($product->product_id);
				$info['products'] .= anchor(site_url('product/view/'.$product_info->id), $product_info->name.' ('.$product->qty.')').'&nbsp;';
			}
			
			// Статус заказа
			$buying_status = $this->cart_model->get_status_name($row->status_id);
			
			// Все статусы
			$statuses = $this->cart_model->get_statuses();
			
			// Формирование селекта для изменения статуса
			$info['status'] = '<select class="status_select" id='.$row->id.'>';
			foreach ($statuses as $value)
			{
				if($buying_status == $value->status)
				{
					$info['status'].="<option selected value=".$value->id.">".$value->status."</option>";
				}
				else
				{
					$info['status'].="<option value=".$value->id.">".$value->status."</option>";
				}
			}
			$info['status'] .= "</select>";
			
			// Массив для передачи в представление
			$data['buyings'][] = $info;
		}
		
		$data['types'] = array('1'=>'Новые', '2'=>'В процессе', '3'=>'Все заказы');
-		$data['cur_type'] = $type;
		
		$this->load->view('admin_header', array('title'=>'Заказы'));
		$this->load->view('admin_buyings_view', $data);
		$this->load->view('footer');
	
	}
	
	function change_status($id, $new_status)
	{
		if(!is_numeric($id)||!$this->cart_model->buying_exist($id)||!isset($new_status)||!$this->cart_model->status_exist($new_status))
		{
			exit('Fuck off, nigga!');
		}
		else
		{
			$this->cart_model->update_buying($id, $new_status);
			exit(json_encode(array('id'=>$id, 'status' =>$new_status)));
		}
	}
	
	function edit_content($cat_id)
	{
		if(!is_numeric($cat_id)||!$this->catalog_model->category_exist($cat_id)) $cat_id=0;
		
		// Получение названия категории
		if ($cat_id == 0)		// Если 0 - то каталог
		{
			$data['cat_name'] = 'Каталог';
		}
		else					// Иначе получаем название из БД
		{
			$data['cat_name'] = $this->catalog_model->get_category_name($cat_id);
		}
		
		// Получаем подкатегории
		$data['subcategories'] = $this->catalog_model->get_subcategories($cat_id);
		
		// Получение товаров
		$data['items'] = $this->catalog_model->get_category_items($cat_id);
		
		// Получаем путь к категории
		$data['path'] = $this->catalog_model->get_category_path($cat_id);
		
		// ID категории
		$data['cat_id'] = $cat_id;
		
		$this->load->view('admin_header', array('title'=>'Изменение контента'));
		$this->load->view('admin_edit_content', $data);
		$this->load->view('footer');
	}
	
	function cat_add($id)
	{
		if(!is_numeric($id)||!$this->catalog_model->category_exist($id)) exit();
		
		$cat_name = $this->input->post('cat_name');
		if($cat_name) 
		{
			$data['parent_id'] = $id;
			$data['category'] = $cat_name;
			$this->catalog_model->insert_category($data);
			redirect(site_url('admin/edit_content/'.$id));
		}
	}
	
	function cat_del($id)
	{
		if(!is_numeric($id)||!$this->catalog_model->category_exist($id)) exit();
		
		$this->catalog_model->delete_category($id);
		exit(json_encode(array('id'=>$id)));
	}
	
	function cat_edit($id)
	{
		if(!is_numeric($id)||!$this->catalog_model->category_exist($id)) exit();
		$newname = $this->input->post('newname');
		$this->catalog_model->edit_category($id, $newname);
		exit(json_encode(array('id'=>$id, 'link'=>'<span id="cat_name_'.$id.'">'.anchor('admin/edit_content/'.$id, $newname).'</span>',)));
	}
	
	function prod_del($id)
	{
		if(!is_numeric($id)||!$this->product_model->product_exist($id)) exit();
		
		$product = $this->product_model->get_product_info($id);
		$this->product_model->delete_product($id);
		unlink(realpath($product->image));
		unlink(realpath($product->thumb));
		exit(json_encode(array('id'=>$id)));
	}
	
	function prod_add($cat_id)
	{
		if(!is_numeric($cat_id)||!$this->catalog_model->category_exist($cat_id)) exit();
		
		// Параметры загружаемого файла
		$config['upload_path'] = 'uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '2048';
		
		// Передаем параметры в библиотеку загрузки
		$this->load->library('upload', $config);
		
		// Библиотека валидации форм
		$this->load->library('form_validation');
		
		// Установка правил валидации для полей
		$this->form_validation->set_rules('article', 'Артикул', 'required|min_length[2]|max_length[8]');
		$this->form_validation->set_rules('name', 'Имя', 'required|min_length[2]|max_length[32]');
		$this->form_validation->set_rules('description', 'Описание', 'required');
		$this->form_validation->set_rules('price', 'Цена', 'required|greater_than[0]');
		
		// Если отправлена форма
		if(isset($_POST['submit']))		
		{
			$success_upload = $this->upload->do_upload('item_image'); // Загружен ли файл
			$image = $this->upload->data();			// Загруженный файл
			
			// Если все хорошо и с полями, и с файлом
			if($this->form_validation->run()&&$success_upload)
			{	
				// Создание большого изображения
				$big['image_library'] = 'gd2';
				$big['source_image']	= $image['full_path'];
				$big['create_thumb'] = FALSE;
				$big['width']	 = 400;
				$big['height']	= 320;

				$this->image_lib->clear();
				$this->image_lib->initialize($big);				
				if ( ! $this->image_lib->resize())
				{
					//$this->load->view('upload_form_view', array('text'=>$this->image_lib->display_errors()));
					return;
				}
				
				// Создание thumb
				$thumb['image_library'] = 'gd2';
				$thumb['source_image']	= $image['full_path'];
				$thumb['create_thumb'] = TRUE;
				$thumb['thumb_marker'] = '_thumb';
				$thumb['width']	 = 180;
				$thumb['height']	= 150;

				$this->image_lib->clear();
				$this->image_lib->initialize($thumb); 
				if ( ! $this->image_lib->resize())
				{
					//$this->load->view('upload_form_view', array('text'=>$this->image_lib->display_errors()));
					return;
				}
				
				$db['image'] = $config['upload_path'].$image['file_name'];	// Путь к файлу
				$db['thumb'] = $config['upload_path'].$image['raw_name'].'_thumb'.$image['file_ext']; // Путь к thumb
				$db['parent_id'] = $cat_id;			// Категория товара
				$db['article'] = $_POST['article'];
				$db['name'] = $_POST['name'];
				$db['description'] = $_POST['description'];
				$db['price'] = $_POST['price'];
				
				// Добавление записи в БД
				$this->product_model->insert_product($db);
				
				redirect(site_url('admin/edit_content/'.$cat_id));
			}
			else 	// Если что-то не так с полями или загрузкой файла
			{
				$data['name'] = $this->form_validation->set_value('name');
				$data['article'] = $this->form_validation->set_value('article');					
				$data['description'] = $this->form_validation->set_value('description');
				$data['price'] = $this->form_validation->set_value('price');								
				$data['text'] = validation_errors();
				$data['text'].=$this->upload->display_errors();
				unlink(realpath($image['full_path']));
			}
		}
		$data['cat_id'] = $cat_id;
		$this->load->view('admin_header', array('title'=>'Добавление товара'));
		$this->load->view('add_prod_view', $data);
		$this->load->view('footer');
	}
	
	function prod_edit($id)
	{
		if(!is_numeric($id)||!$this->catalog_model->category_exist($id)) exit();
		
		$this->load->view('admin_header', array('title'=>'Добавление товара'));
		$this->load->view('edit_prod_view', $data);
		$this->load->view('footer');
	}
}
?>
