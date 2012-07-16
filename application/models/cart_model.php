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
	
}
?>