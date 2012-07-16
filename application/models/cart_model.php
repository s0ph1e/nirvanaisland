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
	
	function create_order($data)
	{
		$this->db->insert('orders', $data); 	// ������ ������ - ID �����, �����, �������
		
		$order_id = $this->db->insert_id();		// ID ����������� ������
		
		$cart = $this->session->userdata('cart');	// ��������� ������� �� ������
		
		foreach($cart as $key => $value)			// ���������� ���� ������� � �����
		{
			$this->db->insert('ordered_items', array('order_id'=>$order_id, 'product_id'=>$key, 'qty'=>$value));
		}
	}
}
?>