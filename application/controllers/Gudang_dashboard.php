<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang_dashboard extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("User_model", "", TRUE);
		$this->load->model("Sales_model", "", TRUE);
		$this->load->model("Barang_model", "", TRUE);
		$this->load->model("Sales_order_model", "", TRUE);
		$this->load->model("Pengiriman_model", "", TRUE);
		$this->load->model("Gudang_barang_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Gudang_barang_model->get_where(array(
															'id_user' => $this->session->userdata("user")->id_user,
															'ket_gb' => 3,
														));
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Kode Barang', 'Nama Barang', 'Tgl Ditolak', 'Jumlah', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$this->table->add_row(	++$i,
							$row->kode_barang,
							$row->nama_barang,
							date("d-m-Y", strtotime($row->tgl_gb)),
							number_format($row->jumlah_gb),
							anchor('gudang_dashboard/detail/'.e_url($row->id_gb),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip'))
				);
			}
		}
		return $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "gudang_dashboard_view",
						"ket" => "Data",
						//"add" => anchor('barang/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
	}

	public function detail_kirim($v)
	{
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
			}else if($row->status_pengiriman==3){
				$sts = '<span class="badge badge-danger">Ditolak</span>';
			}
			
			$detail['Data Pengiriman']["Status Pengiriman"] = $sts;
		}
		$data["detail"] = $detail;
		$this->load->view('index', $data);
	}

	public function detail_ditolak($v)
	{
		$v = d_url($v);
		$data = array(
						"page" => "gudang_dashboard_view",
						"ket" => "Detail Data",
						);
		$q = $this->Pengiriman_model->get_data($v);
		$res = $q->result();
		$detail = [];
		foreach ($res as $row) {
			$id_transaksi = $row->id_transaksi;
			$data['id_pengiriman'] = e_url($row->id_pengiriman);
		}
		$qa = $this->Sales_order_model->get_data($id_transaksi);
		$res = $qa->result();
		foreach ($res as $row) {
			$detail['Data Transaksi']["Id Transaksi"] = $row->id_transaksi;
			$detail['Data Transaksi']["Nama Pelanggan"] = $row->nama_pelanggan;
			$detail['Data Transaksi']["No Telp"] = $row->notelp;
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
			}else if($row->status_pengiriman==3){
				$sts = '<span class="badge badge-danger">Ditolak</span>';
			}
			
			$detail['Data Pengiriman']["Status Pengiriman"] = $sts;
		}
		$data["detail"] = $detail;
		$this->load->view('index', $data);
	}

	public function konfirmasi_tolak($v)
	{
		$v = d_url($v);
		$ids = $this->session->userdata('user')->id_user;
		$sql = "SELECT * FROM gudang_user WHERE id_user = '$ids'";
		$q = $this->db->query($sql);
		$res = $q->result();
		$id_gudang_user = $res[0]->id_gudang_user;


		$sql = "SELECT * FROM pengiriman a JOIN sales_order b ON a.id_transaksi = b.id_transaksi WHERE a.id_pengiriman = '$v'";
		$q = $this->db->query($sql);
		$res = $q->result();
		$kode_barang = $res[0]->kode_barang;
		$jumlah_order = $res[0]->jumlah_order;

		$data = array(
						'id_gudang_user' => $id_gudang_user,
						'kode_barang' => $kode_barang,
						'tgl_gb' => date("Y-m-d H:i:s"),
						'jumlah_gb' => $jumlah_order,
						'ket_gb' => 3,
					);

		if($this->Gudang_barang_model->add($data)){
			$id_pengiriman = $v;
			$data = ["status_pengiriman" => 4];
			$this->Pengiriman_model->update($data, $id_pengiriman);
		
			alert_notif("success");
			redirect('gudang_dashboard');
		}else{
			alert_notif("danger");
			redirect('gudang_dashboard/detail_ditolak/'.e_url($v));
		}
	}

}

/* End of file Gudang_dashboard.php */
/* Location: ./application/controllers/Gudang_dashboard.php */