<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'user';
	var $join = 'jabatan';
	var $pk = 'id_user';
	var $fk = 'id_jabatan';

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->fk.' = '.$this->join.'.'.$this->fk);
		return $this->db->get();
	}

	public function get_all_jabatan()
	{
		return $this->db->get($this->join);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////

	public function get_data_jabatan($id)
	{
		$this->db->where(array($this->join.".".$this->fk => $id));
		return $this->db->get($this->join);
	}

	public function get_data($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->fk.' = '.$this->join.'.'.$this->fk);
		$this->db->where(array($this->table.".".$this->pk => $id));
		return $this->db->get();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////

	public function get_where_jabatan($id)
	{
		$this->db->where($id);
		return $this->db->get($this->join);
	}

	public function get_where($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->fk.' = '.$this->join.'.'.$this->fk);
		$this->db->where($id);
		return $this->db->get();
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

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */