<?php

class ShopCart extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->model(array('product_model'));
	}
	
	function index()
	{
		$this->view();
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
			$data['cart'][]=array(  'name'=>anchor('product/view/'.$key, $product->name),
									'qty'=>'<input type="text" maxlength="3" class="qty_text" id='.$key.' value ='.$value.'>',
									'price'=>$product->price.' грн.',
									'total_price'=>'<span id="total_'.$key.'">'.$value*$product->price.' грн.</span>',
									'actions'=>'<center>'.anchor('shopcart/update/'.$key, img('data/images/ok.png'), array('id'=>$key, 'class'=>"cart_ok", 'title'=>"Изменить")).'&nbsp'.anchor('#', img('data/images/delete.png'), 'title="Удалить"')
								);
			//$this->output->append_output('Наименование: '.$product->name.' | Количество: '.$value.'Общая цена:'.$value*$product->price.'<br>');	
		}
		
		$this->load->view('header', array('title'=>'Корзина'));
		$this->load->view('cart_view', $data);
		$this->load->view('footer');
	}
	
	function update($id, $new_count)
	{
		$cart = $this->session->userdata('cart');
		$cart[$id] = $new_count;
		$this->session->set_userdata('cart', $cart);
		$product = $this->product_model->get_product_info($id);
		exit(json_encode(array('id'=>$id,'total_price'=>$product->price*$new_count.' грн.')));
		//$this->view();
	}
	

}
?>