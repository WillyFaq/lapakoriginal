<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table = 'bonus';
	var $join1 = 'sales_team';
	var $join2 = 'barang';
	var $pk = 'id_bonus';
	var $fk1 = 'id_sales_team';
	var $fk2 = 'kode_barang';

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, "$this->table.$this->fk1 = $this->join1.$this->fk1");
		$this->db->join($this->join2, "$this->join2.$this->fk2 = $this->join1.$this->fk2");
		return $this->db->get();
	}

	public function get_data($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->join($this->join1, "$this->table.$this->fk1 = $this->join1.$this->fk1");
		$this->db->join($this->join2, "$this->join2.$this->fk2 = $this->join1.$this->fk2");
		$this->db->where($this->pk, $id);
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

	public function add_detail($data, $idb)
	{
		$this->db->trans_begin();
		$this->db->insert_batch("bonus_so", $data);
		$this->db->where('id_bonus', $idb);
		$this->db->update($this->table, array("sts_bonus" => 1));
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}
	}

	public function get_transaksi($kode)
	{
		$sql = "SELECT 
					a.id_transaksi,
					b.nama,
					c.nama_barang,
					a.jumlah_order,
					a.tgl_order,
					d.status_pengiriman
				FROM bonus_so e
				JOIN sales_order a ON a.id_transaksi = e.id_transaksi
				JOIN user b ON a.id_user = b.id_user
				JOIN barang c ON a.kode_barang = c.kode_barang
				JOIN pengiriman d ON a.id_transaksi = d.id_transaksi
				WHERE e.id_bonus = '$kode' AND d.status_pengiriman = 2
				ORDER BY a.tgl_order";
		return $this->db->query($sql);
	}

	public function get_transaksi_before($kode, $bln)
	{
		$sql = "SELECT 
					a.id_transaksi,
					b.nama,
					c.nama_barang,
					a.jumlah_order,
					a.tgl_order,
					d.status_pengiriman
				FROM sales_order a
				JOIN user b ON a.id_user = b.id_user
				JOIN barang c ON a.kode_barang = c.kode_barang
				JOIN pengiriman d ON a.id_transaksi = d.id_transaksi
				WHERE MONTH(a.tgl_order) = $bln AND a.kode_barang = '$kode' AND d.status_pengiriman = 2
				ORDER BY a.tgl_order";
		return $this->db->query($sql);
	}
}

/* End of file Bonus_model.php */
/* Location: ./application/models/Bonus_model.php */