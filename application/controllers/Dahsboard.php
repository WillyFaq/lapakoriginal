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
		$this->load->model("Payroll_model", "", TRUE);
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
			$data['semua'] = [];
			$data['bulan_ini'] = [];
			$data['hari_ini'] = [];
			$data['target'] = [];
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
			$data['tbl_pending'] = $this->gen_table_pending_sales($id);
			$data['tbl_kirim'] = $this->gen_table_pengiriman_sales($id);
			$data['gaji_bulan_ini'] = $this->Payroll_model->get_gaji_bulan($id);
		}else if($lvl==1){
			$data['sub_page'] = 'dashboard/dashboard_admin_view';
			$data['table'] = $this->gen_table_pengiriman();
			$data['table2'] = $this->gen_table_pengiriman2();
			$data['table3'] = $this->gen_table_pending();
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
		}else if($lvl==4){ // admin iklan
			$data['sub_page'] = 'dashboard/dashboard_iklan_view';
		}
		$this->load->view('index', $data);
	}


	public function get_all_penjualan($bln="", $thn="", $brg="")
	{
		$thn = $thn==''?date("Y"):$thn;
		$query=$this->Barang_model->laporan_bulanan($bln, $thn, $brg);
		$res = $query->result();
		$tot = 0;
		$laba = 0;
		foreach ($res as $row){
			$tot += $row->total_order;
			$iklan = $this->Barang_model->get_iklan($row->kode_barang, $row->bulan);
			$iklan = $iklan==''?0:$iklan;
			//echo "$iklan<br>";
			//$laba_penjualan = (int)$row->total_order - (int)$row->laba_penjualan - (int)$iklan;
			$laba_penjualan = (int)$row->laba_penjualan - (int)$iklan;
			//echo "$laba_penjualan = $row->total_order - $row->laba_penjualan - $iklan <br>";
			//echo "$laba_penjualan<br>";
			$laba += (int)$laba_penjualan;
		}
		/*echo "<hr>";
		echo $laba;*/
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

	public function get_atasan_chart($thn="", $brg="")
	{
		$bln = get_bulan();
	    //print_pre($bln);

	    $data = [];
	    foreach ($bln as $k => $v) {
	        $data[] = $this->get_all_penjualan($k, $thn, $brg);
	    }

	    return array(
	    				"label" => $bln,
	    				"data" => $data,
	    			);
	}



	public function get_atasan_chart_omset($thn="", $brg="")
	{
		$bln = get_bulan();
	    //print_pre($bln);

	    $data = [];
	    foreach ($bln as $k => $v) {
	        $data[] = $this->get_all_omset($k, $thn, $brg);
	    }

	    //print_pre($data);
	    return array(
	    				"label" => $bln,
	    				"data" => $data,
	    			);
	}

	public function get_all_omset($bln="", $thn="", $brg="")
	{
		$query=$this->Barang_model->laporan_bulanan($bln, $thn, $brg);
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

	public function gen_table_pengiriman2()
	{
		$query=$this->Pengiriman_model->get_where2(array("status_pengiriman" => 1));
		//echo $this->db->last_query();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama Sales', 'Nama Pelanggan', 'Jasa Pengiriman', 'No Resi', 'Tgl Pengiriman', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-warning">Sudah di acc</span>';
				$btn_update = anchor('pengiriman/tambah/'.e_url($row->id_pengiriman),'<span class="fas fa-paper-plane"></span>',array( 'title' => 'Kirim', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
				if($row->status_pengiriman==1){
					$sts = '<span class="badge badge-info">Sudah dikirim</span>';
					$btn_update = anchor('pengiriman/terima/'.e_url($row->id_pengiriman),'<span class="fa fa-check"></span>',array( 'title' => 'Diterima', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
					$btn_update .= "&nbsp;";
					$btn_update .= anchor('pengiriman/tolak/'.e_url($row->id_pengiriman),'<span class="fa fa-ban"></span>',array( 'title' => 'Ditolak', 'class' => 'btn btn-danger btn-xs', 'data-toggle' => 'tooltip'));
					
				}else if($row->status_pengiriman==2){
					$sts = '<span class="badge badge-success">Sudah diterima</span>';
					$btn_update = '';
				}else if($row->status_pengiriman>=3){
					$sts = '<span class="badge badge-danger">Ditolak</span>';
					$c = $this->Pengiriman_model->get_where(['pengiriman.id_transaksi' => $row->id_transaksi]);
					$btn_update = '';
					if($c->num_rows()<2){
						$btn_update = anchor('pengiriman/kirim_ulang/'.e_url($row->id_transaksi),'<span class="fas fa-paper-plane"></span>',array( 'title' => 'Kirim Ulang', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
					}
				}
				$tgl = '';
				if($row->tgl_kirim!=null){
					$tgl = date("d-m-Y", strtotime($row->tgl_kirim));
				}
				$this->table->add_row(	++$i,
							$row->nama,
							$row->nama_pelanggan,
							$row->jasa_pengiriman,
							$row->no_resi,
							$tgl,
							$sts,
							anchor('pengiriman/detail/'.e_url($row->id_pengiriman),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
							.'&nbsp;'.
							$btn_update
						);
			}
		}
		return $this->table->generate();
	}
	public function gen_table_pengiriman()
	{
		
		//$query=$this->Sales_order_model->get_where2(["status_order" => "0"]);
		$pending = $this->Pengiriman_model->get_pending_id();
		$query=$this->Sales_order_model->get_where2("status_order = 0 AND id_transaksi NOT IN ($pending)");
		$res = $query->result();
		$num_rows = $query->num_rows();
		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama Sales', 'Nama Pelanggan', 'Total', 'Tanggal', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-danger">Belum dikirim</span>';
				if($sts==1){
					$sts = '<span class="badge badge-success">Sudah dikirim</span>';
				}
				$this->table->add_row(	++$i,
							trim($row->nama),
							trim($row->nama_pelanggan),
							'Rp. '.number_format($row->total_order),
							date("d-m-Y", strtotime($row->tgl_order)),
							$sts,
							anchor('pengiriman/kirimkan/'.e_url($row->id_transaksi),'<span class="fa fa-box"></span>',array( 'title' => 'Kirim', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'))
							.'&nbsp;'.
							anchor('pengiriman/tolak_so/'.e_url($row->id_transaksi),'<span class="fa fa-ban"></span>',array( 'title' => 'Batalkan', 'class' => 'btn btn-danger btn-xs', 'data-toggle' => 'tooltip'))
							.'&nbsp;'.
							anchor('pengiriman/pending/'.e_url($row->id_transaksi),'<span class="fa fa-exclamation-triangle"></span>',array( 'title' => 'Pending', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
						);
			}
		}
		return  $this->table->generate();
	}

	public function gen_table_pending()
	{
		
		//$query=$this->Sales_order_model->get_where2(["status_order" => "0"]);
		$query = $this->Pengiriman_model->get_pending();
		$res = $query->result();
		$num_rows = $query->num_rows();
		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama Sales', 'Nama Pelanggan', 'Total', 'Tanggal', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-danger">Belum dikirim</span>';
				$sts .= '&nbsp;<span class="badge badge-danger">Pending</span>';
				if($sts==1){
					$sts = '<span class="badge badge-success">Sudah dikirim</span>';
				}
				$this->table->add_row(	++$i,
							trim($row->nama),
							trim($row->nama_pelanggan),
							'Rp. '.number_format($row->total_order),
							date("d-m-Y", strtotime($row->tgl_order)),
							$sts,
							anchor('pengiriman/kirimkan/'.e_url($row->id_transaksi),'<span class="fa fa-box"></span>',array( 'title' => 'Kirim', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'))
							.'&nbsp;'.
							anchor('pengiriman/tolak_so/'.e_url($row->id_transaksi),'<span class="fa fa-ban"></span>',array( 'title' => 'Batalkan', 'class' => 'btn btn-danger btn-xs', 'data-toggle' => 'tooltip'))
						);
			}
		}
		return  $this->table->generate();
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
				JOIN sales_order_detail e ON a.id_transaksi = e.id_transaksi
				JOIN barang c ON e.kode_barang = c.kode_barang
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
							"btn" => 0
							);
		}

		$sql = "SELECT * FROM pengiriman a
				JOIN sales_order b ON a.id_transaksi = b.id_transaksi
				JOIN sales_order_detail e ON a.id_transaksi = e.id_transaksi
				JOIN barang c ON e.kode_barang = c.kode_barang
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
										$btn
				);
			}
		}
		return $this->table->generate();
	}


	public function load_demografi($type="", $brg="", $kd_brg="")
	{
		$data = array("type" => $type, "brg" => $brg, $kd_brg => $kd_brg);
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
        	$whr .= " AND b.kode_barang = '$brg' ";
        }
        if($kd_brg!=""){
        	$k = explode(".", $kd_brg);
        	if($k[1]!=""){
        		$whr .= "AND IF(LOCATE('.', b.kode_brg, (LOCATE('.', b.kode_brg)+1))>0, SUBSTRING(b.kode_brg, LOCATE('.', b.kode_brg)+1, LOCATE('.', SUBSTRING(b.kode_brg, LOCATE('.', b.kode_brg)+1 ) )-1), SUBSTRING(b.kode_brg, LOCATE('.', b.kode_brg)+1 ) ) = '$k[1]' ";
        	}
        	if($k[2]!=""){
        		$whr .= "AND IF(LOCATE('.', b.kode_brg, (LOCATE('.', b.kode_brg)+1))>0, RIGHT(b.kode_brg, LENGTH(b.kode_brg) - LOCATE('.', b.kode_brg, (LOCATE('.', b.kode_brg)+1)) ), '') = '$k[2]' ";
        	}
        	//$whr .= " AND b.kode_brg LIKE '%$kd_brg%' ";
        }
        $sql = "SELECT  
                    SUBSTR(c.alamat, 1, 2) AS prov,
                    SUM(b.jumlah_order) AS jml
                FROM sales_order a
                JOIN sales_order_detail b ON a.id_transaksi = b.id_transaksi
                JOIN pelanggan c ON a.no_pelanggan = c.no_pelanggan
                WHERE 1=1 $whr
                GROUP BY SUBSTR(c.alamat, 1, 2)";
        //print_pre($sql);
        $q = $this->db->query($sql);
        /*if($q->num_rows()==0){
        	$whr = str_replace(" AND b.kode_barang = '$brg' ", "", $whr);
        	$whr .= " AND SUBSTRING_INDEX(a.kode_barang, '.', 1) = '$brg' ";
        	$sql = "SELECT  
                    SUBSTR(b.alamat, 1, 2) AS prov,
                    COUNT(*) AS jml
                FROM sales_order a
                JOIN pelanggan b ON a.no_pelanggan = b.no_pelanggan
                WHERE 1=1 $whr
                GROUP BY SUBSTR(b.alamat, 1, 2)";
        	$q = $this->db->query($sql);
        }*/
        //echo $this->db->last_query();
        $data["q"] = $q;
        $data["whr"] = $whr;
        $res = $q->result();
        //print_pre($res);
		$this->load->view('chart/demograpi_view', $data);
	}

	public function load_profit($thn="", $brg="")
	{
		$data = array(
						'chart' => $this->get_atasan_chart($thn, $brg)
						);
		$this->load->view('chart/profit_view', $data);
	}

	public function load_omset($thn="", $brg="")
	{
		$data = array(
						'omset' => $this->get_atasan_chart_omset($thn, $brg)
						);
		$this->load->view('chart/omset_view', $data);
	}

	public function load_history($tgl="")
	{	
		$judul = "Order History Bulan ".get_bulan(date("n"));
		$sql = " AND YEAR(a.tgl_order) = '".date("Y")."' AND MONTH(a.tgl_order) = '".date("m")."' ";
		if($tgl!=""){
			$judul = "Order History ";
			$tgl = explode("_", $tgl);
			if($tgl[1]==""){
				$sql = " AND a.tgl_order > '$tgl[0]' ";
				$judul .= "Mulai Tanggal $tgl[0] ";
			}else if($tgl[0]==""){
				$sql = " AND a.tgl_order < '$tgl[1]' ";
				$judul .= "Sampai Tanggal $tgl[1] ";
			}else{
				if(strtotime($tgl[1])<strtotime($tgl[0])){
					echo 'Tanggal tidal valid!';
				}else{
					$sql = " AND a.tgl_order < '$tgl[1]' AND a.tgl_order > '$tgl[0]' ";
					$judul .= "Mulai Tanggal $tgl[0] Sampai Tanggal $tgl[1] ";
				}
			}
		}

		$data = array(
						'sql' => $sql,
						'judul' => $judul
						);
		$this->load->view('chart/history_so', $data);
	}

	public function load_income_history($tgl="")
	{	
		$judul = "Income History";
		//$sql = " AND YEAR(tgl_gaji) = '".date("Y")."' AND MONTH(tgl_gaji) = '".date("m")."' ";
		$sql = "";
		if($tgl!=""){
			$judul = "Income History ";
			$tgl = explode("_", $tgl);
			if($tgl[1]==""){
				$sql = " AND tgl_gaji > '$tgl[0]' ";
				$judul .= "Mulai Tanggal $tgl[0] ";
			}else if($tgl[0]==""){
				$sql = " AND tgl_gaji < '$tgl[1]' ";
				$judul .= "Sampai Tanggal $tgl[1] ";
			}else{
				if(strtotime($tgl[1])<strtotime($tgl[0])){
					echo 'Tanggal tidal valid!';
				}else{
					$sql = " AND tgl_gaji < '$tgl[1]' AND tgl_gaji > '$tgl[0]' ";
					$judul .= "Mulai Tanggal $tgl[0] Sampai Tanggal $tgl[1] ";
				}
			}
		}

		$data = array(
						'sql' => $sql,
						'judul' => $judul
						);
		$this->load->view('chart/income_so', $data);
	}

	public function chart_history($sql='')
	{
		
	}

	public function cb_barang_filter($id='')
	{
		$whr = "";
		$ret = "";
		if($id!=""){
			//$whr = " WHERE SUBSTRING_INDEX(kode_barang, '.', 1) = '$id' ";
			$whr = " WHERE kode_barang = '$id' ";
			$sql = "SELECT 
						kode_barang,
						warna_barang,
						ukuran_barang
		            FROM barang
		            $whr";
		    $q = $this->db->query($sql);
			$res = $q->result();
			$opt[""] = "Semua Warna";
			$opt2[""] = "Semua Ukuran";
			foreach ($res as $row) {
				$warna = explode(",", $row->warna_barang);
				$ukuran = explode(",", $row->ukuran_barang);
				foreach($warna as $k => $v){
					$opt[$v] = $v;
				}
				foreach($ukuran as $k => $v){
					$opt2[$v] = $v;
				}
			}
			$war = "";
			if(sizeof($opt)>1){
				$js = 'class="form-control" id="cb_brg_warna" onchange="filter_demo()" ';
				$war = '<div class="col-sm-6">';
				$war .= form_dropdown('warna_barang',$opt,"",$js);
				$war .= '</div>';
			}

			$ukr = '';
			if(sizeof($opt2)>1){
				$js2 = 'class="form-control" id="cb_brg_ukuran" onchange="filter_demo()" ';
				$ukr = '<div class="col-sm-6">';
				$ukr .= form_dropdown('ukuran_barang',$opt2,"",$js2);
				$ukr .= '</div>';
			}
			$ret = $war.$ukr; 
		}
		//$ret= $ret.'</div></div>';
		echo $ret;
	}


	public function gen_table_pending_sales($id)
	{
		$query = $this->Pengiriman_model->get_pending_sales($id);
		$res = $query->result();
		$num_rows = $query->num_rows();
		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama Sales', 'Nama Pelanggan', 'Total', 'Tanggal', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-danger">Belum dikirim</span>';
				$sts .= '&nbsp;<span class="badge badge-danger">Pending</span>';
				if($sts==1){
					$sts = '<span class="badge badge-success">Sudah dikirim</span>';
				}
				$this->table->add_row(	++$i,
							trim($row->nama),
							trim($row->nama_pelanggan),
							'Rp. '.number_format($row->total_order),
							date("d-m-Y", strtotime($row->tgl_order)),
							$sts,
							anchor('sales_order/detail/'.e_url($row->id_transaksi),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
							.'&nbsp;'
						);
			}
			return  $this->table->generate();
		}else{
			return '';;
		}
	}

	public function gen_table_pengiriman_sales($id)
	{
		$query=$this->Pengiriman_model->get_where2(array("status_pengiriman" => 1, 'sales_order.id_user' => $id));
		//echo $this->db->last_query();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No','Nama Pelanggan', 'Jasa Pengiriman', 'No Resi', 'Tgl Pengiriman', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-warning">Sudah di acc</span>';
				$btn_update = anchor('pengiriman/tambah/'.e_url($row->id_pengiriman),'<span class="fas fa-paper-plane"></span>',array( 'title' => 'Kirim', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
				if($row->status_pengiriman==1){
					$sts = '<span class="badge badge-info">Sudah dikirim</span>';
					$btn_update = anchor('pengiriman/terima/'.e_url($row->id_pengiriman),'<span class="fa fa-check"></span>',array( 'title' => 'Diterima', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
					$btn_update .= "&nbsp;";
					$btn_update .= anchor('pengiriman/tolak/'.e_url($row->id_pengiriman),'<span class="fa fa-ban"></span>',array( 'title' => 'Ditolak', 'class' => 'btn btn-danger btn-xs', 'data-toggle' => 'tooltip'));
					
				}else if($row->status_pengiriman==2){
					$sts = '<span class="badge badge-success">Sudah diterima</span>';
					$btn_update = '';
				}else if($row->status_pengiriman>=3){
					$sts = '<span class="badge badge-danger">Ditolak</span>';
					$c = $this->Pengiriman_model->get_where(['pengiriman.id_transaksi' => $row->id_transaksi]);
					$btn_update = '';
					if($c->num_rows()<2){
						$btn_update = anchor('pengiriman/kirim_ulang/'.e_url($row->id_transaksi),'<span class="fas fa-paper-plane"></span>',array( 'title' => 'Kirim Ulang', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
					}
				}
				$tgl = '';
				if($row->tgl_kirim!=null){
					$tgl = date("d-m-Y", strtotime($row->tgl_kirim));
				}
				$this->table->add_row(	++$i,
							$row->nama_pelanggan,
							$row->jasa_pengiriman,
							$row->no_resi,
							$tgl,
							$sts,
							anchor('sales_order/detail/'.e_url($row->id_transaksi),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
							.'&nbsp;'
						);
			}
		}
		return $this->table->generate();
	}

	public function load_detail_demografi()
	{
		$whr = $this->input->post('whr');
		$id = $this->input->post('id');
		$data = [];
		/*	$map = json_decode(file_get_contents(base_url('assets/mapping.json')), true);
		print_pre($map);
		$idp = array_search('id-ac',$map);
		echo $idp;
		print_pre(get_kota('11'));*/
		if($this->input->post('id')){

			$map = json_decode(file_get_contents(base_url('assets/mapping.json')), true);
			$idp = array_search($id,$map);
			$whr .= " AND SUBSTR(c.alamat, 1, 2) = '$idp' ";
			$sql = "SELECT 
						SUBSTR(c.alamat, 4, 4) AS kota,
						SUBSTR(c.alamat, 1, 2) AS prov, 
						SUM(b.jumlah_order) AS jml 
					FROM sales_order a 
					JOIN sales_order_detail b ON a.id_transaksi = b.id_transaksi 
					JOIN pelanggan c ON a.no_pelanggan = c.no_pelanggan 
					WHERE 1=1 $whr
					GROUP BY SUBSTR(c.alamat, 4, 4)";
	        $q = $this->db->query($sql);
	        $res = $q->result();
	        foreach ($res as $row) {
	        	$kota = $row->kota;
	        	if(isset(get_kota($row->kota)['nama'])){
	        		$kota = get_kota($row->kota)["nama"];
	        	}
	        	$data[$row->kota] = array(
	        					"id_kota" => $row->kota,
	        					"kota" => $kota,
	        					"value" => $row->jml
	        					);
	        }
		}
        $label = [];
        $dada = [];
        foreach ($data as $k => $v) {
        	$label[] = $v["kota"];
        	$dada[] = $v["value"];
        }
        $return["detail"] = array(
        				'label' => $label,
        				'data' => $dada,
        			);
        $this->load->view('chart/demograpi_detail_view', $return);
	}
}

/* End of file Dahsboard.php */
/* Location: ./application/controllers/Dahsboard.php */