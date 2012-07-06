<?php

class Product extends CI_Controller{
	
	public function __construct() 
    { 
        parent::__construct();
		$this->load->helper(array('url', 'html', 'form', 'text'));	
		$this->load->model(array('product_model', 'catalog_model'));
	}
	
	function view($id)
	{
		// ���� ����� ����������
		if($this->product_model->product_exist($id))
		{
			// ��������� ������
			$data = (array)$this->product_model->get_product_info($id);
			
			// �������� ���� � ������
			$data['path'] = $this->catalog_model->get_category_path($data['parent_id']);
			$data['path'][] = $data['name'];
			
			// �������� ������������� ������
			$this->load->view('product_view', $data);
		}
		else // ���� ����� �� ����������
		{
			$data['text'] = '��������� ����� �� ����������';
			$this->load->view('error_view', $data);
		}
	}		
}
?>
