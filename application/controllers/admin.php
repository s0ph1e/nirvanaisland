<?php

class Admin extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->model(array('cart_model', 'product_model', 'catalog_model'));
		
		if(!$this->ion_auth->is_admin()) redirect(site_url());
	}
	
	function index()
	{
		redirect(site_url('admin/view_buyings'));
	}
	
	function view_buyings($type)		// Просмотр заказов
	{
		if(!isset($type)||!$this->cart_model->status_exist($type)) $type = 3;
	
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
		if(!isset($id)||!$this->cart_model->buying_exist($id)||!isset($new_status)||!$this->cart_model->status_exist($new_status))
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
		if(!isset($cat_id)||!$this->catalog_model->category_exist($cat_id)) $cat_id=0;
		
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
		if(!isset($id)||!$this->catalog_model->category_exist($id)) exit();
		
		$cat_name = $this->input->post('cat_name');
		if($cat_name) 
		{
			$data['parent_id'] = $id;
			$data['category'] = $cat_name;
			$this->catalog_model->insert_category($data);
			redirect(getenv("HTTP_REFERER"));
		}
	}
}
?>
