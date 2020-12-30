<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_iklan extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("User_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->User_model->get_where(["level" => 4]);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Nama User', 'Username', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-success">Aktif</span>';
				$btn = anchor('admin_iklan/suspend/'.e_url($row->id_user),'<span class="fa fa-ban"></span>',array( 'title' => 'Suspend', 'class' => 'btn btn-danger btn-xs', 'data-toggle' => 'tooltip'));
				if($row->sts==0){
					$btn = anchor('admin_iklan/unsuspend/'.e_url($row->id_user),'<span class="fa fa-check"></span>',array( 'title' => 'Unsuspend', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
					$sts = '<span class="badge badge-danger">Suspend</span>';
				}
				$this->table->add_row(	++$i,
							$row->nama,
							$row->username,
							$sts,
							anchor('admin_iklan/ubah/'.e_url($row->id_user),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip')).'&nbsp;'.
							$btn
						);
			}
		}
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "admin_iklan_view",
						"ket" => "Data",
						"add" => anchor('admin_iklan/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
	}

	public function tambah()
	{
		$q = $this->User_model->get_where_jabatan(["level" => 4]);
		$res = $q->result();

		$data = array(
						"page" => "admin_iklan_view",
						"ket" => "Tambah",
						"form" => "admin_iklan/add",
						"id_jabatan" => $res[0]->id_jabatan
						);
		$this->load->view('index', $data);
	}

	public function add()
	{
		$data = $this->input->post();
		unset($data['id_user']);
		unset($data['btnSimpan']);
		$data['password'] = e_password($data['password']);
		$data['sts'] = 1;
		//print_r($data);
		if($this->User_model->add_admin($data)){
			alert_notif("success");
			redirect('admin_iklan');
		}else{
			alert_notif("danger");
			redirect('admin_iklan/tambah');
		}
	}

	public function ubah($kode='')
	{
		$data = array(
						"page" => "admin_iklan_view",
						"ket" => "Ubah",
						"form" => "admin_iklan/update"
						);

		$q = $this->User_model->get_data(d_url($kode));
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_user'] = $row->id_user;
			$data['id_jabatan'] = $row->id_jabatan;
			$data['username'] = $row->username;
			$data['password'] = d_password($row->password);
			$data['nama'] = $row->nama;
		}
		$this->load->view('index', $data);
	}

	public function update()
	{
		$data = $this->input->post();
		$id = $this->input->post('id_user');
		unset($data['id_user'], $data['btnSimpan']);
		$data['password'] = e_password($data['password']);
		if( $this->User_model->update($data, $id) ){
			alert_notif("success");
			redirect('admin_iklan');
		}else{
			alert_notif("danger");
			redirect('admin_iklan/ubah.'.e_url($id));
		}
	}

	public function suspend($kode)
	{
		$data['sts'] = 0;
		$id = d_url($kode);
		
		if( $this->User_model->update($data, $id) ){
			$this->session->set_flashdata('msg_title', 'Sukses!');
			$this->session->set_flashdata('msg_status', 'alert-success');
			$this->session->set_flashdata('msg', 'admin berhasil disuspend! ');
		}else{
			$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$this->session->set_flashdata('msg_status', 'alert-danger');
			$this->session->set_flashdata('msg', 'admin gagal disuspend! ');
		}
		redirect('admin_iklan');
	}

	public function unsuspend($kode)
	{
		$data['sts'] = 1;
		$id = d_url($kode);
		
		if( $this->User_model->update($data, $id) ){
			$this->session->set_flashdata('msg_title', 'Sukses!');
			$this->session->set_flashdata('msg_status', 'alert-success');
			$this->session->set_flashdata('msg', 'admin berhasil diunsuspend! ');
		}else{
			$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$this->session->set_flashdata('msg_status', 'alert-danger');
			$this->session->set_flashdata('msg', 'admin gagal diunsuspend! ');
		}
		redirect('admin_iklan');
	}

}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */