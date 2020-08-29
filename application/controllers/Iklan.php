<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iklan extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Barang_model", "", TRUE);
		$this->load->model("Iklan_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Iklan_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Kode Barang', 'Nama Barang', 'Tgl Iklan', 'Biaya Iklan', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$this->table->add_row(	++$i,
							$row->kode_barang,
							$row->nama_barang,
							$row->tgl_iklan,
							'Rp. '.number_format($row->biaya_iklan),
							anchor('iklan/ubah/'.e_url($row->id_iklan),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip')).'&nbsp;'.
							'<button type="button" class="btn btn-xs btn-danger btn-hapus" data-id="'.e_url($row->id_iklan).'" data-toggle="tooltip" title="Hapus" data-original-title="Hapus"><i class="fa fa-trash"></i></button>'
						);
			}
		}
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "iklan_view",
						"ket" => "Data",
						"add" => anchor('iklan/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
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

		$this->table->set_heading('No', 'Kode Barang', 'Nama Barang', 'Harga Jual', 'Beban', 'Laba', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$this->table->add_row(	++$i,
							$row->kode_barang,
							$row->nama_barang,
							'Rp. '.number_format($row->harga_jual),
							'Rp. '.number_format($row->beban),
							'Rp. '.number_format($row->laba_barang),
							'<button type="button" onclick="pilih_barang(\''.$row->kode_barang.'\', \''.$row->nama_barang.'\')" class="btn btn-xs btn-success" data-toggle="tooltip" title="Pilih"><i class="fa fa-check"></i></button>'
				);
			}
		}
		echo $this->table->generate();
		init_datatable_tooltips();
	}

	public function tambah()
	{
		$data = array(
						"page" => "iklan_view",
						"ket" => "Tambah",
						"form" => "iklan/add"
						);
		$this->load->view('index', $data);
	}

	public function add()
	{
		print_pre($this->input->post());
		$data = $this->input->post();
		unset($data['id_iklan'], $data['btnSimpan'], $data['nama_barang']);
		if($this->Iklan_model->add($data)){
			alert_notif("success");
			redirect('iklan');
		}else{
			alert_notif("danger");
			redirect('iklan/tambah');
		}
		
	}

	public function ubah($kode='')
	{
		$data = array(
						"page" => "iklan_view",
						"ket" => "Ubah",
						"form" => "iklan/update"
						);

		$q = $this->Iklan_model->get_data(d_url($kode));
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_iklan'] = $row->id_iklan;
			$data['kode_barang'] = $row->kode_barang;
			$data['nama_barang'] = $row->nama_barang;
			$data['tgl_iklan'] = date("Y-m-d", strtotime($row->tgl_iklan))."T".date("H:i", strtotime($row->tgl_iklan));
			$data['biaya_iklan'] = $row->biaya_iklan;
		}
		$this->load->view('index', $data);
	}

	public function update()
	{
		$data = $this->input->post();
		$id = $this->input->post('id_iklan');
		unset($data['id_iklan'], $data['btnSimpan'], $data['nama_barang']);

		if( $this->Iklan_model->update($data, $id) ){
			alert_notif("success");
			redirect('iklan');
		}else{
			alert_notif("danger");
			redirect('iklan/ubah.'.e_url($id));
		}
	}

	public function delete($kode)
	{
		if( $this->Iklan_model->delete(d_url($kode)) ){
			$this->session->set_flashdata('msg_title', 'Sukses!');
			$this->session->set_flashdata('msg_status', 'alert-success');
			$this->session->set_flashdata('msg', 'Data berhasil dihapus! ');
		}else{
			$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$this->session->set_flashdata('msg_status', 'alert-danger');
			$this->session->set_flashdata('msg', 'Data gagal dihapus! ');
		}
		redirect('iklan');
	}

}

/* End of file Iklan.php */
/* Location: ./application/controllers/Iklan.php */