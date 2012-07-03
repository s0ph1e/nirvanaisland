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
			$data['items'] = $this->catalog_model->get_category_items($cat_id);
			
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
	
	// Поверка все ли параметры указаны и корректны и вызов функции в соответствии с типом загрузки
	function validate_parameters($cat_id)	
	{
		if(!isset($cat_id)) // Если нет параметра
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
		// Проверка, чтоб в адресе не было написано всякой ерунды
		$this->validate_parameters($cat_id);
		
		// Данные для передачи во вьюшку
		$data['type'] = 'csv';
		$data['cat_id'] = $cat_id;
		
		// Параметры загружаемого файла
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv';
		$config['max_size']	= '2048';
		
		// Передаем параметры в библиотеку загрузки
		$this->load->library('upload', $config);
	
		// Если форма была отправлена
		if(isset($_POST['submit_csv']))				
		{
			if (!$this->upload->do_upload('csv_item'))	// Если что-то не так с файлом
			{
				$data['text'] = $this->upload->display_errors();
				$this->load->view('upload_csv_view', $data);	// Показываем ошибку перед формой
			}	
			else		// Если с файлом все ок
			{
				$data['text'] = 'Данные успешно загружены на сервер. ';
				$this->load->view('success_upload_view', $data);
			}
		}
		else		
		{
			$this->load->view('upload_csv_view', $data);
		}
	}
	
	function upload_form($cat_id)		
	{
		// Проверка, чтоб в адресе не было написано всякой ерунды
		$this->validate_parameters($cat_id);
		
		// Данные для передачи во вьюшку
		$data['cat_id'] = $cat_id;
		
		// Параметры загружаемого файла
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '2048';
		
		// Передаем параметры в библиотеку загрузки
		$this->load->library('upload', $config);
		
		// Библиотека валидации форм
		$this->load->library('form_validation');
		
		// Установка правил валидации для полей
		$this->form_validation->set_rules('article', 'Артикул', 'required|min_length[5]|max_length[8]');
		$this->form_validation->set_rules('name', 'Имя', 'required|min_length[5]|max_length[32]');
		$this->form_validation->set_rules('description', 'Описание', 'required');
		$this->form_validation->set_rules('price', 'Цена', 'required|decimal|greater_than[0]');
		
		// Если отправлена форма
		if(isset($_POST['submit_form']))		
		{
			$success_upload = $this->upload->do_upload('item_image'); // Загружен ли файл
			
			// Если все хорошо и с полями, и с файлом
			if($this->form_validation->run()&&$success_upload)
			{
				// Подготовка данных для передачи в модель
				$image = $this->upload->data();			// Загруженный файл
				$_POST['image'] = $image['full_path'];	// Полный путь к файлу
				$_POST['parent_id'] = $cat_id;			// Категория товара
				
				// Добавление записи в БД
				$this->catalog_model->insert_item($_POST);
				
				// Сообщение об успешном добавлении
				$data['type'] = 'form';
				$data['cat_id'] = $cat_id;
				$data['text'] = 'Данные успешно загружены на сервер. ';
				$this->load->view('success_upload_view', $data);
			}
			else 	// Если что-то не так с полями или загрузкой файла
			{
				$data['text'] .= validation_errors();
				$data['text'].=$this->upload->display_errors();
				$this->load->view('upload_form_view', $data);
			}
		}
		else
		{
			$this->load->view('upload_form_view', $data);
		}	
	}

	function add_category($cat_id)
	{
		// Проверка категории
		$this->validate_parameters($cat_id);
		
		// Данные для передачи во вьюшку
		$data['cat_id'] = $cat_id;
		
		// Библиотека валидации форм
		$this->load->library('form_validation');
		
		// Установка правил валидации
		$this->form_validation->set_rules('category', 'Категория', 'required|min_length[5]|max_length[32]');
		
		// Если отправлена форма
		if(isset($_POST['submit_form']))		
		{
			// Если все хорошо
			if($this->form_validation->run())
			{
				// Подготовка данных для передачи в модель
				$_POST['parent_id'] = $cat_id;			// Категория товара
				
				// Добавление записи в БД
				$this->catalog_model->insert_category($_POST);
				
				// Сообщение об успешном добавлении
				$data['text'] = 'Данные успешно загружены на сервер. ';
				$this->load->view('success_upload_view', $data);
			}
			else 	// Если что-то не так с полем
			{
				$data['text'] .= validation_errors();
				$this->load->view('add_category_view', $data);
			}
		}
		else
		{
			$this->load->view('add_category_view', $data);
		}	
	}
}
?>