<?php

class ShopCart extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->helper(array('url', 'html', 'form', 'text'));	
		$this->load->model(array('product_model'));
		$this->load->library(array('table', 'cart'));
	}
	
	function index()
	{
		
	}
	
	// Функция добавления товара в корзину
	function add_to_cart($product_id)
	{	
	}
	

}
?>