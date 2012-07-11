<?php

class Comment extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->helper(array('url', 'html', 'form', 'text'));	
		$this->load->model(array('comment_model'));
		$this->load->library('form_validation');
		$this->load->library('ion_auth');
	}
	
	function add_comment($id)
	{
		//Правила валидации
		$this->form_validation->set_rules('comment', 'Отзыв', 'required');

		if ($this->form_validation->run() == true)
		{
		  if($this->ion_auth->logged_in())
		  {
			$data['name'] = $this->ion_auth->user()->row()->username;
		  } else {
			$data['name'] = 'Гость';
		  }
		  $data['comment'] = $this->input->post('comment'); 
		  $data['content_id'] = $id;
		  $this->comment_model->insert_comment($data);
		  
		  //exit(json_encode(array('response'=>'ОК')));
		  redirect(getenv("HTTP_REFERER"));
		}
		else
		{ 
			//exit(json_encode(array('response'=>'ERROR', 'additional'=>validation_errors())));
			redirect(getenv("HTTP_REFERER").'?error=1');
		}
	}
	
	function get_comment($content_id)
	{
		$data['comments'] = $this->comment_model->get_comments($content_id);
		$data['comments_count'] = count($data['comments']);
		
	}
	
}