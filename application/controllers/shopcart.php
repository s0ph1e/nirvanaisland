<?php

class ShopCart extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->model(array('product_model', 'cart_model'));
		$this->load->library('form_validation');
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
			if($value == 0) $this->delete($key);	// Если количество 0, то удаляем товар
			
			$product = $this->product_model->get_product_info($key);
			$data['cart'][]=array(  'name'=>anchor('product/view/'.$key, $product->name),
									'qty'=>'<input type="text" maxlength="3" class="qty_text" id='.$key.' value ='.$value.' onkeyup="this.value = this.value.replace (/\D/, \'\')">',
									'price'=>$product->price.' грн.',
									'total_price'=>'<span id="total_'.$key.'">'.$value*$product->price.' грн.</span>',
									'actions'=>'<center>'.anchor(site_url('shopcart/update/'.$key), img('data/images/ok.png'), array('id'=>$key, 'class'=>"cart_ok", 'title'=>"Изменить")).'&nbsp'.anchor(site_url('shopcart/delete/'.$key), img('data/images/delete.png'), 'title="Удалить"')
								);
		}
		$data['qty'] = $this->cart_model->get_total_count();
		$data['total'] = $this->cart_model->get_total_price();
		
		$this->load->view('header', array('title'=>'Корзина'));
		$this->load->view('cart_view', $data);
		$this->load->view('footer');
	}
	
	function update($id, $new_count)
	{
		$cart = $this->session->userdata('cart');
		if(!is_numeric($new_count)) exit();		// Если новое значение не число
		else 									// Если новое значение число
		{
			$cart[$id] = $new_count;
			$this->session->set_userdata('cart', $cart);
			$product = $this->product_model->get_product_info($id);
			$all_qty = $this->cart_model->get_total_count();
			$all_price = $this->cart_model->get_total_price();
			
			exit(json_encode(array('id'=>$id,'total_price'=>$product->price*$new_count.' грн.', 'all_qty' =>$all_qty, 'all_price'=>$all_price)));
		}
	}
	
	function delete($id)
	{
		$cart = $this->session->userdata('cart');		// Получение корзины из сессии
		unset($cart[$id]);								// Удаление выбранного товара
		$this->session->unset_userdata('cart');			// Удаление корзины из сессии
		$this->session->set_userdata('cart', $cart);	// Запись обновленной корзины в сессию
		redirect(site_url('shopcart'));
	}

	function order()
	{
		// Если корзина пуста, то редирект
		if(!$this->session->userdata('cart')) redirect(site_url('shopcart'));
		
		// Правила валидации
		$this->form_validation->set_rules('addres', 'Адрес', 'required');
		$this->form_validation->set_rules('phone', 'Телефон', 'required|is_numeric');
		
		if(isset($_POST['order_submit'])&&$this->form_validation->run())
		{
			$orders['user_id'] = $this->ion_auth->user()->row()->id;
			$orders['addres'] = mb_strtolower($this->input->post('addres'));
			$orders['phone'] = mb_strtolower($this->input->post('phone'));	
			
			// Создаем записи в таблице заказов (пользователь, адрес, тел) и в таблице заказанных товаров (id заказа, товар, колво)
			$this->cart_model->create_buying($orders);
			$this->session->unset_userdata('cart');			// Удаление корзины из сессии
			
			$this->load->view('header', array('title'=>'Заказ отправлен'));
			$this->load->view('message_view', array('text'=>'Ваш заказ отправлен.<br> Администратор свяжется с Вами в ближайшее время.'));
			$this->load->view('footer');
		}
		else 
		{
			$data['message'] = validation_errors();
			$data['addres'] = $this->form_validation->set_value('addres');
			$data['phone'] = $this->form_validation->set_value('phone');
			
			
			$data['title'] = "Заказ товара";
			$this->load->view('header', $data);
			$this->load->view('order_view');
			$this->load->view('footer');
		}
		
		
	}
}
?>