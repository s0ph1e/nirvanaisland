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
	function add($product_id)
	{
		$cart = $this->session->userdata('cart');
		//$this->output->append_output(print_r($data), TRUE);
		
		// Если корзины не существует - создаем
		if ($cart === FALSE)
			$cart = array();
			
		$cart[$product_id]++;
		$this->session->set_userdata('cart', $cart);
	}
	
	function view()
	{
		$cart = $this->session->userdata('cart');
		
		foreach($cart as $key => $value)
		{
			$product = $this->product_model->get_product_info($key);
			$this->output->append_output('Наименование: '.$product->name.' | Количество: '.$value.'Общая цена:'.$value*$product->price.'<br>');
			
		}
	}
	

}
?>