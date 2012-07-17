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
		$view_type = $this->session->userdata('admin_view_type');
		
		if($view_type == FALSE)
		{
			$this->session->set_userdata('admin_view_type', 'all');	
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
				default: $data['message'] = 'Неверный запрос'; break;
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
			$data['types'] = array('new'=>'Только новые', 'new_in_process'=>'Новые и в процессе', 'all'=>'Все заказы');
			$data['cur_type'] = $view_type;
			
			$this->load->view('admin_header', array('title'=>'Заказы'));
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
	
	function change_view_type($type)
	{
		if(!isset($type)&&!($type == 'new' || $type == 'new_in_process' || $type == 'all'))
		{
			redirect(getenv("HTTP_REFERER"));
		}
		else
		{
			$this->session->unset_userdata('admin_view_type');			
			$this->session->set_userdata('admin_view_type', $type);	
			$this->view_buyings();
		}
	}
}
?>
