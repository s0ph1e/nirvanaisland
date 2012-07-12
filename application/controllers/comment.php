<?php

class Comment extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->model(array('comment_model'));
		$this->load->library('form_validation');
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
		  $comment_id = $this->comment_model->insert_comment($data);
		  $comment = $this->comment_model->get_comment($comment_id);
		  
		  $html = '<div class="comment"><div class="comment_top">'.$comment->name.'</div><div class="comment_date">'.date('j M y G:i:s', strtotime($comment->datetime)).'</div>';
		  $html.= '<p class="comment_message">'.$comment->comment.'</p></div>';
		  exit(json_encode(array('response'=>1, 'html'=>$html)));
		}
		else
		{ 
			$error = '<p class="error">'.validation_errors().'</p>';
			exit(json_encode(array('response'=>0, 'html'=>$error)));
		}
	}
	
	function get_comment($content_id)
	{
		$data['comments'] = $this->comment_model->get_comments($content_id);
		$data['comments_count'] = count($data['comments']);
		
	}
	
}