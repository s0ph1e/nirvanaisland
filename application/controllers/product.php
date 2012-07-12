<?php

class Product extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->model(array('product_model', 'catalog_model', 'comment_model'));
	}
	
	function view($id)
	{
		// Если товар существует
		if($this->product_model->product_exist($id)&&isset($id))
		{
			// Получение данных
			$data = (array)$this->product_model->get_product_info($id);
			
			// Получаем путь к товару
			$data['path'] = $this->catalog_model->get_category_path($data['parent_id']);
			$data['path'][] = $data['name'];
			
			$data['comments'] = $this->comment_model->get_comments($id);
			$data['comments_count'] = count($data['comments']);
			
			// Вызываем представление товара
			$this->load->view('header', array('title'=>$data['name'],'id'=>$id));
			$this->load->view('product_view', $data);
			$this->load->view('footer');
		}
		else // Если товар не существует
		{
			$this->load->view('header', array('title'=>'Ошибка'));
			$this->load->view('error_view', array('text' => 'Указанный товар не существует.'));
			$this->load->view('footer');
		}
	}
}
?>