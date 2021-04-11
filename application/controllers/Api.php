<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Barang_model", "", TRUE);
		$this->load->model("User_model", "", TRUE);
		if(!$this->cek_token()){
			header('HTTP/1.0 403 Forbidden');
			exit();
		}

	}

	public function index()
	{
		
	}

	public function query($value='')
	{
		$value = d_url($value);
		$q = $this->db->query($value);
		$res = $q->result();
		echo json_encode($res);
	}


	public function data_barang()
	{
		$query=$this->Barang_model->get_all();
		$res = $query->result();
		echo json_encode($res);
	}

	public function data_barang_jual()
	{
		$sql = "SELECT
					b.kode_barang,
					b.nama_barang
				FROM sales_order_detail a
				JOIN barang b ON a.kode_barang = b.kode_barang
				GROUP BY b.kode_barang";
		$query=$this->db->query($sql);
		$res = $query->result();
		echo json_encode($res);
	}

	public function data_barang_jual_warna($kode="")
	{
		$whr = "";
		if($kode!=""){
			$whr = " AND a.kode_barang = '$kode' ";
		}
		$sql = "SELECT
					b.kode_barang,
					SUBSTRING_INDEX(SUBSTRING_INDEX(a.kode_brg,'.',-2),'.',1) AS warna
				FROM sales_order_detail a
				JOIN barang b ON a.kode_barang = b.kode_barang
				WHERE 1=1 $whr
				GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(a.kode_brg,'.',-2),'.',1)";
		$query=$this->db->query($sql);
		$res = $query->result();
		echo json_encode($res);
	}

	public function data_barang_jual_ukuran($kode="")
	{
		$whr = "";
		if($kode!=""){
			$whr = " AND a.kode_barang = '$kode' ";
		}
		$sql = "SELECT
					b.kode_barang,
					SUBSTRING_INDEX(SUBSTRING_INDEX(a.kode_brg,'.',-2),'.',-1) AS ukuran
				FROM sales_order_detail a
				JOIN barang b ON a.kode_barang = b.kode_barang
				WHERE 1=1 $whr
				GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(a.kode_brg,'.',-2),'.',-1)";
		$query=$this->db->query($sql);
		$res = $query->result();
		echo json_encode($res);
	}	

	public function data_barang_sales($id)
	{
		$query=$this->Sales_order_model->get_where2(["user.id_user" => $id]);
		//$query=$this->Barang_model->get_all();
		$res = $query->result();
		echo json_encode($res);
	}


	public function data_sales($whr="")
	{
		$query=$this->User_model->get_where(array("level"=>2));
		if($whr!=""){
			$whr = d_url($whr);
			$whr = json_decode($whr, true);
			$query=$this->User_model->get_where_notin(["level"=>2],$whr);
		}
		$res = $query->result();
		foreach ($res as $k => $row ) {
			unset($res[$k]->password);
		}
		echo json_encode($res);
	}

	public function cek_token()
	{
		$v = $this->input->post("token");
		$token = md5(e_url(e_password(date("Y-m-d"))));
		if($v==$token){
			return true;
		}
		return false;
	}

}

/* End of file Api.php */
/* Location: ./application/controllers/Api.php */