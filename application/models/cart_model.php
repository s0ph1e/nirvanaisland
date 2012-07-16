<?php

class Cart_model extends CI_Model{

	public function __construct() 
    { 
        parent::__construct();
	}
	
	function get_total_count()
	{
		$total_count = 0;
		
		$cart = $this->session->userdata('cart');
		
		foreach($cart as $key => $value)
		{
			$total_count += $value;
		}
		
		return $total_count;
	}
	
	function get_total_price()
	{
		$total_price = 0;
		
		$cart = $this->session->userdata('cart');
		
		foreach($cart as $key => $value)
		{
			$product = $this->product_model->get_product_info($key);
			$total_price += $value*$product->price;
		}
		
		return $total_price;
	}
	
	function create_buying($data)
	{
		$this->db->insert('buyings', $data); 	// Данные заказа - ID юзера, адрес, телефон
		
		$order_id = $this->db->insert_id();		// ID вставленной записи
		
		$cart = $this->session->userdata('cart');	// Получение корзины из сессии
		
		foreach($cart as $key => $value)			// Добавление всех товаров в заказ
		{
			$this->db->insert('buying_items', array('order_id'=>$order_id, 'product_id'=>$key, 'qty'=>$value));
		}
	}
	
	function get_buyings($status_code)
	{
		$this->db->where('status_id <=',$status_code);
		$query = $this->db->get('buyings');
		
		return $query->result();
	}
	
	function get_buying_items($buying_id)
	{
		$query = $this->db->get_where('buying_items', array('order_id'=>$buying_id));
		
		return $query->result();
	}
	
	function get_status_name($id)
	{
		$query = $this->db->get_where('buying_statuses', array('id'=>$id));
		
		return $query->row()->status;
	}
	
	function get_status_id($name)
	{
		$query = $this->db->get_where('buying_statuses', array('name'=>$name));
		
		return $query->row()->id;
	}
	
	function update_buying($id)
	{
		// Получаем статус заказ
		$status_id = (int)$this->db->get_where('buyings', array('id'=>$id))->row()->status_id;
		
		// Выбираем из таблицы следующий статус
		$this->db->where('id >', $status_id);
		$next_status = $this->db->get('buying_statuses', 1,0)->row()->id;
		
		// Обновляем запись в таблице заказов
		if($next_status)
		{
			$this->db->where('id', $id);
			$this->db->update('buyings', array('status_id'=>$next_status));
			return true;
		}
		else return false;
	}
	
	function buying_exist($id)
	{
		$query = $this->db->get_where('buyings', array('id' => $id));
		if ($query->num_rows() > 0)
		{
			return true;
		}
		else return false;
	}
	
}
?>