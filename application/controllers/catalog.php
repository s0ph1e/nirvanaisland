<?php

class Catalog extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->model(array('catalog_model','product_model'));
		$this->load->library(array('image_lib'));
	}
	
	function index()
	{
		$this->category(0);
	}
	
	function category($cat_id)
	{
		// Если категория существует
		if($this->catalog_model->category_exist($cat_id)&&isset($cat_id))
		{
			// Получение названия категории
			if ($cat_id == 0)		// Если 0 - то каталог
			{
				$data['cat_name'] = 'Каталог';
			}
			else					// Иначе получаем название из БД
			{
				$data['cat_name'] = $this->catalog_model->get_category_name($cat_id);
			}
			
			// Получаем подкатегории
			$data['subcategories'] = $this->catalog_model->get_subcategories($cat_id);
			
			// Получение товаров
			$data['items'] = $this->catalog_model->get_category_items($cat_id);
			
			// Получаем путь к категории
			$data['path'] = $this->catalog_model->get_category_path($cat_id);
			
			// ID категории
			$data['cat_id'] = $cat_id;
			
			// Вызываем представление категории
			$this->load->view('header', array('title'=>$data['cat_name']));
			$this->load->view('category_view', $data);
			$this->load->view('footer');
		}
		else // Если категория не существует
		{
			$this->load->view('header', array('title'=>'Ошибка'));
			$this->load->view('error_view', array('text' => 'Указанная категория не существует'));
			$this->load->view('footer');
		}	
	}
}
?>