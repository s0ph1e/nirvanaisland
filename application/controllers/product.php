<?php

class Product extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->helper(array('url', 'html', 'form', 'text'));	
		$this->load->model(array('product_model', 'catalog_model'));
	}
	
	function view($id)
	{
		// Если товар существует
		if($this->product_model->product_exist($id))
		{
			// Получение данных
			$data = (array)$this->product_model->get_product_info($id);
			
			// Получаем путь к товару
			$data['path'] = $this->catalog_model->get_category_path($data['parent_id']);
			$data['path'][] = $data['name'];
			
			// Вызываем представление товара
			$this->load->view('product_view', $data);
		}
		else // Если товар не существует
		{
			$data['text'] = 'Указанный товар не существует';
			$this->load->view('error_view', $data);
		}
	}		
}
?>
