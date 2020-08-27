<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'barang';
	var $join = 'beban';
	var $pk = 'kode_barang';

	public function get_all()
	{
		$this->db->select($this->table.".*, SUM(".$this->join.".nominal) AS 'beban'");
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->pk.' = '.$this->join.'.'.$this->pk);
		$this->db->group_by($this->table.'.'.$this->pk);
		return $this->db->get();
	}

	public function get_data($id)
	{
		$this->db->select($this->table.".*, SUM(".$this->join.".nominal) AS 'beban'");
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->pk.' = '.$this->join.'.'.$this->pk);
		$this->db->where(array($this->table.'.'.$this->pk => $id));
		$this->db->group_by($this->table.'.'.$this->pk);
		return $this->db->get();
	}

	public function get_where($id)
	{			
		$this->db->select($this->table.".*, SUM(".$this->join.".nominal) AS 'beban'");
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->pk.' = '.$this->join.'.'.$this->pk);
		$this->db->where($id);
		$this->db->group_by($this->table.'.'.$this->pk);
		return $this->db->get();
	}

	public function get_update($id)
	{
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->pk.' = '.$this->join.'.'.$this->pk);
		$this->db->where(array($this->table.'.'.$this->pk => $id));
		return $this->db->get();
	}

	public function add($da)
	{
		$beban = [];
		foreach ($da['nama_beban'] as $k => $v) {
			$beban[] = array(
							'kode_barang' => $da['kode_barang'],
							'nama_beban' => $v,
							'nominal' => $da['nominal'][$k],	
							);
		}
		unset($da['nama_beban'], $da['nominal'], $da['btnSimpan']);
		$barang = $da;
		
		$this->db->trans_begin();
		$this->db->insert($this->table, $barang);
		$this->db->insert_batch($this->join, $beban);
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}
	}

	public function update($da, $_id)
	{
		$this->db->trans_begin();
		$beban = [];
		foreach ($da['nama_beban'] as $k => $v) {
			$beban[] = array(
							'kode_barang' => $_id,
							'nama_beban' => $v,
							'nominal' => $da['nominal'][$k],	
							);
		}
		unset($da['id_beban'], $da['nama_beban'], $da['nominal'], $da['btnSimpan']);
		$barang = $da;
		$this->db->delete($this->join, array($this->pk => $_id));
		$this->db->insert_batch($this->join, $beban);
		
		$this->db->set($barang);
		$this->db->where($this->pk, $_id);
		$this->db->update($this->table);
		
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

/* End of file Barang_model.php */
/* Location: ./application/models/Barang_model.php */