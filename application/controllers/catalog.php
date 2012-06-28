<?php

class Catalog extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->helper(array('url', 'html'));	
		$this->load->model('catalog_model');
	}
	
	function index()
	{
		$this->category(0);
	}
	
	function category($cat_id)
	{
		// Если категория существует
		if($this->catalog_model->category_exist($cat_id))
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
			
			// Получаем содержимое категории
			$data['content'] = $this->catalog_model->get_category_content($cat_id);
			
			// Получаем путь к категории
			$data['path'] = $this->catalog_model->get_category_path($cat_id);
			
			// Вызываем представление категории
			$this->load->view('category_view', $data);
		}
		else // Если категория не существует
		{
			$data['title'] = 'Ошибка';
			$data['text'] = 'Указанная категория не существует';
			$this->load->view('invalid_category_view', $data);
		}
		
	}
}

?>