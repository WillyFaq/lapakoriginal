<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dahsboard extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("User_model", "", TRUE);
		$this->load->model("Sales_model", "", TRUE);
		$this->load->model("Barang_model", "", TRUE);
		$this->load->model("Sales_order_model", "", TRUE);
	}

	public function index()
	{
		$data = array(
						"page" => "home_view"
						);
		$lvl = $this->session->userdata('user')->level;
		if($lvl==2){
			$data['sub_page'] = "dashboard/dashboard_sales_view";

			$id = $this->session->userdata('user')->id_user;
			$q = $this->Sales_model->get_data($id);
			$res = $q->result();
			$ret = "";
			foreach ($res as $row) {
				$data['semua'][] =  array(
												"barang" => $row->nama_barang,
												"jml" => $this->Sales_model->get_tot_penjualan_all($id, $row->kode_barang),
											);
				$data['bulan_ini'][] =  array(
												"barang" => $row->nama_barang,
												"jml" => $this->Sales_model->get_tot_penjualan($id, date("n"), $row->kode_barang),
											);
				$data['hari_ini'][] =  array(
												"barang" => $row->nama_barang,
												"jml" => $this->Sales_model->get_tot_penjualan_today($id, date("n"), $row->kode_barang),
											);
				$data['target'][] =  array(
												"barang" => $row->nama_barang,
												"jml" => $row->minimal_sale,
											);
			}

			//$data['bulan_ini'] = 
		}else if($lvl==1){
			$data['sub_page'] = 'dashboard/dashboard_admin_view';
		}else if($lvl==0){
			$data['sub_page'] = 'dashboard/dashboard_atasan_view';
			$data['semua'] = $this->get_all_penjualan();
			$data['bulan_ini'] = $this->get_all_penjualan(date("n"));
			$data['hari_ini'] = $this->get_all_penjualan_hari(date("n"));
			$b = $this->Barang_model->get_all();
			$data['barang'] = $b->num_rows();;
		}
		$this->load->view('index', $data);
	}


	public function get_all_penjualan($bln="")
	{
		$query=$this->Barang_model->laporan_bulanan($bln);
		$res = $query->result();
		$tot = 0;
		$laba = 0;
		foreach ($res as $row){
			$tot += $row->total_order;
			$laba_penjualan = $row->total_order - $row->laba_penjualan - $this->Barang_model->get_iklan($row->kode_barang, $row->bulan);
			$laba += $laba_penjualan;
		}
		return $laba;
	}

	public function get_all_penjualan_hari()
	{
		$tgl = date("Y-m-d");
		$query=$this->Barang_model->laporan_harian($tgl, $tgl);
		$res = $query->result();
		$num_rows = 0;
		foreach ($res as $row){
			//print_pre($row);
			$num_rows += $row->jumlah_order;
		}

		return $num_rows;
	}

}

/* End of file Dahsboard.php */
/* Location: ./application/controllers/Dahsboard.php */