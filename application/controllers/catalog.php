<?php

class Catalog extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->helper(array('url', 'html'));	
	}
	
	function index()
	{
		category(0);
	}
	
	function category($cat_id)
	{
		//Получаем название текущей категории
		if ($cat_id) //Берем из БД
		{
			$query = $this->db->get_where('categories', array('id' => $cat_id));
			$result = $query->row();
			$data['title'] = $result->category;
		}
		else $data['title'] = 'Каталог'; //Если id=0, то корневой каталог
		
		//Получаем содержимое категории
		$data['content'] = $this->db->get_where('categories', array('parent_id' => $cat_id))->result();
		
		//Путь к категории
		$path = array();
		while ($cat_id > 0)
		{
			$path_query = $this->db->get_where('categories', array('id' => $cat_id))->row();
			$path[$cat_id] = $path_query->category;
			$cat_id = $path_query->parent_id;
		}
		$path['0'] = 'Каталог';
		$data['path'] = array_reverse($path, true); //Инверсия массива, чтобы категории шли по порядку
		
		$this->load->view('category_view', $data);
	}
}

?>