<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	//redirect if needed, otherwise display the user list
	function index()
	{
		redirect(site_url(), 'refresh');
	}

	//log the user in
	function login()
	{
		//Правила валидации
		$this->form_validation->set_rules('identity', 'E-mail', 'required');
		$this->form_validation->set_rules('password', 'Пароль', 'required');

		if ($this->form_validation->run() == true)
		{ 
			//Значение "запомнить меня"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{ //Если вход успешен возвращаем ОК
				exit(json_encode(array('response'=>'OK')));
			}
			else
			{ // Если нет - вернуть ошибки
				exit(json_encode(array('response'=>'ERROR', 'additional'=>$this->ion_auth->errors())));
			}
		}
		else
		{ 
			exit(json_encode(array('response'=>'ERROR', 'additional'=>validation_errors())));
		}
	}
	
	//log the user out
	function logout()
	{
		//log the user out
		$logout = $this->ion_auth->logout();

		//redirect them back to the page they came from
		if ($_GET['ajax'] == 'true')
			exit('OK');
		else
			redirect(getenv("HTTP_REFERER"));
	}
	
	//create a new user
	function registration()
	{
		$this->data['title'] = "Регистрация";

		// Если пользователь авторизирован
		if ($this->ion_auth->logged_in())	
		{
			// То отправляем его обратно
			redirect(getenv("HTTP_REFERER"));
		}

		//validate form input
		$this->form_validation->set_rules('first_name', 'Имя', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Фамилия', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Пароль', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Подтверждение пароля', 'required');
		
		if ($this->form_validation->run() == true)
		{
			$username = mb_strtolower($this->input->post('first_name')) . ' ' . mb_strtolower($this->input->post('last_name'));
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array('first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'));
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
		{ //check to see if we are creating the user
			//redirect them back to the admin page
			$this->load->view('header', array('title'=>"Регистрация прошла успешно"));
			$this->load->view('message_view', array('text'=>"Вы успешно зарегистрировались. Теперь можете войти на сайт."));
			$this->load->view('footer');
		}
		else
		{ //display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array('name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			
			$this->data['last_name'] = array('name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array('name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);
			$this->load->view('header', array('title'=>"Регистрация"));
			$this->load->view('registration_view', $this->data);
			$this->load->view('footer');
		}
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
				$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}
