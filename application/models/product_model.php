<?php

class Product_model extends CI_Model{

	public function __construct() 
    { 
        parent::__construct();
	}
	
	function product_exist($id)  // Проверка существования продукта
	{
		$query = $this->db->get_where('items', array('id' => $id));
		if ($query->num_rows() > 0 || $cat_id == 0)
		{
			return true;
		}
		else return false;
	}
	
	function get_product_info($id)	// Информация о товаре
	{
		$query = $this->db->get_where('items', array('id' => $id));
		return $query->row();
	}
	
	function insert_product($data)	// Добавление продукта
	{
		$this->db->insert('items', $data);
	}
	
}
	
?>