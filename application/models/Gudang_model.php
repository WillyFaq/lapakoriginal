<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'gudang';
	var $join = 'beban';
	var $pk = 'id_gudang';

	public function get_all()
	{
		return $this->db->get($this->table);
	}

	public function get_data($id)
	{
		$this->db->where(array($this->table.'.'.$this->pk => $id));
		return $this->db->get($this->table);
	}

	public function get_where($id)
	{			
		$this->db->where($id);
		return $this->db->get($this->table);
	}

	public function add($da)
	{
		return $this->db->insert($this->table, $da);
	}

	public function update($da, $_id)
	{
		$this->db->where($this->pk, $_id);
		return $this->db->update($this->table, $da);
	}

	public function delete($id)
	{
		return $this->db->delete($this->table, array($this->pk => $id));
	}

}

/* End of file Gudang_model.php */
/* Location: ./application/models/Gudang_model.php */