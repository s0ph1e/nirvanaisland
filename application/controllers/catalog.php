﻿<?php

class Catalog extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->helper(array('url', 'html', 'form'));	
		$this->load->model('catalog_model');
		$this->load->library('csvreader');
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
			$data['text'] = 'Указанная категория не существует';
			$this->load->view('error_view', $data);
		}	
	}
	
	function add_item($cat_id, $type)	// Поверка все ли параметры указаны и корректны
	{
		if(!isset($cat_id) || !isset($type)) // Если нет какого-либо параметра
		{
			$data['text'] = 'Не все параметры добавления товара указаны.';
			$this->load->view('error_view', $data);
		}
		elseif (!$this->catalog_model->category_exist($cat_id))	// Если категория не существует
		{
			$data['text'] = 'Невозможно добавить товар в несуществующую категорию.';
			$this->load->view('error_view', $data);
		}
		else 		// Если все ок
		{
			$data['type'] = $type;
			$data['cat_id'] = $cat_id;
			$this->load->view('add_item_view', $data);
		}	
	}
	
	function upload_csv($cat_id)		
	{
		
	}
	
	function upload_form($cat_id)
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		
		$config['max_size']	= '2048';
		
		// Передаем параметры в библиотеку загрузки
		$this->load->library('upload', $config);
	
		// Обработка ошибок загрузки
		if (!$this->upload->do_upload('item_image'))
		{
			$data['text'] = $this->upload->display_errors();
			$this->load->view('error_view', $data);
		}	
		else
		{
			//$data[] = array('upload_data' => $this->upload->data());
			$data['type'] = 'form';
			$data['cat_id'] = $cat_id;
			$data['text'] = 'Данные успешно загружены на сервер. ';
			$this->load->view('success_upload_view', $data);
		}
	}
	
}

?>