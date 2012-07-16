<?php

class Admin extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->model(array('cart_model', 'product_model'));
	}
	
	function index()
	{
		$this->view_buyings('all');
	}
	
	function view_buyings($view_type)		// Просмотр заказов
	{
		if(!isset($view_type))
		{
			redirect(getenv("HTTP_REFERER"));
		}
		else
		{
			switch($view_type)			// В зависимости от типа просмотра выбираем только нужные записи
			{
				case 'new': $buyings = $this->cart_model->get_buyings(1);
							break;
				case 'new_in_process': $buyings = $this->cart_model->get_buyings(2);
							break;
				case 'all': $buyings = $this->cart_model->get_buyings(3);
							break;
				default: $data['message'] = 'Неверный запрос';
			}
			
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
				$info['status'] = $this->cart_model->get_status_name($row->status_id);
				
				// Изменение статуса
				$info['link'] = '<center>'.anchor(site_url('admin/change_status/'.$row->id), img('data/images/upd.png'), array('class'=>"cart_upd", 'title'=>"Следующее состояние"));
				
				// Массив для передачи в представление
				$data['buyings'][] = $info;
			}
			
			$this->load->view('header', array('title'=>'Заказы'));
			$this->load->view('admin_buyings_view', $data);
			$this->load->view('footer');
		}
	}
	
	function change_status($id)
	{
		if(!isset($id)||!$this->cart_model->buying_exist($id))
		{
			redirect(getenv("HTTP_REFERER"));
		}
		else
		{
			$this->cart_model->update_buying($id);
			$this->view_buyings();
		}
	}
}
?>
