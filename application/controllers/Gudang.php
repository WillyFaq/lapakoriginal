<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Gudang_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Gudang_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Nama', 'Lokasi', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){

				$alamat = explode("|", $row->lokasi);
				if(sizeof($alamat)==1){
					$alm = $row->alamat;
				}else{
					$prov = get_provinsi($alamat[0])['nama'];
					$kot = get_kota($alamat[1])['nama'];
					$kec = get_kecamatan($alamat[2])['nama'];
					$alm = $alamat[3].", $kec, $kot, $prov";
				}
				$this->table->add_row(	++$i,
							$row->nama_gudang,
							$alm,
							anchor('gudang/ubah/'.e_url($row->id_gudang),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip')).'&nbsp;'.
							''//anchor('gudang/detail/'.e_url($row->id_gudang),'<span class="fa fa-eye"></span>',array( 'title' => 'Detail', 'class' => 'btn btn-warning btn-xs', 'data-toggle' => 'tooltip'))
						);
			}
		}
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "gudang_view",
						"ket" => "Data",
						"add" => anchor('gudang/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
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
		$ret = '<div class="form-group row"><label for="kecamatan" class="col-sm-2 col-form-label">Kecamatan</label><div class="col-sm-10">';
		$res = get_kecamatan($id);
		foreach ($res as $k => $row) {
			$opt[$row['id']] = $row['nama'];
		}
		$js = 'class="form-control cb_kecamatan" id="kecamatan"';
		$ret= $ret.''.form_dropdown('kecamatan',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		echo $ret;
	}

	public function tambah()
	{
		$data = array(
						"page" => "gudang_view",
						"ket" => "Tambah",
						"form" => "gudang/add",
						"cb_provinsi" => $this->cb_provinsi("")
						);
		$this->load->view('index', $data);
	}

	public function add()
	{
		$data = array(
						'nama_gudang' => $this->input->post('nama_gudang'),
						'lokasi' => $this->input->post('provinsi')."|".$this->input->post('kota')."|".$this->input->post('kecamatan')."|".$this->input->post('alamat'),
						'status_gudang' => 1
						);
		if($this->Gudang_model->add($data)){
			alert_notif("success");
			redirect('gudang');
		}else{
			alert_notif("danger");
			redirect('gudang/tambah');
		}
	}

	public function ubah($id='')
	{
		$id = d_url($id);
		$data = array(
						"page" => "gudang_view",
						"ket" => "Ubah",
						"form" => "gudang/update"
						);

		$q = $this->Gudang_model->get_data($id);
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_gudang'] = $row->id_gudang;
			$data['nama_gudang'] = $row->nama_gudang;

			$alamat = explode("|", $row->lokasi);
			if(sizeof($alamat)==1){
				$alm = $row->alamat;
			}else{
				$prov = get_provinsi($alamat[0])['nama'];
				$kot = get_kota($alamat[1])['nama'];
				$kec = get_kecamatan($alamat[2])['nama'];
				$alm = $alamat[3].", $kec, $kot, $prov";
			}

			$data['cb_provinsi'] = $this->cb_provinsi($alamat[0]);
			$data['id_provinsi'] = $alamat[0];
			$data['id_kota'] = $alamat[1];
			$data['id_kecamatan'] = $alamat[2];
			$data['alamat'] = $alamat[3];
			//$data['cb_kota'] = $this->cb_kota2($alamat[0], $alamat[1]);
			//$data['cb_kecamatan'] = $this->cb_kecamatan2($alamat[1], $alamat[2]);
		}

		$this->load->view('index', $data);
	}

	public function update()
	{
		$id_gudang = $this->input->post('id_gudang');
		$data = array(
						'nama_gudang' => $this->input->post('nama_gudang'),
						'lokasi' => $this->input->post('provinsi')."|".$this->input->post('kota')."|".$this->input->post('kecamatan')."|".$this->input->post('alamat'),
						'status_gudang' => 1
						);
		if($this->Gudang_model->update($data, $id_gudang)){
			alert_notif("success");
			redirect('gudang');
		}else{
			alert_notif("danger");
			redirect('gudang/tambah');
		}
	}

}

/* End of file Gudang.php */
/* Location: ./application/controllers/Gudang.php */