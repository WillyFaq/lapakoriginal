<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'menu';
	var $join = 'menu_role';
	var $join2 = 'jabatan';
	var $pk = 'id_menu';
	var $fk = 'id_role';
	var $fk2 = 'id_jabatan';

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
		$this->db->join($this->join, $this->join.'.'.$this->pk.' = '.$this->table.'.'.$this->pk);
		$this->db->join($this->join2, $this->join.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
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
	
	////////////////////////////////////////////////////////////////////////

	public function get_jabatan_role($data)
	{
		$this->db->where_not_in($this->fk2, $data);
		return $this->db->get($this->join2);
	}

	public function get_role($id)
	{
		$this->db->select('*');
		$this->db->from($this->join);
		$this->db->join($this->join2, $this->join.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->where('id_menu', $id);
		return $this->db->get();
	}

	public function add_role($data)
	{	
		$this->db->delete($this->join, array('id_menu' => $data[0]['id_menu']));
		foreach ($data as $k => $v) {
			$ret[] = $this->db->insert($this->join, $v);
		}
		if(in_array(false, $ret)){
			return false;
		}else{
			return true;
		}
		//return $this->db->insert_batch($this->join, $data);
	}

	public function cek_accesss($level, $menu)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join, $this->join.'.'.$this->pk.' = '.$this->table.'.'.$this->pk);
		$this->db->join($this->join2, $this->join.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->where(array("level" => $level, "link_menu" => $menu));
		$this->db->order_by('order_menu', 'asc');
		$q =  $this->db->get();
		if($q->num_rows()>0){
			return true;
		}else{
			return false;
		}
	}

}

/* End of file Menu_model.php */
/* Location: ./application/models/Menu_model.php */