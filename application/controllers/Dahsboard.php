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
		$this->load->model("Pengiriman_model", "", TRUE);
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
			$data['table'] = $this->gen_table_pengiriman();
		}else if($lvl==0){
			$data['sub_page'] = 'dashboard/dashboard_atasan_view';
			$data['semua'] = $this->get_all_penjualan();
			$data['bulan_ini'] = $this->get_all_penjualan(date("n"));
			$data['hari_ini'] = $this->get_all_penjualan_hari(date("n"));
			$b = $this->Barang_model->get_all();
			$data['barang'] = $b->num_rows();
			$data['chart'] = $this->get_atasan_chart();
			$data['omset'] = $this->get_atasan_chart_omset();
		}else if($lvl==3){
			$data['sub_page'] = 'dashboard/dashboard_admin_view';
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

	public function get_atasan_chart()
	{
		$bln = get_bulan();
	    //print_pre($bln);

	    $data = [];
	    foreach ($bln as $k => $v) {
	        $data[] = $this->get_all_penjualan($k);
	    }

	    //print_pre($data);
	    return array(
	    				"label" => $bln,
	    				"data" => $data,
	    			);
	}



	public function get_atasan_chart_omset()
	{
		$bln = get_bulan();
	    //print_pre($bln);

	    $data = [];
	    foreach ($bln as $k => $v) {
	        $data[] = $this->get_all_omset($k);
	    }

	    //print_pre($data);
	    return array(
	    				"label" => $bln,
	    				"data" => $data,
	    			);
	}

	public function get_all_omset($bln="")
	{
		$query=$this->Barang_model->laporan_bulanan($bln);
		$res = $query->result();
		$tot = 0;
		$laba = 0;
		foreach ($res as $row){
			//$tot += $row->total_order;
			//$laba_penjualan = $row->total_order - $row->laba_penjualan - $this->Barang_model->get_iklan($row->kode_barang, $row->bulan);
			$laba += $row->total_order;
		}
		return $laba;
	}

	public function gen_table_pengiriman()
	{
		$query=$this->Pengiriman_model->get_where(array("status_pengiriman" => 0));
		//echo $this->db->last_query();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Id Transaksi', 'Nama Pelanggan', 'Nama Barang', 'Nama Gudang', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-warning">Sudah di acc</span>';
				$btn_update = anchor('pengiriman/tambah/'.e_url($row->id_pengiriman),'<span class="fas fa-paper-plane"></span>',array( 'title' => 'Kirim', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
				if($row->status_pengiriman==1){
					$sts = '<span class="badge badge-success">Sudah dikirim</span>';
					$btn_update = anchor('pengiriman/terima/'.e_url($row->id_pengiriman),'<span class="fa fa-check"></span>',array( 'title' => 'Diterima', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
					$btn_update .= "&nbsp;";
					$btn_update .= anchor('pengiriman/tolak/'.e_url($row->id_pengiriman),'<span class="fa fa-ban"></span>',array( 'title' => 'Ditolak', 'class' => 'btn btn-danger btn-xs', 'data-toggle' => 'tooltip'));
					
				}else if($row->status_pengiriman==2){
					$sts = '<span class="badge badge-info">Sudah diterima</span>';
					$btn_update = '';
				}else if($row->status_pengiriman==3){
					$sts = '<span class="badge badge-danger">Ditolak</span>';
					$btn_update = '';
				}
				$this->table->add_row(	++$i,
							$row->id_transaksi,
							$row->nama_pelanggan,
							$row->nama_barang,
							$row->nama_gudang,
							$sts,
							anchor('pengiriman/detail/'.e_url($row->id_pengiriman),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
							.'&nbsp;'.
							$btn_update
						);
			}
		}
		return $this->table->generate();
	}

}

/* End of file Dahsboard.php */
/* Location: ./application/controllers/Dahsboard.php */