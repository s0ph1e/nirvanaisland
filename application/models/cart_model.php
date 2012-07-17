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
		
		$buying_id = $this->db->insert_id();		// ID вставленной записи
		
		$cart = $this->session->userdata('cart');	// Получение корзины из сессии
		
		foreach($cart as $key => $value)			// Добавление всех товаров в заказ
		{
			$this->db->insert('buying_items', array('buying_id'=>$buying_id, 'product_id'=>$key, 'qty'=>$value));
		}
	}
	
	function get_buyings()
	{
		//$this->db->where('status_id <=',$status_code);
		$query = $this->db->get('buyings');
		
		return $query->result();
	}
	
	function get_statuses()
	{
		$query = $this->db->get('buying_statuses');
		
		return $query->result();
	}
	
	function get_buying_items($buying_id)
	{
		$query = $this->db->get_where('buying_items', array('buying_id'=>$buying_id));
		
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
	
	function update_buying($id, $new_status)
	{
		$this->db->where('id', $id);
		$this->db->update('buyings', array('status_id'=>$new_status));
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
	
	function status_exist($id)
	{
		$query = $this->db->get_where('buying_statuses', array('id' => $id));
		if ($query->num_rows() > 0)
		{
			return true;
		}
		else return false;
	}
	
}
?>