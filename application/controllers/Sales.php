<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("User_model", "", TRUE);
		$this->load->model("Sales_model", "", TRUE);
	}

	public function get_sales($id)
	{
		$q = $this->Sales_model->get_data($id);
		$res = $q->result();
		$ret = "";
		foreach ($res as $row) {
			$ret .= $row->nama_barang." (".$row->minimal_sale.")"."<br>";
		}
		return $ret;
	}

	public function get_mimimal($id)
	{
		$q = $this->Sales_model->get_data($id);
		$res = $q->result();
		$ret = "";
		foreach ($res as $row) {
			$ret .= $row->minimal_sale."<br>";
		}
		return $ret;
	}

	public function gen_table()
	{
		$query=$this->User_model->get_where(["level" => 2]);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);

		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Nama Sales', 'Username', 'Barang (Minimal Penjualan)', 'Status', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$sts = '<span class="badge badge-success">Aktif</span>';
				$btn = anchor('sales/suspend/'.e_url($row->id_user),'<span class="fa fa-ban"></span>',array( 'title' => 'Suspend', 'class' => 'btn btn-danger btn-xs', 'data-toggle' => 'tooltip'));
				if($row->sts==0){
					$btn = anchor('sales/unsuspend/'.e_url($row->id_user),'<span class="fa fa-check"></span>',array( 'title' => 'Unsuspend', 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip'));
					$sts = '<span class="badge badge-danger">Suspend</span>';
				}
				$this->table->add_row(	++$i,
							$row->nama,
							$row->username,
							$this->get_sales($row->id_user),
							$sts,
							anchor('sales/ubah/'.e_url($row->id_user),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip')).'&nbsp;'.
							$btn
						);
			}
		}
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "sales_view",
						"ket" => "Data",
						"add" => anchor('sales/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
	}

	public function tambah()
	{
		$q = $this->User_model->get_where_jabatan(["level" => 2]);
		$res = $q->result();

		$data = array(
						"page" => "sales_view",
						"ket" => "Tambah",
						"form" => "sales/add",
						"id_jabatan" => $res[0]->id_jabatan
						);
		$sal = $this->session->flashdata('data_sales');
		if(isset($sal)){
			foreach ($sal as $key => $value) {
				$data[$key] = $value;
			}
		}
		$this->load->view('index', $data);
	}

	public function add()
	{
		$data = $this->input->post();
		unset($data['id_user']);
		unset($data['btnSimpan']);
		
		if(empty($this->input->post('kode_barang')[0])){
			$this->session->set_flashdata('data_sales', $data);

			$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$this->session->set_flashdata('msg_status', 'alert-danger');
			$this->session->set_flashdata('msg', 'Data silahkan lengkapi data! ');
			redirect('sales/tambah');
		}else{

			$data['password'] = e_password($data['password']);
			$data['sts'] = 1;

			if($this->Sales_model->add($data)){
				$this->session->set_flashdata('msg_title', 'Sukses!');
				$this->session->set_flashdata('msg_status', 'alert-success');
				$this->session->set_flashdata('msg', 'Data berhasil disimpan! ');
				redirect('sales');
			}else{

				$this->session->set_flashdata('data_sales', $data);
				$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
				$this->session->set_flashdata('msg_status', 'alert-danger');
				$this->session->set_flashdata('msg', 'Data gagal disimpan! ');
				redirect('sales/tambah');
			}
		}
	}

	public function ubah($kode='')
	{
		$data = array(
						"page" => "sales_view",
						"ket" => "Ubah",
						"form" => "sales/update"
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

		$q = $this->Sales_model->get_data(d_url($kode));
		$res = $q->result();
		$i=0;
		foreach ($res as $row) {
			$data['barang'][$i]["id_sales_barang"] = $row->id_sales_barang;
			$data['barang'][$i]["kode_barang"] = $row->kode_barang;
			$data['barang'][$i]["nama_barang"] = $row->nama_barang;
			$data['barang'][$i]["minimal"] = $row->minimal_sale;
			$i++;
		}

		$this->load->view('index', $data);
	}

	public function update()
	{
		$data = $this->input->post();
		$id = $this->input->post('id_user');
		unset($data['id_user'], $data['btnSimpan']);
		$data['password'] = e_password($data['password']);
		//print_pre($data);
		if(empty($this->input->post('kode_barang')[0])){

			$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
			$this->session->set_flashdata('msg_status', 'alert-danger');
			$this->session->set_flashdata('msg', 'Data silahkan lengkapi data! ');
			redirect('sales/ubah/'.e_url($id));
		}else{
			if( $this->Sales_model->update($data, $id) ){
				$this->session->set_flashdata('msg_title', 'Sukses!');
				$this->session->set_flashdata('msg_status', 'alert-success');
				$this->session->set_flashdata('msg', 'Data berhasil disimpan! ');
				redirect('sales');
			}else{
				$this->session->set_flashdata('msg_title', 'Terjadi Kesalahan!');
				$this->session->set_flashdata('msg_status', 'alert-danger');
				$this->session->set_flashdata('msg', 'Data gagal disimpan! ');
				redirect('sales/ubah.'.e_url($id));
			}
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
		redirect('sales');
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
		redirect('sales');
	}

}

/* End of file Sales.php */
/* Location: ./application/controllers/Sales.php */