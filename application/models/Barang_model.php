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
		$whr = "WHERE 1 = 1 ";
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
					a.jumlah_order,
					a.total_order,
					a.harga_order,
					d.laba_barang,
					(d.laba_barang*a.jumlah_order) AS 'laba_penjualan'
				FROM sales_order a
				JOIN user b ON a.id_user = b.id_user
				JOIN pelanggan c ON a.no_pelanggan = c.no_pelanggan
				JOIN barang d ON a.kode_barang = d.kode_barang
				$whr";
		return $this->db->query($sql);
	}

	public function laporan_bulanan($bln)
	{
		$whr = "";
		if($bln!=""){
			$whr = " WHERE MONTH(a.tgl_order) = '$bln' ";
		}
		$sql = "SELECT
					a.id_transaksi,
					a.id_user,
					b.nama AS 'sales', 
					d.kode_barang,
					d.nama_barang,
					MONTH(a.tgl_order) AS 'bulan',
					SUM(a.jumlah_order) AS 'jumlah_order',
					SUM(a.total_order) AS 'total_order',
					SUM(a.harga_order) AS 'harga_order',
					SUM(d.laba_barang) AS 'laba_barang',
					SUM((d.laba_barang*a.jumlah_order)) AS 'laba_penjualan'
				FROM sales_order a
				JOIN user b ON a.id_user = b.id_user
				JOIN pelanggan c ON a.no_pelanggan = c.no_pelanggan
				JOIN barang d ON a.kode_barang = d.kode_barang
				$whr AND YEAR(a.tgl_order) = ".date("Y")."
				GROUP BY a.kode_barang, MONTH(a.tgl_order)";
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
					SUM(a.jumlah_order) AS 'jumlah_order',
					SUM(a.total_order) AS 'total_order',
					SUM(a.harga_order) AS 'harga_order',
					SUM(d.laba_barang) AS 'laba_barang',
					SUM((d.laba_barang*a.jumlah_order)) AS 'laba_penjualan'
				FROM sales_order a
				JOIN user b ON a.id_user = b.id_user
				JOIN pelanggan c ON a.no_pelanggan = c.no_pelanggan
				JOIN barang d ON a.kode_barang = d.kode_barang
				WHERE MONTH(a.tgl_order) = '$bln'  AND  d.kode_barang = '$id' AND YEAR(a.tgl_order) = ".date("Y")."
				GROUP BY a.kode_barang, MONTH(a.tgl_order)";
		return $this->db->query($sql);
	}
}

/* End of file Barang_model.php */
/* Location: ./application/models/Barang_model.php */