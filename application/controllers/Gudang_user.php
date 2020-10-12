<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang_user extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Gudang_user_model", "", TRUE);
		$this->load->model("Gudang_model", "", TRUE);
		$this->load->model("User_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Gudang_user_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Nama User', 'Username', 'Gudang', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-success">Aktif</span>';
				$btn = anchor('admin/suspend/'.e_url($row->id_user),'<span class="fa fa-ban"></span>',array( 'title' => 'Suspend', 'class' => 'btn btn-danger btn-xs', 'data-toggle' => 'tooltip'));
				if($row->sts==0){
					$btn = anchor('admin/unsuspend/'.e_url($row->id_user),'<span class="fa fa-check"></span>',array( 'title' => 'Unsuspend', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
					$sts = '<span class="badge badge-danger">Suspend</span>';
				}
				$this->table->add_row(	++$i,
							$row->nama,
							$row->username,
							$row->nama_gudang,
							$sts,
							anchor('gudang_user/ubah/'.e_url($row->id_gudang_user),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip')).'&nbsp;'.
							$btn
						);
			}
		}
		return  $this->table->generate();
	}

	public function gen_table_gudang()
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
							'<button type="button" onclick="pilih_gudang(\''.$row->id_gudang.'\', \''.$row->nama_gudang.'\')" class="btn btn-xs btn-success" data-toggle="tooltip" title="Pilih"><i class="fa fa-check"></i></button>'
						);
			}
		}
		echo  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "gudang_user_view",
						"ket" => "Data",
						"add" => anchor('gudang_user/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
	}

	public function tambah()
	{
		$q = $this->User_model->get_where_jabatan(["level" => 3]);
		$res = $q->result();
		$data = array(
						"page" => "gudang_user_view",
						"ket" => "Tambah",
						"form" => "gudang_user/add",
						"id_jabatan" => $res[0]->id_jabatan
						);
		$this->load->view('index', $data);
	}

	public function add()
	{
		$data = $this->input->post();
		unset($data['id_gudang_user']);
		unset($data['id_user']);
		unset($data['btnSimpan']);
		$data['password'] = e_password($data['password']);
		$data['sts'] = 1;
		print_pre($data);
		if($this->Gudang_user_model->add($data)){
			alert_notif("success");
			redirect('gudang_user');
		}else{
			alert_notif("danger");
			redirect('gudang_user/tambah');
		}
	}

	public function ubah($id='')
	{
		$id = d_url($id);
		$data = array(
						"page" => "gudang_user_view",
						"ket" => "Ubah",
						"form" => "gudang_user/update"
						);
		$q = $this->Gudang_user_model->get_data($id);
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_user'] = $row->id_user;
			$data['id_gudang'] = $row->id_gudang;
			$data['nama_gudang'] = $row->nama_gudang;
			$data['nama'] = $row->nama;
			$data['username'] = $row->username;
			$data['password'] = d_password($row->password);
		}


		$this->load->view('index', $data);

	}

	public function update()
	{
		$id_gudang_user = $this->input->post("id_user");
		$id_user = $this->input->post("id_user");
		$da_user = array(
							"nama" => $this->input->post("id_user"),
							"username" => $this->input->post("username"),
							"password" => e_password($this->input->post("password")),
						);
		$da_gudang = $this->input->post("id_gudang");

		if($this->Gudang_user_model->update($id_gudang_user, $id_user, $da_user, $da_gudang)){
			alert_notif("success");
			redirect('gudang_user');
		}else{
			alert_notif("danger");
			redirect('gudang_user');
		}
	}

}

/* End of file Gudang_user.php */
/* Location: ./application/controllers/Gudang_user.php */