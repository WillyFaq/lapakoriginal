<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'user';
	var $join = 'sales_barang';
	var $join2 = 'barang';
	var $pk = 'id_user';
	var $fk = 'id_user';
	var $fk2 = 'kode_barang';

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->fk.' = '.$this->join.'.'.$this->fk);
		$this->db->join($this->join2, $this->join.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		return $this->db->get();
	}

	public function get_data($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->fk.' = '.$this->join.'.'.$this->fk);
		$this->db->join($this->join2, $this->join.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->where(array($this->table.".".$this->pk => $id));
		return $this->db->get();
	}

	public function get_where($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join, $this->table.'.'.$this->fk.' = '.$this->join.'.'.$this->fk);
		$this->db->join($this->join2, $this->join.'.'.$this->fk2.' = '.$this->join2.'.'.$this->fk2);
		$this->db->where($id);
		return $this->db->get();
	}

	public function add($da)
	{
		$kode_barang = $da['kode_barang'];
		$minimal = $da['minimal_sale'];
		unset($da['id_sales_barang']);
		unset($da['kode_barang']);
		unset($da['minimal_sale']);

		$this->db->trans_begin();
		
		$this->db->insert($this->table, $da);
		$id_user = $this->db->insert_id();
		foreach ($kode_barang as $k => $v) {
			$dada = array(
						'id_user' => $id_user,
						'kode_barang' => $v,
						'minimal_sale' => $minimal[$k]
						);
			$this->db->insert($this->join, $dada);
		}

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}
	}

	public function update($data, $_id)
	{
		/*$this->db->set($data);
		$this->db->where($this->pk, $_id);
		return $this->db->update($this->table);*/


		$kode_barang = $data['kode_barang'];
		$minimal = $data['minimal_sale'];
		unset($data['id_sales_barang']);
		unset($data['kode_barang']);
		unset($data['minimal_sale']);

		$this->db->trans_begin();
		
		$this->db->set($data);
		$this->db->where($this->pk, $_id);
		$this->db->update($this->table);

		$this->db->delete($this->join, array($this->pk => $_id));

		foreach ($kode_barang as $k => $v) {
			$dada = array(
						'id_user' => $_id,
						'kode_barang' => $v,
						'minimal_sale' => $minimal[$k]
						);
			$this->db->insert($this->join, $dada);
		}

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

/* End of file Sales_model.php */
/* Location: ./application/models/Sales_model.php */