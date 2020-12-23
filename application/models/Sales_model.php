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

	public function get_tot_penjualan($id, $thn, $bln, $kode)
	{
		$whr = "";
		if($kode!=""){
			$whr .= " AND b.kode_barang = '$kode' ";
		}
		$sql = "SELECT 
					a.id_user,
					MONTH(a.tgl_order) AS bulan,
					SUM(b.jumlah_order) AS jumlah_order
				FROM sales_order a
				JOIN sales_order_detail b ON a.id_transaksi = b.id_transaksi
				WHERE a.id_user = '$id' AND YEAR(a.tgl_order) = $thn AND MONTH(a.tgl_order) = $bln $whr
				GROUP BY a.id_user, MONTH(a.tgl_order)";
		$q = $this->db->query($sql);
		$res = $q->result();
		foreach ($res as $row) {
			return $row->jumlah_order;
		}
	}

	public function get_penjualan($id, $bln, $kode, $thn)
	{
		$sql = "SELECT 
					a.id_user,
					b.nama_barang,
					a.tgl_order,
					SUM(c.jumlah_order) as jumlah_order
				FROM sales_order a
				JOIN sales_order_detail c ON a.id_transaksi = c.id_transaksi
				JOIN barang b ON c.kode_barang = b.kode_barang
				WHERE a.id_user = '$id' AND MONTH(a.tgl_order) = $bln AND YEAR(a.tgl_order) = $thn  AND c.kode_barang = '$kode'
				GROUP BY DATE(a.tgl_order)";
		return $this->db->query($sql);
	}

	public function get_tot_penjualan_all($id, $kode)
	{
		$whr = "";
		if($kode!=""){
			$whr .= " AND b.kode_barang = '$kode' ";
		}
		$sql = "SELECT 
					a.id_user,
					MONTH(a.tgl_order) AS bulan,
					SUM(b.jumlah_order) AS jumlah_order
				FROM sales_order a
				JOIN sales_order_detail b ON a.id_transaksi = b.id_transaksi
				WHERE a.id_user = '$id' $whr
				GROUP BY a.id_user";
		$q = $this->db->query($sql);
		$res = $q->result();
		foreach ($res as $row) {
			return $row->jumlah_order;
		}
	}

	public function get_tot_penjualan_today($id, $kode)
	{
		$tgl = date("Y-m-d");
		$sql = "SELECT 
					a.id_user,
					MONTH(a.tgl_order) AS bulan,
					SUM(b.jumlah_order) AS jumlah_order
				FROM sales_order a
				JOIN sales_order_detail b ON a.id_transaksi = b.id_transaksi
				WHERE a.id_user = '$id' AND b.kode_barang = '$kode' AND DATE(a.tgl_order) = '$tgl'
				GROUP BY a.id_user";
		$q = $this->db->query($sql);
		$res = $q->result();
		foreach ($res as $row) {
			return $row->jumlah_order;
		}
	}

}

/* End of file Sales_model.php */
/* Location: ./application/models/Sales_model.php */