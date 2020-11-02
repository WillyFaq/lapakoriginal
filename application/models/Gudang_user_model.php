<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang_user_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'gudang_user';
	var $join1 = 'gudang';
	var $join2 = 'user';
	var $join3 = 'jabatan';
	var $pk = 'id_gudang_user';
	var $fk1 = 'id_gudang';
	var $fk2 = 'id_user';
	var $fk3 = 'id_jabatan';

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, "$this->join1.$this->fk1 = $this->table.$this->fk1");
		$this->db->join($this->join2, "$this->join2.$this->fk2 = $this->table.$this->fk2");
		$this->db->join($this->join3, "$this->join2.$this->fk3 = $this->join3.$this->fk3");
		$this->db->where('jabatan.level !=', 1);
		return $this->db->get();
	}

	public function get_data($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, "$this->join1.$this->fk1 = $this->table.$this->fk1");
		$this->db->join($this->join2, "$this->join2.$this->fk2 = $this->table.$this->fk2");
		$this->db->where(array($this->table.'.'.$this->pk => $id));
		return $this->db->get();
	}

	public function get_where($id)
	{			
		$this->db->where($id);
		return $this->db->get($this->table);
	}

	public function get_by_user($id)
	{			
		$this->db->where(array($this->fk2 => $id));
		return $this->db->get($this->table);
	}

	public function add($da)
	{
		$id_gudang = $da['id_gudang'];
		unset($da['id_gudang']);
		unset($da['gudang']);
		$this->db->trans_begin();

		$this->db->insert('user', $da);
		$id_user = $this->db->insert_id();
		$this->db->insert($this->table, array("id_gudang" => $id_gudang, "id_user" => $id_user, 'sts_gudang_user' => 1));

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}
		//return $this->db->insert($this->table, $da);
	}

	public function update($id_gudang_user, $id_user, $da_user, $da_gudang)
	{

		$this->db->trans_begin();
		$this->db->where('id_user', $id_user);
		$this->db->update('user', $da_user);

		$da = array("id_user" => $id_user, "id_gudang" => $da_gudang);
		$this->db->where($this->pk, $id_gudang_user);
		$this->db->update($this->table, $da);
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}
	}

	public function delete($id)
	{
		return $this->db->delete($this->table, array($this->pk => $id));
	}
}

/* End of file Gudang_user_model.php */
/* Location: ./application/models/Gudang_user_model.php */