<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang_barang_model extends CI_Model {
	
	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'gudang_barang';
	var $join1 = 'barang';
	var $join2 = 'gudang_user';
	var $pk = 'id_gb';
	var $fk1 = 'kode_barang';
	var $fk2 = 'id_gudang_user';

	public function get_all()
	{
		return $this->db->get($this->table);
	}

	public function get_all_gudang()
	{
		return $this->db->get($this->table);
	}

	public function get_all_by_user($idu)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, "$this->table.$this->fk1 = $this->join1.$this->fk1");
		$this->db->join($this->join2, "$this->table.$this->fk2 = $this->join2.$this->fk2");
		$this->db->where('id_user', $idu);
		return $this->db->get();
	}

	public function get_gudang_barang($kode)
	{
		$sql = "SELECT
					a.id_gudang,
					a.nama_gudang,
					IFNULL((
						SELECT 
							SUM(b.jumlah_gb) AS stok
						FROM gudang_barang b
						JOIN gudang_user c ON b.id_gudang_user = c.id_gudang_user
						WHERE c.id_gudang = a.id_gudang AND b.kode_barang IN ($kode)
						GROUP BY c.id_gudang, b.kode_barang
					),0) AS stok
				FROM gudang a";
		return $this->db->query($sql);
	}

	public function get_data($id)
	{
		$this->db->where(array($this->table.'.'.$this->pk => $id));
		return $this->db->get($this->table);
	}

	public function get_where($id)
	{			
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, "$this->table.$this->fk1 = $this->join1.$this->fk1");
		$this->db->join($this->join2, "$this->table.$this->fk2 = $this->join2.$this->fk2");
		$this->db->where($id);
		return $this->db->get();
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
	

	public function get_laporan($w=[])
	{
		$this->db->select('*');
		$this->db->from('gudang_barang a');
		$this->db->join('gudang_user b', 'a.id_gudang_user = b.id_gudang_user');
		$this->db->join('gudang c', 'b.id_gudang = c.id_gudang');
		$this->db->join('barang d', 'a.kode_barang = d.kode_barang');
		if(!empty($w)){
			$this->db->where($w);
		}
		$this->db->order_by('id_gb', 'desc');
		return $this->db->get();
	}

}

/* End of file Gudang_barang.php */
/* Location: ./application/models/Gudang_barang.php */