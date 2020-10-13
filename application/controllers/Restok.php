<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restok extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Barang_model", "", TRUE);
		$this->load->model("Gudang_user_model", "", TRUE);
		$this->load->model("Gudang_barang_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Gudang_barang_model->get_all_by_user($this->session->userdata("user")->id_user);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTableModal">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Kode Barang', 'Nama Barang', 'Tgl Restok', 'Jumlah', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$this->table->add_row(	++$i,
							$row->kode_barang,
							$row->nama_barang,
							date("d-m-Y", strtotime($row->tgl_gb)),
							number_format($row->jumlah_gb),
							anchor('restok/ubah/'.e_url($row->id_gb),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip'))
				);
			}
		}
		return $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "restok_view",
						"ket" => "Data",
						"add" => anchor('restok/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);	
	}

	public function tambah()
	{
		$data = array(
						"page" => "restok_view",
						"ket" => "Tambah Data ",
						"form" => "restok/add"
						);
		$q = $this->Gudang_user_model->get_by_user($this->session->userdata("user")->id_user);
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_gudang_user'] = $row->id_gudang_user;
		}
		$this->load->view('index', $data);	
	}


	public function gen_table_barang()
	{
		$query=$this->Barang_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTableModal">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Kode Barang', 'Nama Barang', 'Harga Jual', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$this->table->add_row(	++$i,
							$row->kode_barang,
							$row->nama_barang,
							'Rp. '.number_format($row->harga_jual),
							'<button type="button" onclick="pilih_barang(\''.$row->kode_barang.'\', \''.$row->nama_barang.'\')" class="btn btn-xs btn-success" data-toggle="tooltip" title="Pilih"><i class="fa fa-check"></i></button>'
				);
			}
		}
		echo $this->table->generate();
		init_datatable_tooltips();
	}

	public function add()
	{
		$data = $this->input->post();
		$data['ket_gb'] = '1';
		unset($data['id_gb']);
		unset($data['nama_barang']);
		unset($data['btnSimpan']);
		if($this->Gudang_barang_model->add($data)){
			alert_notif("success");
			redirect('restok');
		}else{
			alert_notif("danger");
			redirect('restok/tambah');
		}
	}

	public function ubah($id)
	{
		$id = d_url($id);
		$data = array(
						"page" => "restok_view",
						"ket" => "Ubah Data ",
						"form" => "restok/update"
						);
		$q = $this->Gudang_barang_model->get_all_by_user($this->session->userdata("user")->id_user);
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_gb'] = $row->id_gb;
			$data['id_gudang_user'] = $row->id_gudang_user;
			$data['kode_barang'] = $row->kode_barang;
			$data['nama_barang'] = $row->nama_barang;
			$data['tgl_gb'] = $row->tgl_gb;
			$data['jumlah_gb'] = $row->jumlah_gb;
		}
		$this->load->view('index', $data);	
		
	}

	public function update()
	{
		$id_gb = $this->input->post('id_gb'); 
		$data = $this->input->post();
		$data['ket_gb'] = '1';
		unset($data['id_gb']);
		unset($data['nama_barang']);
		unset($data['btnSimpan']);
		if($this->Gudang_barang_model->update($data, $id_gb)){
			alert_notif("success");
			redirect('restok');
		}else{
			alert_notif("danger");
			redirect('restok/ubah/'.e_url($id_gb));
		}
	}

}

/* End of file Restok.php */
/* Location: ./application/controllers/Restok.php */