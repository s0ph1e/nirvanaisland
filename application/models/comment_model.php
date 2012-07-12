<?php

class Comment_model extends CI_Model{

	public function __construct() 
    { 
        parent::__construct();
	}
	
	function insert_comment($data)
	{
		$this->db->insert('comments', $data);
		return $this->db->insert_id();
	}
	
	function get_comment($id)
	{
		$query = $this->db->get_where('comments', array('id' => $id));
		return $query->row();
	}
	
	function get_comments($id)
	{
		$query = $this->db->get_where('comments', array('content_id' => $id));
		return $query->result();
	}
}
?>