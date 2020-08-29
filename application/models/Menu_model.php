<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'menu';
	var $pk = 'id_menu';

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->order_by('order_menu', 'asc');
		return $this->db->get();
	}

	public function get_menu()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('sts_menu', 1);
		$this->db->order_by('order_menu', 'asc');
		return $this->db->get();
	}

	public function get_data($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where(array($this->table.".".$this->pk => $id));
		$this->db->order_by('order_menu', 'asc');
		return $this->db->get();
	}

	public function get_where($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($id);
		$this->db->order_by('order_menu', 'asc');
		return $this->db->get();
	}

	public function get_parent()
	{
		$this->db->distinct();
		$this->db->select('parent_menu');
		$this->db->from($this->table);
		$q = $this->db->get();
		return $q->result();
	}

	public function add($da)
	{
		return $this->db->insert($this->table, $da);
	}

	public function update($data, $_id)
	{
		$this->db->set($data);
		$this->db->where($this->pk, $_id);
		return $this->db->update($this->table);
	}

	public function delete($id)
	{
		return $this->db->delete($this->table, array($this->pk => $id));
	}
	

}

/* End of file Menu_model.php */
/* Location: ./application/models/Menu_model.php */