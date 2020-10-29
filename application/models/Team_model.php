<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'sales_team';
	var $join1 = 'barang';
	var $pk = 'id_sales_team';
	var $fk1 = 'kode_barang';

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, "$this->table.$this->fk1 = $this->join1.$this->fk1");
		return $this->db->get();
	}

	public function get_user($id)
	{
		$this->db->where('id_user', $id);
		$q = $this->db->get('user');
		$res = $q->result();
		foreach ($res as $row) {
			return $row->nama;
		}
	}

	public function get_data($id)
	{	
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, "$this->table.$this->fk1 = $this->join1.$this->fk1");
		$this->db->where(array($this->table.'.'.$this->pk => $id));
		return $this->db->get();
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

/* End of file Team_model.php */
/* Location: ./application/models/Team_model.php */