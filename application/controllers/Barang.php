<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Barang_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Barang_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
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
							anchor('barang/ubah/'.e_url($row->kode_barang),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip')).'&nbsp;'.
							anchor('barang/detail/'.e_url($row->kode_barang),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
						);
			}
		}
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "barang_view",
						"ket" => "Data",
						"add" => anchor('barang/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
	}

	public function tambah()
	{
		$data = array(
						"page" => "barang_view",
						"ket" => "Tambah",
						"form" => "barang/add"
						);
		$this->load->view('index', $data);
	}

	public function add()
	{
		//print_pre($this->input->post());
		if($this->Barang_model->add($this->input->post())){
			alert_notif("success");
			redirect('barang');
		}else{
			alert_notif("danger");
			redirect('barang/tambah');
		}
	}

	public function ubah($kode='')
	{
		$data = array(
						"page" => "barang_view",
						"ket" => "Ubah",
						"form" => "barang/update"
						);

		$q = $this->Barang_model->get_update(d_url($kode));
		$res = $q->result();
		foreach ($res as $row) {
			$data['kode_barang'] = $row->kode_barang;
			$data['nama_barang'] = $row->nama_barang;
			$data['warna_barang'] = $row->warna_barang;
			$data['ukuran_barang'] = $row->ukuran_barang;
			$data['setting_harga'] = $row->setting_harga;
			$data['harga_jual'] = $row->harga_jual;
			$data['laba_barang'] = $row->laba_barang;
			$data['beban'][$row->id_beban]['id'] = $row->id_beban;
			$data['beban'][$row->id_beban]['nama_beban'] = $row->nama_beban;
			$data['beban'][$row->id_beban]['nominal'] = $row->nominal;
		}
		$this->load->view('index', $data);
	}

	public function update()
	{
		$data = $this->input->post();
		$id = $this->input->post('kode_barang');
		unset($data['kode_barang']);

		if( $this->Barang_model->update($data, $id) ){
			alert_notif("success");
			redirect('barang');
		}else{
			alert_notif("danger");
			redirect('barang/ubah.'.e_url($id));
		}
	}

	public function detail($kode='')
	{
		$data = array(
						"page" => "barang_view",
						"ket" => "Detail",
						"detail" => d_url($kode)
						);

		$q = $this->Barang_model->get_update(d_url($kode));
		$res = $q->result();
		foreach ($res as $row) {
			$data['kode_barang'] = $row->kode_barang;
			$data['nama_barang'] = $row->nama_barang;
			$data['warna_barang'] = $row->warna_barang;
			$data['ukuran_barang'] = $row->ukuran_barang;
			$data['setting_harga'] = $row->setting_harga;
			$data['harga_jual'] = $row->harga_jual;
			$data['laba_barang'] = $row->laba_barang;
			$data['beban'][$row->id_beban]['id'] = $row->id_beban;
			$data['beban'][$row->id_beban]['nama_beban'] = $row->nama_beban;
			$data['beban'][$row->id_beban]['nominal'] = $row->nominal;
		}
		$this->load->view('index', $data);
	}

	public function get_harga($kode)
	{
		$q = $this->Barang_model->get_data($kode)->row();
		echo $q->harga_jual;
	}

}

/* End of file Barang.php */
/* Location: ./application/controllers/Barang.php */