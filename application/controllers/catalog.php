<?php

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
			
			// ID категории
			$data['cat_id'] = $cat_id;
			
			// Вызываем представление категории
			$this->load->view('category_view', $data);
		}
		else // Если категория не существует
		{
			$data['text'] = 'Указанная категория не существует';
			$this->load->view('error_view', $data);
		}	
	}
	
	function validate_upload_parameters($cat_id, $type)	// Поверка все ли параметры указаны и корректны и вызов функции в соответствии с типом загрузки
	{
		if(!isset($cat_id)||!isset($type)) // Если нет параметра
		{
			$data['text'] = 'Не указана категория для добавления товара.';
			$this->load->view('error_view', $data);
		}
		elseif (!$this->catalog_model->category_exist($cat_id))	// Если категория не существует
		{
			$data['text'] = 'Невозможно добавить товар в несуществующую категорию.';
			$this->load->view('error_view', $data);
		}	
	}
	
	function upload_csv($cat_id)		
	{
		$data['type'] = 'csv';
		$data['cat_id'] = $cat_id;
		
		$this->validate_upload_parameters($cat_id, 'csv');
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv';
		$config['max_size']	= '2048';
		
		// Передаем параметры в библиотеку загрузки
		$this->load->library('upload', $config);
	
		// Обработка ошибок загрузки
		if (!$this->upload->do_upload('csv_item'))
		{
			$data['text'] = $this->upload->display_errors();
			$this->load->view('upload_csv_view', $data);
		}	
		else
		{
			// Сообщение об успешном добавлении
			$data['text'] = 'Данные успешно загружены на сервер. ';
			$this->load->view('success_upload_view', $data);
		}
		
	}
	
	function upload_form($cat_id)
	{
		// Проверка, чтоб в адресе не было написано всякой ерунды
		$this->validate_upload_parameters($cat_id, 'form');
		
		$data['cat_id'] = $cat_id;
		
		// Параметры загружаемого файла
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '2048';
		
		// Передаем параметры в библиотеку загрузки
		$this->load->library('upload', $config);
		
		$this->load->library('form_validation');
			
		$this->form_validation->set_rules('article', 'Артикул', 'required');
		$this->form_validation->set_rules('name', 'Имя', 'required');
		$this->form_validation->set_rules('description', 'Описание', 'required');
		$this->form_validation->set_rules('price', 'Цена', 'required');
		
		if ($this->form_validation->run() == FALSE)		// Если ошибки при заполнении полей
		{
			$data['text']= validation_errors();
			$this->load->view('upload_form_view', $data);
		}
		elseif (!$this->upload->do_upload('item_image'))		// Ошибка отправления файла
		{
			$data['text']=$this->upload->display_errors();
			$this->load->view('upload_form_view', $data);
		}
		else
		{
			$image = $this->upload->data();
			$_POST['parent_id'] = $cat_id;
			$_POST['image'] = $image['full_path'];
			
			// Добавление записи в БД
			$this->catalog_model->insert_item($_POST);
			
			// Сообщение об успешном добавлении
			$data['type'] = 'form';
			$data['cat_id'] = $cat_id;
			$data['text'] = 'Данные успешно загружены на сервер. ';
			$this->load->view('success_upload_view', $data);
		}
	}
	
}

?>