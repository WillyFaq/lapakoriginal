<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengiriman extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Pengiriman_model", "", TRUE);
		$this->load->model("Gudang_model", "", TRUE);
		$this->load->model("Gudang_barang_model", "", TRUE);
		$this->load->model("Gudang_user_model", "", TRUE);
		$this->load->model("Sales_model", "", TRUE);
		$this->load->model("Barang_model", "", TRUE);
	}

	public function gen_table($aj = "")
	{
		
		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dtTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama Sales', 'Nama Pelanggan', 'Jasa Pengiriman', 'No Resi', 'Tgl Pengiriman', 'Status', 'Aksi');

		if($aj==""){

			$data = $this->get_table();

			foreach ($data['data'] as $k => $v) {
				$this->table->add_row(
										$v
									);
			}
		}

		return  $this->table->generate();
	}

	public function get_table($aj = "1")
	{
		$ret = [];
		$totalRecords = 0;
		$totalRecordwithFilter = 0;
		$query = $this->Pengiriman_model->get_all();
		$totalRecords = $query->num_rows();
		if($this->input->post("cariSts")){
			$sts = $this->input->post("cariSts");
			$sts = $sts=="ac"?"0":$sts;
			
			if($sts == "3"){
				$query = $this->Pengiriman_model->get_where_like2(array(
																"pengiriman.status_pengiriman" => "3",
																"pengiriman.status_pengiriman" => "4",
															));
			}else{
				$query = $this->Pengiriman_model->get_where2(array("pengiriman.status_pengiriman" => $sts));
			}

		}else if($this->input->post("search")){
			$sts = $this->input->post("search")['value'];
			$query = $this->Pengiriman_model->get_where_like2(
														array(
															"user.nama" => $sts,
															"pelanggan.nama_pelanggan" => $sts,
															"pengiriman.jasa_pengiriman" => $sts,
															"pengiriman.no_resi" => $sts,
															"pengiriman.tgl_kirim" => $sts,
														)
													);
		}
		$res = $query->result();

		$num_rows = $query->num_rows();
		$totalRecordwithFilter = $num_rows;

		if ($num_rows > 0){
			$i = 0;
			foreach ($res as $row){
				$sts = '<span class="badge badge-warning">Sudah di acc</span>';
				$btn_update = anchor('pengiriman/tambah/'.e_url($row->id_pengiriman),'<span class="fas fa-paper-plane"></span>',array( 'title' => 'Kirim', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
				if($row->status_pengiriman==1){
					$sts = '<span class="badge badge-info">Sudah dikirim</span>';
					$btn_update = anchor('pengiriman/terima/'.e_url($row->id_pengiriman),'<span class="fa fa-check"></span>',array( 'title' => 'Diterima', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'
										));
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
				$ret[] = [
							++$i,
							$row->nama,
							$row->nama_pelanggan,
							$row->jasa_pengiriman,
							$row->no_resi,
							$tgl,
							$sts,
							anchor('pengiriman/detail/'.e_url($row->id_pengiriman),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
							.'&nbsp;'.
							$btn_update
							];
				if($i>=1000){break;}
			}
		}
		if($aj=="1"){
			//print_pre($ret);
			return $ret;
		}else{

			## Read value
			$draw = $this->input->post('draw');
			$row = $this->input->post('start');
			$rowperpage = $this->input->post('length'); // Rows display per page
			$columnIndex = $this->input->post('order')[0]['column']; // Column index
			$columnName = $this->input->post('columns')[$columnIndex]['data']; // Column name
			$columnSortOrder = $this->input->post('order')[0]['dir']; // asc or desc
			$searchValue = $this->input->post('search')['value']; // Search value

			$response = array(
			  	"draw" => intval($draw),
			  	"iTotalRecords" => $totalRecords,
			  	"iTotalDisplayRecords" => $totalRecordwithFilter,
			  	"aaData" => $ret,
			  	"db_last" => $this->db->last_query()
			);
			echo json_encode($response);
		}
	}

	public function index()
	{
		$data = array(
						"page" => "pengiriman_view",
						"ket"  => "Data",
						"table" => $this->gen_table("ajax"),
						"utama" => 1
						);
		$this->load->view('index', $data);
	}

	public function gen_table_belum()
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

	public function tolak_so($v='')
	{
		$v = d_url($v);
		if($this->Sales_order_model->update(array("status_order" => 4), $v )){
			if($this->Pengiriman_model->delete_pending($v)){
				alert_notif("success");
				redirect('');
			}else{
				alert_notif("danger");
				redirect('pengiriman/belum/');
			}
		}else{
			alert_notif("danger");
			redirect('pengiriman/belum/');
		}
	}

	public function belum(){
		$data = array(
						"page" => "pengiriman_view",
						"ket"  => "Data",
						"table" => $this->gen_table_belum()
						);
		$this->load->view('index', $data);
	}

	public function cb_barang($id, $sel='')
	{
		$ret = '<div class="form-group row"><label for="nama" class="col-sm-2 col-form-label">Barang</label><div class="col-sm-10">';
		//$id = $this->session->userdata("user")->id_user;
		$q = $this->Sales_model->get_data($id);
		$res = $q->result();
		foreach ($res as $row) {
			$opt[$row->kode_barang] = $row->nama_barang;
		}
		$js = 'class="form-control" id="kode_barang"';
		$ret= $ret.''.form_dropdown('kode_barang',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		return $ret;
	}



	public function cb_provinsi($sel='')
	{
		$ret = '<div class="form-group row"><label for="provinsi" class="col-sm-2 col-form-label">Provinsi</label><div class="col-sm-10">';
		$res = get_provinsi();
		foreach ($res as $k => $row) {
			$opt[$row['id']] = $row['nama'];
		}
		$js = 'class="form-control cb_provinsi" id="provinsi"';
		$ret= $ret.''.form_dropdown('provinsi',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		return $ret;
	}

	public function cb_kota($id='', $sel='')
	{
		$ret = '<div class="form-group row"><label for="kota" class="col-sm-2 col-form-label">Kabupaten/Kota</label><div class="col-sm-10">';
		$res = get_kota($id);
		foreach ($res as $k => $row) {
			$opt[$row['id']] = $row['nama'];
		}
		$js = 'class="form-control cb_kota" id="kota"';
		$ret= $ret.''.form_dropdown('kota',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		echo $ret;
	}

	public function cb_kecamatan($id='', $sel='')
	{
		$ret = '<div class="form-group row"><label for="kecamatan" class="col-sm-2 col-form-label">Kecamatan/Kota</label><div class="col-sm-10">';
		$res = get_kecamatan($id);
		foreach ($res as $k => $row) {
			$opt[$row['id']] = $row['nama'];
		}
		$js = 'class="form-control cb_kecamatan" id="kecamatan"';
		$ret= $ret.''.form_dropdown('kecamatan',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		echo $ret;
	}

	public function get_harga()
	{
		$kode = $this->input->post('kode_barang');
		$q = $this->Barang_model->get_data($kode)->row();
		echo $q->harga_jual;
	}

	public function ubah_ajax($v='')
	{
		$v = d_url($v);
		$data = array(
						"page" => "ajax_ubah_trans",
						"ket"  => "Acc",
						"form" => "pengiriman/update_ajax",
						);
		$q = $this->Sales_order_model->get_data($v);
		$res = $q->result();
		$detail = [];
		foreach ($res as $row) {
			$data["cb_barang"] = $this->cb_barang($row->id_user, $row->kode_barang);
			$data["id_transaksi"] = $row->id_transaksi;
			$data["no_pelanggan"] = $row->no_pelanggan;
			$data["nama_pelanggan"] = $row->nama_pelanggan;
			$data["notelp"] = $row->notelp;

			$alamat = explode("|", $row->alamat);

			if(sizeof($alamat)==1){
				$data["alamat"] = $row->alamat;
			}else{
				
				$data["alamat"] = $alamat[3];
				$data["cb_provinsi"] = $this->cb_provinsi($alamat[0]);
				$data["provinsi"] = $alamat[0];
				$data["kota"] = $alamat[1];
				$data["kecamatan"] = $alamat[2];
			}
			

			$data["kode_barang"] = trim($row->kode_barang);
			$data["harga_barang"] = $row->harga_order;
			$data["jumlah_beli"] = $row->jumlah_order;
			$data["total"] = $row->total_order;
			$data["keterangan"] = $row->keterangan;
		}

		$this->load->view('ajax_ubah_trans', $data);
	}

	public function update_ajax()
	{
		$data = $this->input->post();
		//print_r($data);
		$id_transaksi = $data['id_transaksi'];
		$no_pelanggan = $data['no_pelanggan'];
		unset($data['id_transaksi']);
		unset($data['no_pelanggan']);

		$provinsi = $data["provinsi"];
		$kota = $data["kota"];
		$kecamatan = $data["kecamatan"];
		$alamat = "$provinsi|$kota|$kecamatan|".$data["alamat"];

		$pelanggan = array(
							'nama_pelanggan' => $data['nama_pelanggan'],
							'notelp' => $data['notelp'],
							'alamat' => $alamat,
							);
		$transaksi = array(
						
						"kode_barang" => $data["kode_barang"],
						"harga_order" => $data["harga_barang"],
						"jumlah_order" => $data["jumlah_beli"],
						"total_order" => $data["harga_barang"] * $data["jumlah_beli"],
						"keterangan" => $data["keterangan"],
						);

		//print_pre($transaksi);
		if($this->Pengiriman_model->ubah_transaksi($no_pelanggan, $pelanggan, $id_transaksi, $transaksi)){
			alert_notif("success");
			redirect('pengiriman/acc/'.e_url($id_transaksi));
		}else{
			alert_notif("danger");
			redirect('pengiriman/acc/'.e_url($id_transaksi));
		}
	}

	public function acc($v=""){
		
		$data = array(
						"page" => "pengiriman_view",
						"ket"  => "Acc",
						"form" => "pengiriman/acc_add",
						"add" => anchor('', '<i class="fas fa-pencil-alt"></i>', array("class" => "btn btn-success btn_ubah_trans", "id" => $v, "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Ubah Data")),
						);
		$v = d_url($v);
		$q = $this->Sales_order_model->get_data($v);
		$res = $q->result();
		$detail = [];
		$kde = [];
		foreach ($res as $row) {
			$detail["Id Transaksi"] = $row->id_transaksi;
			$detail["Nama Pelanggan"] = $row->nama_pelanggan;
			$detail["No Telp"] = $row->notelp;
			
			$alamat = explode("|", $row->alamat);
			if(sizeof($alamat)==1){
				$detail["Alamat"] = $row->alamat;
			}else{
				$prov = get_provinsi($alamat[0])['nama'];
				$kot = get_kota($alamat[1])['nama'];
				$kec = get_kecamatan($alamat[2])['nama'];
				$detail["Alamat"] = $alamat[3].", $kec, $kot, $prov";
			}
			$detail["total_order"] = "Rp. ".number_format($row->total_order);
			$nama_barang = $row->nama_barang;
			$kd = explode(".", $row->kode_brg);
			if(sizeof($kd)>1){
				$nama_barang .= isset($kd[1])?"<br>($kd[1]":"";
				$nama_barang .= isset($kd[2])?" - $kd[2]":"";
				$nama_barang .= ")";
			}
			$kde[] = $row->kode_barang;
			$detail["order"][] = array(
										"barang" => $nama_barang,
										"harga_barang" => "Rp. ".number_format($row->harga_order),
										"potongan" => "Rp. ".number_format($row->potongan_order),
										"jumlah_order" => number_format($row->jumlah_order),
										"subtotal" => "Rp. ".number_format($row->subtotal_order),
										);

			/*$data["kode_barang"] = trim($row->kode_barang);
			$detail["Nama Barang"] = $row->nama_barang;
			$detail["Harga Barang"] = "Rp. ".number_format($row->harga_order);
			$data["jumlah"] = $row->jumlah_order;
			$detail["Jumlah"] = number_format($row->jumlah_order);
			$detail["Total"] = "Rp. ".number_format($row->total_order);*/
		}
		$data['kode_barang'] = e_url("'".join("', '", $kde)."'");
		$data["transaksi"] = $detail;
		$this->load->view('index', $data);
	}

	public function tambah($v=""){
		
		$data = array(
						"page" => "pengiriman_view",
						"ket"  => "Tambah",
						"form" => "pengiriman/add"
						);
		$v = d_url($v);

		$q = $this->Pengiriman_model->get_data($v);
		$res = $q->result();
		$detail = [];
		foreach ($res as $row) {
			$id_transaksi = $row->id_transaksi;
			$data['id_pengiriman'] = $row->id_pengiriman;
			$id_gudang = $row->id_gudang;
		}
		$qa = $this->Gudang_user_model->get_where(array('id_gudang' => $id_gudang, 'id_user' => $this->session->userdata('user')->id_user));
		$qres = $qa->result();
		foreach ($qres as $row) {
			$data['id_gudang_user'] = $row->id_gudang_user;
		}

		$qq = $this->Sales_order_model->get_data($id_transaksi);
		$res = $qq->result();
		$detail = [];
		foreach ($res as $row) {
			$detail["Id Transaksi"] = $row->id_transaksi;
			$detail["Nama Pelanggan"] = $row->nama_pelanggan;
			$detail["No Telp"] = $row->notelp;
			
			$alamat = explode("|", $row->alamat);
			if(sizeof($alamat)==1){
				$detail["Alamat"] = $row->alamat;
			}else{
				$prov = get_provinsi($alamat[0])['nama'];
				$kot = get_kota($alamat[1])['nama'];
				$kec = get_kecamatan($alamat[2])['nama'];
				$detail["Alamat"] = $alamat[3].", $kec, $kot, $prov";
			}

			$data['kode_barang'] = $row->kode_barang;
			$data['jumlah'] = $row->jumlah_order;
			$detail["Nama Barang"] = $row->nama_barang;
			$detail["Harga Barang"] = "Rp. ".number_format($row->harga_order);
			$detail["Jumlah"] = number_format($row->jumlah_order);
			$detail["Total"] = "Rp. ".number_format($row->total_order);
			$detail["Keterangan"] = $row->keterangan;
		}

		$data["transaksi"] = $detail;
		$this->load->view('index', $data);
	}

	public function acc_add(){
		$data = $this->input->post();
		unset($data['nama_gudang']);
		unset($data['id_pengiriman']);
		unset($data['btnSimpan']);
		$data['id_user'] = $this->session->userdata('user')->id_user;
		
		if($this->Pengiriman_model->add($data)){
			alert_notif("success");
			redirect('');
		}else{
			alert_notif("danger");
			redirect('pengiriman/tambah/'.e_url($data['id_transaksi']));
		}
	}

	public function add(){
		$data = $this->input->post();
		$data['status_pengiriman'] = 1;
		//	unset($data['id_pengiriman']);
		unset($data['btnSimpan']);
		$data['id_user'] = $this->session->userdata('user')->id_user;
		//print_pre($data);
		if($this->Pengiriman_model->add_kirim($data)){
			alert_notif("success");
			redirect('pengiriman');
		}else{
			alert_notif("danger");
			redirect('pengiriman/tambah/'.e_url($data['id_transaksi']));
		}
	}

	public function detail($v){
		$v = d_url($v);
		$data = array(
						"page" => "pengiriman_view",
						"ket" => "Detail Data",
						);
		$q = $this->Pengiriman_model->get_data($v);
		$res = $q->result();
		$detail = [];
		foreach ($res as $row) {
			$id_transaksi = $row->id_transaksi;
		}
		$qa = $this->Sales_order_model->get_data($id_transaksi);
		$res = $qa->result();
		foreach ($res as $row) {
			$detail['Data Transaksi']["Id Transaksi"] = $row->id_transaksi;
			$detail['Data Transaksi']["Nama Pelanggan"] = $row->nama_pelanggan;
			$detail['Data Transaksi']["No Telp"] = $row->notelp;
			//$detail['Data Transaksi']["Alamat"] = $row->alamat;
			$alamat = explode("|", $row->alamat);
			if(sizeof($alamat)==1){
				$detail['Data Transaksi']["Alamat"] = $row->alamat;
			}else{
				$prov = get_provinsi($alamat[0])['nama'];
				$kot = '';
				//echo strlen($alamat[1]);
				if(isset($alamat[1]) && strlen($alamat[1])==4){
					$kot = get_kota($alamat[1])['nama'];
				}
				$kec = '';
				if(isset($alamat[2]) && strlen($alamat[2])==7){
					$kec = get_kecamatan($alamat[2])['nama'];
				}
				$alm = isset($alamat[3])?$alamat[3]:$alamat[1];

				$detail['Data Transaksi']["Alamat"] = $alm.", $kec, $kot, $prov";
			}
			/*$detail['Data Transaksi']["Nama Barang"] = $row->nama_barang;
			$detail['Data Transaksi']["Harga Barang"] = "Rp. ".number_format($row->harga_order);
			$detail['Data Transaksi']["Jumlah"] = number_format($row->jumlah_order);
			$detail['Data Transaksi']["Total"] = "Rp. ".number_format($row->total_order);
			*/
			$detail['Data Transaksi']["total_order"] = "Rp. ".number_format($row->total_order);
			$nama_barang = $row->nama_barang;
			$kd = explode(".", $row->kode_brg);
			if(sizeof($kd)>1){
				$nama_barang .= isset($kd[1])?"<br>($kd[1]":"";
				$nama_barang .= isset($kd[2])?" - $kd[2]":"";
				$nama_barang .= ")";
			}
			$kde[] = $row->kode_barang;
			$detail['Detail Transaksi'][] = array(
										"kode_barang" => $row->kode_barang,
										"kode_brg" => $row->kode_brg,
										"jmlh" => $row->jumlah_order,
										"barang" => $nama_barang,
										"harga_barang" => "Rp. ".number_format($row->harga_order),
										"potongan" => "Rp. ".number_format($row->potongan_order),
										"jumlah_order" => number_format($row->jumlah_order),
										"subtotal" => "Rp. ".number_format($row->subtotal_order),
										);

			if($row->status_order!=1){
				$detail['Data Transaksi']["Status"] = '<span class="badge badge-danger">Belum diproses</span>';
			}
			//$detail['Data Transaksi']["Status"] = $row->status_order==1?'<span class="badge badge-success">Sudah dikirim</span>':'<span class="badge badge-danger">Belum dikirim</span>';
		}
		$res = $q->result();
		foreach ($res as $row) {
			if($row->status_pengiriman==0){
				$detail['Data Pengiriman']['Nama Gudang'] = $row->nama_gudang;
			}else{
				$no_resi = "<div class='box_editable'>";
				$no_resi .= "<span class='editable-txt' data-id='$row->id_pengiriman' data-val='$row->no_resi'>".$row->no_resi."</span>";
				$no_resi .= form_open('pengiriman/edit_no_resi', array("id"=>"frm_noresi"), array("id_pengiriman" => $row->id_pengiriman));
				$no_resi .= "<input type='text' name='no_resi_edit' id='no_resi_edit' value='$row->no_resi' style='display:none'>";
				$no_resi .= "</form>";
				$no_resi .= "</div>";
				$detail['Data Pengiriman']['Nama Gudang'] = $row->nama_gudang;
				$detail['Data Pengiriman']['Jasa Pengiriman'] = $row->jasa_pengiriman;
				$detail['Data Pengiriman']['No Resi'] = $no_resi;
				$detail['Data Pengiriman']['Tgl Kirim'] = date("d-m-Y", strtotime($row->tgl_kirim));
				$detail['Data Pengiriman']['Pengirim'] = $row->nama;
			}
			
			$sts = '<span class="badge badge-warning">Sudah di acc</span>';
			
			if($row->status_pengiriman==1){
				$sts = '<span class="badge badge-info">Sudah dikirim</span>';
			}else if($row->status_pengiriman==2){
				$sts = '<span class="badge badge-info">Sudah diterima</span>';
			}else if($row->status_pengiriman>=3){
				$sts = '<span class="badge badge-danger">Ditolak</span>';
			}
			
			$detail['Data Pengiriman']["Status Pengiriman"] = $sts;
		}
		$data["detail"] = $detail;
		$this->load->view('index', $data);
	}



	public function ubah($v=""){
		
		$data = array(
						"page" => "pengiriman_view",
						"ket"  => "Ubah",
						"form" => "pengiriman/update"
						);
		$v = d_url($v);
		$q = $this->Pengiriman_model->get_data($v);
		$res = $q->result();
		$detail = [];
		foreach ($res as $row) {
			$id_transaksi = $row->id_transaksi;
			$data['id_pengiriman'] = $row->id_pengiriman;
			$data['jasa_pengiriman'] = $row->jasa_pengiriman;
			$data['no_resi'] = $row->no_resi;
			$data['tgl_kirim'] = $row->tgl_kirim;
		}

		$q = $this->Sales_order_model->get_data($id_transaksi);
		$res = $q->result();
		$detail = [];
		foreach ($res as $row) {
			$detail["Id Transaksi"] = $row->id_transaksi;
			$detail["Nama Pelanggan"] = $row->nama_pelanggan;
			$detail["No Telp"] = $row->notelp;
			$detail["Alamat"] = $row->alamat;
			$detail["Nama Barang"] = $row->nama_barang;
			$detail["Harga Barang"] = "Rp. ".number_format($row->harga_order);
			$detail["Jumlah"] = number_format($row->jumlah_order);
			$detail["Total"] = "Rp. ".number_format($row->total_order);
		}
		$data["transaksi"] = $detail;
		$this->load->view('index', $data);
	}

	public function update()
	{
		$id_pengiriman = $this->input->post("id_pengiriman");
		$data = ["status_pengiriman" => 1];
		if($this->Pengiriman_model->update($data, $id_pengiriman)){
			alert_notif("success");
			redirect('pengiriman');
		}else{
			alert_notif("danger");
			redirect('pengiriman/ubah/'.e_url($id_pengiriman));
		}
	}

	public function terima($id='')
	{
		$id_pengiriman = d_url($id);
		$data = ["status_pengiriman" => 2];
		if($this->Pengiriman_model->update($data, $id_pengiriman)){
			alert_notif("success");
			redirect('pengiriman');
		}else{
			alert_notif("danger");
			redirect('pengiriman/ubah/'.e_url($id_pengiriman));
		}
	}

	public function tolak($id='')
	{
		$id_pengiriman = d_url($id);
		$ids = $this->session->userdata('user')->id_user;
		$sql = "SELECT * FROM gudang_user WHERE id_user = '$ids'";
		$q = $this->db->query($sql);
		$res = $q->result();
		$id_gudang_user = $res[0]->id_gudang_user;


		$sql = "SELECT * FROM pengiriman a 
				JOIN sales_order b ON a.id_transaksi = b.id_transaksi 
				JOIN sales_order_detail c ON b.id_transaksi = c.id_transaksi
				WHERE a.id_pengiriman = '$id_pengiriman'";
		$q = $this->db->query($sql);
		$res = $q->result();
		$data_gb = [];
		foreach ($res as $row) {
			$data_gb[] = array(
						'id_gudang_user' => $id_gudang_user,
						'kode_barang' => $row->kode_barang,
						'kode_brg' => $row->kode_brg,
						'tgl_gb' => date("Y-m-d H:i:s"),
						'jumlah_gb' => $row->jumlah_order,
						'ket_gb' => 3,
					);
		}
		//print_pre($data_gb);

		$this->db->trans_begin();

		$this->db->set(['status_pengiriman' => 4]);
		$this->db->where("id_pengiriman", $id_pengiriman);
		$this->db->update("pengiriman");

		$this->db->insert_batch("gudang_barang", $data_gb);
		
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    alert_notif("danger");
			redirect('pengiriman/ubah/'.e_url($id_pengiriman));
		}else{
		    $this->db->trans_commit();
		    alert_notif("success");
			redirect('pengiriman');
		}

		/*$kode_barang = $res[0]->kode_barang;
		$jumlah_order = $res[0]->jumlah_order;
		
		echo $id_gudang_user.'<br>';
		echo $kode_barang.'<br>';
		echo $jumlah_order.'<br>';

		$data_gb = array(
						'id_gudang_user' => $id_gudang_user,
						'kode_barang' => $kode_barang,
						'tgl_gb' => date("Y-m-d H:i:s"),
						'jumlah_gb' => $jumlah_order,
						'ket_gb' => 3,
					);
		print_pre($data_gb);*/
		/*$data = ["status_pengiriman" => 4];
		if($this->Pengiriman_model->update($data, $id_pengiriman)){
			if($this->Gudang_barang_model->add($data_gb)){
			
				alert_notif("success");
				redirect('pengiriman');
			}else{
				alert_notif("danger");
				redirect('pengiriman/ubah/'.e_url($id_pengiriman));
			}
		}else{
			alert_notif("danger");
			redirect('pengiriman/ubah/'.e_url($id_pengiriman));
		}*/
	}

	public function gen_table_gudang($kode, $bth=0)
	{
		$kode = d_url($kode);
		$query=$this->Gudang_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTableModal">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Nama Gudang', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;
			
			foreach ($res as $row){
				$kt = 1;
				/*$ket = "";
				if($row->stok<$bth){
					$ket = '<span class="badge badge-warning">Stok kurang</span>';
					$kt = 0;
				}*/
				$this->table->add_row(	++$i,
							$row->nama_gudang,
							'<button type="button" onclick="pilih_gudang(\''.$row->id_gudang.'\', \''.$row->nama_gudang.'\', '.$kt.')" class="btn btn-xs btn-success" data-toggle="tooltip" title="Pilih"><i class="fa fa-check"></i></button>'
				);
				/*
$row->stok,
							$ket,
				*/
			}
		}
		echo $this->table->generate();
		init_datatable_tooltips();
	}

	public function kirimkan($v=""){
		
		$data = array(
						"page" => "pengiriman_view",
						"ket"  => "Kirim",
						"form" => "pengiriman/kirmkan_add",
						//"add" => anchor('', '<i class="fas fa-pencil-alt"></i>', array("class" => "btn btn-success btn_ubah_trans", "id" => $v, "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Ubah Data")),
						);
		$v = d_url($v);
		$q = $this->Sales_order_model->get_data($v);
		$res = $q->result();
		$detail = [];
		$kde = [];
		foreach ($res as $row) {
			$detail["Id Transaksi"] = $row->id_transaksi;
			$detail["Nama Pelanggan"] = $row->nama_pelanggan;
			$detail["No Telp"] = $row->notelp;
			$data["jasa_pengiriman"] = $row->jasa_pengiriman;
			
			$alamat = explode("|", $row->alamat);
			if(sizeof($alamat)==1){
				$detail["Alamat"] = $row->alamat;
			}else{
				$prov = get_provinsi($alamat[0])['nama'];
				$kot = get_kota($alamat[1])['nama'];
				$kec = get_kecamatan($alamat[2])['nama'];
				$detail["Alamat"] = $alamat[3].", $kec, $kot, $prov";
			}
			$detail["total_order"] = "Rp. ".number_format($row->total_order);
			$nama_barang = $row->nama_barang;
			$kd = explode(".", $row->kode_brg);
			if(sizeof($kd)>1){
				$nama_barang .= isset($kd[1])?"<br>($kd[1]":"";
				$nama_barang .= isset($kd[2])?" - $kd[2]":"";
				$nama_barang .= ")";
			}
			$kde[] = $row->kode_barang;
			$detail["order"][] = array(
										"kode_barang" => $row->kode_barang,
										"kode_brg" => $row->kode_brg,
										"jmlh" => $row->jumlah_order,
										"barang" => $nama_barang,
										"harga_barang" => "Rp. ".number_format($row->harga_order),
										"potongan" => "Rp. ".number_format($row->potongan_order),
										"jumlah_order" => number_format($row->jumlah_order),
										"subtotal" => "Rp. ".number_format($row->subtotal_order),
										);

			/*$data["kode_barang"] = trim($row->kode_barang);
			$detail["Nama Barang"] = $row->nama_barang;
			$detail["Harga Barang"] = "Rp. ".number_format($row->harga_order);
			$data["jumlah"] = $row->jumlah_order;
			$detail["Jumlah"] = number_format($row->jumlah_order);
			$detail["Total"] = "Rp. ".number_format($row->total_order);*/
		}
		$data['kode_barang'] = e_url("'".join("', '", $kde)."'");
		$data["transaksi"] = $detail;
		$this->load->view('index', $data);
	}

	public function kirmkan_add()
	{
		$data = $this->input->post();
		print_pre($data);
		$id_user = $this->session->userdata("user")->id_user;
		$pengiriman = array(
							"id_user" => $id_user,
							"id_transaksi" => $data['id_transaksi'],
							"id_gudang" => $data['id_gudang'],
							"tgl_kirim" => $data['tgl_kirim'],
							"jasa_pengiriman" => $data['jasa_pengiriman'],
							"no_resi" => $data['no_resi'],
							"status_pengiriman" => 1,
							);

		$q = $this->Gudang_user_model->get_where(array("id_user" => $id_user, "id_gudang" => $data['id_gudang']));
		$res = $q->result();
		$igu = $res[0]->id_gudang_user;
		$gudang = [];
		foreach($data['kode_barang'] as $k => $v){

			$gudang[] = array(
							"id_gudang_user" => $igu,
							"kode_barang" => $v,
							"kode_brg" => $data["kode_brg"][$k],
							"jumlah_gb" => $data["jmlh"][$k],
							"tgl_gb" => $data["tgl_kirim"],
							"ket_gb" => 2,
							);

		}
		/*print_pre($pengiriman);
		print_pre($gudang);*/
		if($this->Pengiriman_model->kirimkan($pengiriman, $gudang)){
			alert_notif("success");
			redirect('');
		}else{
			alert_notif("danger");
			redirect('pengiriman/belum/');
		}
	}

	public function kirim_ulang($v='')
	{
		$data = array(
						"page" => "pengiriman_view",
						"ket"  => "Kirim Ulang",
						"form" => "pengiriman/kirmkan_add",
						//"add" => anchor('', '<i class="fas fa-pencil-alt"></i>', array("class" => "btn btn-success btn_ubah_trans", "id" => $v, "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Ubah Data")),
						);
		$v = d_url($v);
		$q = $this->Sales_order_model->get_data($v);
		$res = $q->result();
		$detail = [];
		$kde = [];
		foreach ($res as $row) {
			$detail["Id Transaksi"] = $row->id_transaksi;
			$detail["Nama Pelanggan"] = $row->nama_pelanggan;
			$detail["No Telp"] = $row->notelp;
			$data["jasa_pengiriman"] = $row->jasa_pengiriman;
			
			$alamat = explode("|", $row->alamat);
			if(sizeof($alamat)==1){
				$detail["Alamat"] = $row->alamat;
			}else{
				$prov = get_provinsi($alamat[0])['nama'];
				$kot = get_kota($alamat[1])['nama'];
				$kec = get_kecamatan($alamat[2])['nama'];
				$detail["Alamat"] = $alamat[3].", $kec, $kot, $prov";
			}
			$detail["total_order"] = "Rp. ".number_format($row->total_order);
			$nama_barang = $row->nama_barang;
			$kd = explode(".", $row->kode_brg);
			if(sizeof($kd)>1){
				$nama_barang .= isset($kd[1])?"<br>($kd[1]":"";
				$nama_barang .= isset($kd[2])?" - $kd[2]":"";
				$nama_barang .= ")";
			}
			$kde[] = $row->kode_barang;
			$detail["order"][] = array(
										"kode_barang" => $row->kode_barang,
										"kode_brg" => $row->kode_brg,
										"jmlh" => $row->jumlah_order,
										"barang" => $nama_barang,
										"harga_barang" => "Rp. ".number_format($row->harga_order),
										"potongan" => "Rp. ".number_format($row->potongan_order),
										"jumlah_order" => number_format($row->jumlah_order),
										"subtotal" => "Rp. ".number_format($row->subtotal_order),
										);
		}
		$data['kode_barang'] = e_url("'".join("', '", $kde)."'");
		$data["transaksi"] = $detail;
		$this->load->view('index', $data);
	}

	public function pending($id)
	{
		$id = d_url($id);
		$sql = "INSERT INTO pending (id_transaksi) VALUES('$id')";
		if($this->Pengiriman_model->pending_add($id)){
			alert_notif("success");
			redirect('pengiriman/belum/');
		}else{
			alert_notif("danger");
			redirect('pengiriman/belum/');
		}
	}

	public function edit_no_resi()
	{
		$data = array("no_resi" => $this->input->post("no_resi_edit"));
		$idp = $this->input->post('id_pengiriman');
		unset($data['id_pengiriman']);
		if($this->Pengiriman_model->update($data, $idp)){
			//echo $this->db->last_query();
			alert_notif("success");
			redirect('pengiriman/detail/'.e_url($idp));
		}else{
			alert_notif("danger");
			redirect('pengiriman/detail/'.e_url($idp));
		}
	}

}

/* End of file Pengiriman.php */
/* Location: ./application/controllers/Pengiriman.php */