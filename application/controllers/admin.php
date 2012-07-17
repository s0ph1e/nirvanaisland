<?php

class Admin extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->model(array('cart_model', 'product_model'));
		
		if(!$this->ion_auth->is_admin()) redirect(getenv("HTTP_REFERER"));
	}
	
	function index()
	{
		redirect(site_url('admin/view_buyings'));
	}
	
	function view_buyings()		// Просмотр заказов
	{
		
			$buyings = $this->cart_model->get_buyings();
			
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
			exit();
		}
	}
}
?>
