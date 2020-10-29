<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus_cs extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Bonus_model", "", TRUE);
		$this->load->model("User_model", "", TRUE);
		$this->load->model("Sales_model", "", TRUE);
	}

	public function index()
	{
		$data = array(
						"page" => "bonus_cs_view",
						"ket" => "Data",
						"add" => anchor('bonus/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						//"table" => $this->gen_table()
						"form" => "bonus_cs/add",
						"cb_bulan" => $this->cb_bulan(date("n"))
					);
		$this->load->view('index', $data);
	}

	public function cb_bulan($sel='')
	{
		$ret = '<div class="form-group row"><label for="tgl_bonus" class="col-sm-2 col-form-label">Bulan</label><div class="col-sm-10">';
		
		$res = get_bulan();

		foreach ($res as $k => $row) {
			$opt[$k] = $row;
		}
		$js = 'class="form-control" id="tgl_bonus"';
		$ret= $ret.''.form_dropdown('tgl_bonus',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		return $ret;
	}

	public function cb_barang($id, $sel='')
	{
		$ret = '<div class="form-group row"><label for="nama" class="col-sm-2 col-form-label">Barang</label><div class="col-sm-10">';
		//$id = $this->session->userdata("user")->id_user;
		$q = $this->Sales_model->get_data($id);
		$res = $q->result();
		foreach ($res as $row) {
			$opt[$row->kode_barang] = $row->nama_barang;
		}
		$js = 'class="form-control" id="kode_barang"';
		$ret= $ret.''.form_dropdown('kode_barang',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		echo $ret;
	}

}

/* End of file Bonus_cs.php */
/* Location: ./application/controllers/Bonus_cs.php */