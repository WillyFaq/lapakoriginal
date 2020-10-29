<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_cs extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Team_model", "", TRUE);
		$this->load->model("Bonus_model", "", TRUE);
		$this->load->model("User_model", "", TRUE);
		$this->load->model("Sales_model", "", TRUE);
	}

	public function gen_table()
	{
		$query=$this->Team_model->get_all();
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Barang', 'Anggota', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;
			foreach ($res as $row){
				$t = explode("|", $row->team);
				$anggota = "";
				foreach ($t as $k => $v) {
					$anggota .= "- ".$this->Team_model->get_user($v)." <br>";
				}
				$this->table->add_row(	++$i,
							$row->nama_barang,
							$anggota,
							anchor('team_cs/ubah/'.e_url($row->id_sales_team),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip'))
						);
			}
		}
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "team_view",
						"ket" => "Data",
						"add" => anchor('team_cs/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
					);
		$this->load->view('index', $data);
	}

	public function tambah()
	{
		$data = array(
						"page" => "team_view",
						"ket" => "Tambah",
						"form" => "team_cs/add"
						
					);
		$this->load->view('index', $data);
	}

	public function add()
	{
		$data = $this->input->post();
		$data['team'] = join("|", $data['id_sales']);
		unset($data['id_sales_team']);
		unset($data['id_sales']);
		unset($data['nama_barang']);
		unset($data['btnSimpan']);
		if($this->Team_model->add($data)){
			alert_notif("success");
			redirect('team_cs');
		}else{
			alert_notif("danger");
			redirect('team_cs/tambah');
		}
	}

	public function ubah($id)
	{
		$id = d_url($id);
		$data = array(
						"page" => "team_view",
						"ket" => "Ubah",
						"form" => "team_cs/update"
						
					);
		$q = $this->Team_model->get_data($id);
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_sales_team'] = $row->id_sales_team;
			$data['kode_barang'] = $row->kode_barang;
			$data['nama_barang'] = $row->nama_barang;
			$team = explode("|", $row->team);
			foreach ($team as $k => $v) {
				$data['team'][$v] = $this->Team_model->get_user($v);
			}
		}
		$this->load->view('index', $data);
	}

	public function update()
	{
		$data = $this->input->post();
		$data['team'] = join("|", $data['id_sales']);
		$id = $data['id_sales_team'];
		unset($data['id_sales_team']);
		unset($data['id_sales']);
		unset($data['nama_barang']);
		unset($data['btnSimpan']);
		if($this->Team_model->update($data, $id)){
			alert_notif("success");
			redirect('team_cs');
		}else{
			alert_notif("danger");
			redirect('team_cs/ubah/'.e_url($id));
		}
	}


	public function gen_table_sales()
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
		$this->table->set_heading('No', 'Nama Sales', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$this->table->add_row(	++$i,
							$row->nama,
							'<button type="button" onclick="pilih_sales(\''.$row->id_user.'\', \''.$row->nama.'\')" class="btn btn-xs btn-success" data-toggle="tooltip" title="Pilih"><i class="fa fa-check"></i></button>'
						);
			}
		}
		echo $this->table->generate();
		init_datatable_tooltips();
	}
}

/* End of file Team_cs.php */
/* Location: ./application/controllers/Team_cs.php */