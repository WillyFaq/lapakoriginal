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

	public function get_iklan($kode, $bln)
	{
		$sql = "SELECT 
					kode_barang,
					MONTH(tgl_iklan) AS 'bulan',
					SUM(biaya_iklan) AS 'biaya_iklan'
				FROM iklan
				WHERE kode_barang = '$kode' AND MONTH(tgl_iklan) = $bln
				GROUP BY kode_barang, MONTH(tgl_iklan)";
		$q = $this->db->query($sql);
		$res = $q->result();
		foreach ($res as $row) {
			return $row->biaya_iklan;
		}
	}

	public function laporan_harian($tgl1, $tgl2)
	{
		$whr = "WHERE e.status_pengiriman = 2 ";
		if($tgl1 != ""){
			$whr .= " AND a.tgl_order >= '$tgl1' ";
		}

		if($tgl2 != ""){
			$whr .= " AND a.tgl_order <= '$tgl2' ";
		}

		$sql = "SELECT
					a.id_transaksi,
					a.id_user,
					b.nama AS 'sales', 
					d.kode_barang,
					d.nama_barang,
					a.tgl_order,
					f.jumlah_order,
					a.total_order,
					f.harga_order,
					d.laba_barang,
					(d.laba_barang*f.jumlah_order) AS 'laba_penjualan'
				FROM sales_order a
				JOIN sales_order_detail f ON a.id_transaksi = f.id_transaksi
				JOIN user b ON a.id_user = b.id_user
				JOIN pelanggan c ON a.no_pelanggan = c.no_pelanggan
				JOIN barang d ON f.kode_barang = d.kode_barang
				JOIN pengiriman e ON e.id_transaksi = f.id_transaksi
				$whr";
		return $this->db->query($sql);
	}

	public function laporan_bulanan($bln, $thn, $brg)
	{
		$whr = "WHERE YEAR(a.tgl_order) = '".$thn."'";

		if($bln!=""){
			$whr .= " AND MONTH(a.tgl_order) = '$bln' ";
		}
		if($brg!=""){
			$whr .= " AND f.kode_barang = '$brg' ";
		}
		$sql = "SELECT
					a.id_transaksi,
					a.id_user,
					b.nama AS 'sales', 
					d.kode_barang,
					d.nama_barang,
					MONTH(a.tgl_order) AS 'bulan',
					SUM(f.jumlah_order) AS 'jumlah_order',
					SUM(a.total_order) AS 'total_order',
					SUM(f.harga_order) AS 'harga_order',
					SUM(d.laba_barang) AS 'laba_barang',
					SUM((d.laba_barang*f.jumlah_order)) AS 'laba_penjualan'
				FROM sales_order a
				JOIN sales_order_detail f ON a.id_transaksi = f.id_transaksi
				JOIN user b ON a.id_user = b.id_user
				JOIN pelanggan c ON a.no_pelanggan = c.no_pelanggan
				JOIN barang d ON f.kode_barang = d.kode_barang
				JOIN pengiriman e ON a.id_transaksi = e.id_transaksi
				$whr
				AND e.status_pengiriman = 2	
				GROUP BY f.kode_barang, MONTH(a.tgl_order)";

		return $this->db->query($sql);
	}


	public function laporan_bulanan_id($bln, $id)
	{
		
		$sql = "SELECT
					a.id_transaksi,
					a.id_user,
					b.nama AS 'sales', 
					d.kode_barang,
					d.nama_barang,
					d.harga_jual,
					MONTH(a.tgl_order) AS 'bulan',
					SUM(f.jumlah_order) AS 'jumlah_order',
					SUM(a.total_order) AS 'total_order',
					SUM(f.harga_order) AS 'harga_order',
					SUM(d.laba_barang) AS 'laba_barang',
					SUM((d.laba_barang*f.jumlah_order)) AS 'laba_penjualan'
				FROM sales_order a
				JOIN sales_order_detail f ON f.id_transaksi = a.id_transaksi
				JOIN user b ON a.id_user = b.id_user
				JOIN pelanggan c ON a.no_pelanggan = c.no_pelanggan
				JOIN barang d ON f.kode_barang = d.kode_barang
				JOIN pengiriman e ON a.id_transaksi = e.id_transaksi
				WHERE MONTH(a.tgl_order) = '$bln'  AND  d.kode_barang = '$id' AND YEAR(a.tgl_order) = ".date("Y")."
				AND e.status_pengiriman = 2
				GROUP BY f.kode_barang, MONTH(a.tgl_order)";
		return $this->db->query($sql);
	}
}

/* End of file Barang_model.php */
/* Location: ./application/models/Barang_model.php */