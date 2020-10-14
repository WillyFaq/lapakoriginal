<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengiriman extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Pengiriman_model", "", TRUE);
		$this->load->model("Gudang_barang_model", "", TRUE);
		$this->load->model("Gudang_user_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Pengiriman_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Id Transaksi', 'Nama Pelanggan', 'Nama Barang', 'Jasa Pengiriman', 'No Resi', 'Tgl Pengiriman', 'Status', 'Aksi');

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
					$btn_update = '';
				}
				$tgl = '';
				if($row->tgl_kirim!=null){
					$tgl = date("d-m-Y", strtotime($row->tgl_kirim));
				}
				$this->table->add_row(	++$i,
							$row->id_transaksi,
							$row->nama_pelanggan,
							$row->nama_barang,
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
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "pengiriman_view",
						"ket"  => "Data",
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
	}

	public function gen_table_belum()
	{
		$query=$this->Sales_order_model->get_where(["status_order" => "0"]);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Id Transaksi', 'Nama Pelanggan', 'Nama Barang', 'Jumlah', 'Total', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-danger">Belum dikirim</span>';
				if($sts==1){
					$sts = '<span class="badge badge-success">Sudah dikirim</span>';
				}
				$this->table->add_row(	++$i,
							$row->id_transaksi,
							$row->nama_pelanggan,
							$row->nama_barang,
							number_format($row->jumlah_order),
							'Rp. '.number_format($row->total_order),
							$sts,
							anchor('pengiriman/acc/'.e_url($row->id_transaksi),'<span class="fa fa-box"></span>',array( 'title' => 'Kirim', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'))
						);
			}
		}
		return  $this->table->generate();
	}

	public function belum(){
		$data = array(
						"page" => "pengiriman_view",
						"ket"  => "Data",
						"table" => $this->gen_table_belum()
						);
		$this->load->view('index', $data);
	}



	public function acc($v=""){
		
		$data = array(
						"page" => "pengiriman_view",
						"ket"  => "Acc",
						"form" => "pengiriman/acc_add"
						);
		$v = d_url($v);
		$q = $this->Sales_order_model->get_data($v);
		$res = $q->result();
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

			$data["kode_barang"] = $row->kode_barang;
			$detail["Nama Barang"] = $row->nama_barang;
			$detail["Harga Barang"] = "Rp. ".number_format($row->harga_order);
			$data["jumlah"] = $row->jumlah_order;
			$detail["Jumlah"] = number_format($row->jumlah_order);
			$detail["Total"] = "Rp. ".number_format($row->total_order);
			$detail["Keterangan"] = $row->keterangan;
		}
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
			redirect('pengiriman');
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
				$kot = get_kota($alamat[1])['nama'];
				$kec = get_kecamatan($alamat[2])['nama'];
				$detail['Data Transaksi']["Alamat"] = $alamat[3].", $kec, $kot, $prov";
			}
			$detail['Data Transaksi']["Nama Barang"] = $row->nama_barang;
			$detail['Data Transaksi']["Harga Barang"] = "Rp. ".number_format($row->harga_order);
			$detail['Data Transaksi']["Jumlah"] = number_format($row->jumlah_order);
			$detail['Data Transaksi']["Total"] = "Rp. ".number_format($row->total_order);
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
				$detail['Data Pengiriman']['Nama Gudang'] = $row->nama_gudang;
				$detail['Data Pengiriman']['Jasa Pengiriman'] = $row->jasa_pengiriman;
				$detail['Data Pengiriman']['No Resi'] = $row->no_resi;
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
		$data = ["status_pengiriman" => 3];
		if($this->Pengiriman_model->update($data, $id_pengiriman)){
			alert_notif("success");
			redirect('pengiriman');
		}else{
			alert_notif("danger");
			redirect('pengiriman/ubah/'.e_url($id_pengiriman));
		}
	}

	public function gen_table_gudang($kode, $bth)
	{
		$query=$this->Gudang_barang_model->get_gudang_barang($kode);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTableModal">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Nama Gudang', 'Stok', 'Ket', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;
			
			foreach ($res as $row){
				$ket = "";
				$kt = 1;
				if($row->stok<$bth){
					$ket = '<span class="badge badge-warning">Stok kurang</span>';
					$kt = 0;
				}
				$this->table->add_row(	++$i,
							$row->nama_gudang,
							$row->stok,
							$ket,
							'<button type="button" onclick="pilih_gudang(\''.$row->id_gudang.'\', \''.$row->nama_gudang.'\', '.$kt.')" class="btn btn-xs btn-success" data-toggle="tooltip" title="Pilih"><i class="fa fa-check"></i></button>'
				);
			}
		}
		echo $this->table->generate();
		init_datatable_tooltips();
	}
}

/* End of file Pengiriman.php */
/* Location: ./application/controllers/Pengiriman.php */