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
												"jml" => $this->Sales_model->get_tot_penjualan($id, date("Y"), date("n"), $row->kode_barang),
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
			$data['bulan_ini'] = $this->get_all_penjualan(date("n"), date("Y"));
			$data['hari_ini'] = $this->get_all_penjualan_hari(date("n"), date("Y"));
			$b = $this->Barang_model->get_all();
			$data['barang'] = $b->num_rows();
		}else if($lvl==3){
			$data['sub_page'] = 'dashboard/dashboard_gudang_view';
			$data['table'] = $this->gen_table_gudang();
		}
		$this->load->view('index', $data);
	}


	public function get_all_penjualan($bln="", $thn="")
	{
		$thn = $thn==''?date("Y"):$thn;
		$query=$this->Barang_model->laporan_bulanan($bln, $thn);
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

	public function get_atasan_chart($thn="")
	{
		$bln = get_bulan();
	    //print_pre($bln);

	    $data = [];
	    foreach ($bln as $k => $v) {
	        $data[] = $this->get_all_penjualan($k, $thn);
	    }

	    //print_pre($data);
	    return array(
	    				"label" => $bln,
	    				"data" => $data,
	    			);
	}



	public function get_atasan_chart_omset($thn="")
	{
		$bln = get_bulan();
	    //print_pre($bln);

	    $data = [];
	    foreach ($bln as $k => $v) {
	        $data[] = $this->get_all_omset($k, $thn);
	    }

	    //print_pre($data);
	    return array(
	    				"label" => $bln,
	    				"data" => $data,
	    			);
	}

	public function get_all_omset($bln="", $thn="")
	{
		$query=$this->Barang_model->laporan_bulanan($bln, $thn);
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

	public function gen_table_gudang()
	{
		$ids = $this->session->userdata('user')->id_user;
		$sql = "SELECT * FROM gudang_user WHERE id_user = '$ids'";
		$q = $this->db->query($sql);
		$res = $q->result();
		$id_gudang = $res[0]->id_gudang;
		
		$sql = "SELECT * FROM pengiriman a
				JOIN sales_order b ON a.id_transaksi = b.id_transaksi
				JOIN barang c ON b.kode_barang = c.kode_barang
				JOIN pelanggan d ON b.no_pelanggan = d.no_pelanggan
				WHERE a.id_gudang = $id_gudang AND a.status_pengiriman = 0";
		$q = $this->db->query($sql);
		$res = $q->result();

		$data = [];

		foreach ($res as $row) {
			$data[] = array(
							"id_pengiriman" => $row->id_pengiriman,
							"kode_barang" => $row->kode_barang,
							"nama_barang" => $row->nama_barang,
							"sts" => '<span class="badge badge-info">Akan dikirim</span>',
							"btn" => 0,
							);
		}

		$sql = "SELECT * FROM pengiriman a
				JOIN sales_order b ON a.id_transaksi = b.id_transaksi
				JOIN barang c ON b.kode_barang = c.kode_barang
				JOIN pelanggan d ON b.no_pelanggan = d.no_pelanggan
				WHERE a.id_gudang = $id_gudang AND a.status_pengiriman = 3";
		$q = $this->db->query($sql);
		$res = $q->result();
		foreach ($res as $row) {
			$data[] = array(
							"id_pengiriman" => $row->id_pengiriman,
							"kode_barang" => $row->kode_barang,
							"nama_barang" => $row->nama_barang,
							"sts" => '<span class="badge badge-danger">Ditolak</span>',
							"btn" => 1,
							);
		}

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);
		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Kode Barang', 'Nama Barang', 'Status', 'Aksi');

		if (sizeof($data) > 0){
			$i = 0;
			foreach ($data as $k => $row) {

				$btn = anchor('gudang_dashboard/detail_kirim/'.e_url($row['id_pengiriman']), '<span class="fa fa-eye"></span>', array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'));
				if($row['btn']==1){
					$btn = anchor('gudang_dashboard/detail_ditolak/'.e_url($row['id_pengiriman']), '<span class="fa fa-eye"></span>', array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'));
				}


				$this->table->add_row(	
										++$i,
										$row['kode_barang'],
										$row['nama_barang'],
										$row['sts'],
										$btn,
				);
			}
		}
		return $this->table->generate();
	}


	public function load_demografi($type="", $brg="")
	{
		$data = array("type" => $type, "brg" => $brg);
		$whr = "";
        if($type!=""){
            if($type=="1"){
                $whr .= " AND a.tgl_order >= DATE(NOW()) - INTERVAL 7 DAY ";
            }else if($type=="2"){
                $whr .= " AND a.tgl_order >= DATE(NOW()) - INTERVAL 14 DAY ";
            }else if($type==3){
                $whr .= " AND a.tgl_order >= DATE(NOW()) - INTERVAL 30 DAY ";
            }else{
            	$t = explode("_", $type);
            	if($t[0]==4){
            		$tgl1 = $t[1];
            		$tgl2 = $t[2];
            		if($tgl1!="" && $tgl2!=""){
                		$whr .= " AND a.tgl_order >= '$tgl1' ";
            		}

            		if($tgl2!=""){
                		$whr .= " AND a.tgl_order <= '$tgl2' ";
            		}
            	}
            }
        }

        if($brg!=""){
        	$whr .= " AND a.kode_barang = '$brg' ";
        }
        $sql = "SELECT  
                    SUBSTR(b.alamat, 1, 2) AS prov,
                    COUNT(*) AS jml
                FROM sales_order a
                JOIN pelanggan b ON a.no_pelanggan = b.no_pelanggan
                WHERE 1=1 $whr
                GROUP BY SUBSTR(b.alamat, 1, 2)";
        $q = $this->db->query($sql);

        $data["q"] = $q;
		$this->load->view('chart/demograpi_view', $data);
	}

	public function load_profit($thn="")
	{
		$data = array(
						'chart' => $this->get_atasan_chart($thn)
						);
		$this->load->view('chart/profit_view', $data);
	}

	public function load_omset($thn="")
	{
		$data = array(
						'omset' => $this->get_atasan_chart_omset($thn)
						);
		$this->load->view('chart/omset_view', $data);
	}
}

/* End of file Dahsboard.php */
/* Location: ./application/controllers/Dahsboard.php */