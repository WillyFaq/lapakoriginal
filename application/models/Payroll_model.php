<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_model extends CI_Model {

	public function __construct() 
	{ 
		parent::__construct(); 
	} 

	var $table1 = 'payroll_setting';
	var $table2 = 'payroll';
	var $join1 = 'payroll_detail';
	var $join2 = 'user';

	var $pk1 = 'id_payroll_setting';
	var $pk2 = 'id_payroll';
	var $fk1 = 'id_user';

	public function get_setting()
	{
		$q =  $this->db->get($this->table1);
		$res = $q->result();
		$ret = [];
		foreach ($res as $row => $v) {
           	$ret[$v->nama_setting] = $v->nilai;
		}
		return $ret;
	}

	public function update_setting($data)
	{
		$this->db->trans_begin();
		foreach ($data as $key => $value) {
			$this->db->set(["nilai" => $value]);
			$this->db->where("nama_setting", $key);
			$this->db->update($this->table1);
		}
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}
	}

	public function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->table2);
		$this->db->join($this->join1, $this->join1.'.'.$this->pk2.' = '.$this->table2.'.'.$this->pk2);
		$this->db->join($this->join2, $this->join2.'.'.$this->fk1.' = '.$this->table2.'.'.$this->fk1);
		return $this->db->get();
	}

	public function get_all_payroll()
	{
		$this->db->select('*');
		$this->db->from($this->table2);
		$this->db->join($this->join2, $this->join2.'.'.$this->fk1.' = '.$this->table2.'.'.$this->fk1);
		$this->db->join('jabatan', 'user.id_jabatan = jabatan.id_jabatan');
		$this->db->order_by($this->table2.'.tgl_gaji', 'desc');
		return $this->db->get();
	}

	public function get_payroll_id($id)
	{
		$this->db->select('*');
		$this->db->from($this->table2);
		$this->db->join($this->join2, $this->join2.'.'.$this->fk1.' = '.$this->table2.'.'.$this->fk1);
		$this->db->join('jabatan', 'user.id_jabatan = jabatan.id_jabatan');
		$this->db->where($this->pk2, $id);
		return $this->db->get();
	}

	public function gen_id()
	{
		$d = date("Y-m-d");
		$da = date("Ymd");
		$this->db->where("SUBSTR(id_payroll, 2, 8) = $da");
		$q = $this->db->get($this->table2);
		$nr = $q->num_rows();
		$nr+=1;
		$no = "000000".$nr;
		$no = substr($no, strlen($no)-6, strlen($no));
		return "B$da$no"; 
	}

	public function add($data='', $detail='')
	{
		
		$this->db->trans_begin();
		$this->db->insert($this->table2, $data);
		if(!empty($detail)){
			$this->db->insert_batch($this->join1, $detail);
		}

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return false;
		}else{
		    $this->db->trans_commit();
		    return true;
		}
	}

	public function get_det($id='')
	{
		$sql = "SELECT a.id_transaksi FROM payroll_detail a
				JOIN payroll b ON a.id_payroll = b.id_payroll
				WHERE b.id_user = $id";
		$q = $this->db->query($sql);
		$res = $q->result();
		$ret = [];
		foreach ($res as $row) {
			$ret[] = $row->id_transaksi; 
		}
		return "'".join("','", $ret)."'";
	}

	public function get_det_sales($id='', $tgl='')
	{
		$id_transaksi = $this->get_det($id);
		$sql = "SELECT 
					c.id_transaksi,
					f.nama_barang,
					c.tgl_order,
					d.jumlah_order
				FROM user a 
				JOIN jabatan b ON a.id_jabatan = b.id_jabatan
				JOIN sales_order c ON a.id_user = c.id_user
				JOIN sales_order_detail  d ON c.id_transaksi = d.id_transaksi
				JOIN barang f ON f.kode_barang = d.kode_barang
				JOIN pengiriman e ON c.id_transaksi = e.id_transaksi
				WHERE a.id_user = $id AND c.tgl_order <= '$tgl' AND e.status_pengiriman = 2  AND c.id_transaksi NOT IN($id_transaksi)
				ORDER BY c.tgl_order DESC";
		return $this->db->query($sql);
	}

	public function get_det_admin($id='', $tgl='')
	{
		$id_transaksi = $this->get_det($id);
		$sql = "SELECT
					a.id_transaksi,
					a.no_resi,
					a.tgl_kirim,
					a.status_pengiriman
				FROM pengiriman a
				JOIN user b ON a.id_user = b.id_user
				WHERE a.id_user = $id AND a.tgl_kirim <= '$tgl' AND a.status_pengiriman IN (2,3,4) AND a.id_transaksi NOT IN($id_transaksi)
				ORDER BY a.tgl_kirim, a.status_pengiriman DESC";
		return $this->db->query($sql);
	}

	public function get_det_iklan($id='', $tgl='')
	{
		$id_transaksi = $this->get_det($id);
		$sql = "SELECT
					a.id_transaksi,
					a.no_resi,
					a.tgl_kirim,
					a.status_pengiriman
				FROM pengiriman a
				JOIN user b ON a.id_user = b.id_user
				WHERE a.tgl_kirim <= '$tgl' AND a.status_pengiriman = 2 AND a.id_transaksi NOT IN($id_transaksi)
				ORDER BY a.tgl_kirim, a.status_pengiriman DESC";
		return $this->db->query($sql);
	}

	public function get_det_admin_iklan($id="", $tgl='')
	{
		$id_transaksi = $this->get_det($id);
		$sql = "SELECT
					a.id_transaksi,
					a.no_resi,
					a.tgl_kirim,
					a.status_pengiriman
				FROM pengiriman a
				JOIN user b ON a.id_user = b.id_user
				WHERE a.tgl_kirim <= '$tgl' AND a.status_pengiriman = 2 AND a.id_transaksi NOT IN($id_transaksi)
				ORDER BY a.tgl_kirim DESC, a.status_pengiriman DESC";
		return $this->db->query($sql);
	}

	public function get_det_sales_payroll($id='')
	{
		$sql = "SELECT 
					e.nama_barang,
					b.tgl_order,
					c.jumlah_order
				FROM payroll_detail a
				JOIN sales_order b ON a.id_transaksi = b.id_transaksi
				JOIN sales_order_detail c ON b.id_transaksi = c.id_transaksi
				JOIN barang e ON c.kode_barang = e.kode_barang
				WHERE a.id_payroll = '$id'
				ORDER BY b.tgl_order DESC";
		return $this->db->query($sql);
	}

	public function get_det_admin_payroll($id='')
	{
		$sql = "SELECT 
					a.id_transaksi,
					b.no_resi,
					b.tgl_kirim,
					b.status_pengiriman
				FROM payroll_detail a
				JOIN pengiriman b ON a.id_transaksi = b.id_transaksi
				WHERE a.id_payroll = '$id'
				ORDER BY b.tgl_kirim, b.status_pengiriman DESC";
		return $this->db->query($sql);
	}

	public function get_det_admin_iklan_payroll($id='')
	{
		$sql = "SELECT 
					a.id_transaksi,
					b.no_resi,
					b.tgl_kirim,
					b.status_pengiriman
				FROM payroll_detail a
				JOIN pengiriman b ON a.id_transaksi = b.id_transaksi
				WHERE a.id_payroll = '$id'
				ORDER BY b.tgl_kirim, b.status_pengiriman DESC";
		return $this->db->query($sql);
	}

	public function get_gaji_bulan($id='')
	{
		$b = date('m');
		$y = date('Y');
		$sql = "SELECT jumlah_gaji, bonus FROM payroll WHERE id_user = $id AND MONTH(tgl_gaji) = '$b' AND YEAR(tgl_gaji) = '$y' ";
		$q = $this->db->query($sql);	
		$res = $q->result();
		$ret = 0;
		foreach ($res as $row) {
			$ret += $row->jumlah_gaji;
			$ret += $row->bonus;
		}
		return $ret;
	}

}

/* End of file Payroll_model.php */
/* Location: ./application/models/Payroll_model.php */