<?php

class Comment_model extends CI_Model{

	public function __construct() 
    { 
        parent::__construct();
	}
	
	function insert_comment($data)
	{
		$this->db->insert('comments', $data);
	}
	
	function get_comments($id)
	{
		$query = $this->db->get_where('comments', array('content_id' => $id));
		return $query->result();
	}
}
?>