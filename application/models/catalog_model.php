<?phpclass Catalog_model extends CI_Model{	public function __construct()     {         parent::__construct();	}		function category_exist($cat_id)  // Проверка существования категории	{		$query = $this->db->get_where('categories', array('id' => $cat_id));		if ($query->num_rows() > 0 || $cat_id == 0) 		{			return true;		}		else return false;	}		function get_category_name($cat_id)		// Получение названия категории	{		$query = $this->db->get_where('categories', array('id' => $cat_id));		return $query->row()->category;	}		function get_subcategories($cat_id)		// Получение подкатегорий	{		$query = $this->db->get_where('categories', array('parent_id' => $cat_id));		return $query->result();	}		function get_category_items($cat_id)	// Получение объектов категории	{		$query = $this->db->get_where('items', array('parent_id' => $cat_id));		return $query->result();	}		function get_category_path($cat_id)		// Получение пути к категории	{		$path = array();		while ($cat_id > 0)		{			$query = $this->db->get_where('categories', array('id' => $cat_id))->row();			$path[$cat_id] = $query->category;			$cat_id = $query->parent_id;		}		$path['0'] = 'Каталог';				 //Первый сегмент пути всегда корневой каталог		$path = array_reverse($path, true);  //Инверсия массива, чтобы категории шли по порядку		return $path;	}		function insert_category($data)	{		$this->db->insert('categories', $data);	}		function delete_category($id)	{		$this->db->delete('categories', array('id' => $id)); 		foreach($this->get_subcategories($id) as $child)		{			$this->delete_category($child->id);		}	}}?>