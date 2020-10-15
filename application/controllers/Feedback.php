<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		$this->load->model("Feedback_model", "", TRUE);
	}



	public function gen_table()
	{
		$query=$this->Feedback_model->get_all(["level" => 1]);
		$res = $query->result();
		$num_rows = $query->num_rows();

		$tmpl = array(  'table_open'    => '<table class="table table-striped table-hover dataTable">',
				'row_alt_start'  => '<tr>',
				'row_alt_end'    => '</tr>'
			);

		$this->table->set_template($tmpl);
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No', 'Type', 'Pesan', 'Aksi');

		if ($num_rows > 0)
		{
			$i = 0;

			foreach ($res as $row){
				$this->table->add_row(	++$i,
							get_type_feedback($row->type),
							$row->pesan,
							anchor('feedback/ubah/'.e_url($row->id_feedback),'<span class="fa fa-pencil-alt"></span>',array( 'title' => 'Ubah', 'class' => 'btn btn-primary btn-xs', 'data-toggle' => 'tooltip')).'&nbsp;'.
							'<button type="button" class="btn btn-xs btn-danger btn-hapus" data-id="'.e_url($row->id_feedback).'" data-toggle="tooltip" title="Hapus" data-original-title="Hapus"><i class="fa fa-trash"></i></button>'
						);
			}
		}
		return  $this->table->generate();
	}

	public function index()
	{
		$data = array(
						"page" => "feedback_view",
						"ket" => "Data",
						"add" => anchor('feedback/tambah', '<i class="fa fa-plus"></i>', array("class" => "btn btn-success", "data-toggle" => "tooltip", "data-placement" => "top", "title" => "Tambah Data")),
						"table" => $this->gen_table()
						);
		$this->load->view('index', $data);
	}

	public function cb_type($sel='')
	{
		$ret = '<div class="form-group row"><label for="type" class="col-sm-2 col-form-label">Type</label><div class="col-sm-10">';
		$res = get_type_feedback();
		foreach ($res as $k => $row) {
			$opt[$k] = $row;
		}
		$js = 'class="form-control cb_type" id="type"';
		$ret= $ret.''.form_dropdown('type',$opt,$sel,$js);
		$ret= $ret.'</div></div>';
		return $ret;
	}

	public function tambah()
	{
		$data = array(
						"page" => "feedback_view",
						"ket" => "Tambah",
						"form" => "feedback/add",
						"cb_type" => $this->cb_type("")
						);
		$this->load->view('index', $data);
	}

	public function add()
	{
		$data = $this->input->post();
		unset($data['id_feedback']);
		unset($data['btnSimpan']);
		if($this->Feedback_model->add($data)){
			alert_notif("success");
			redirect('feedback');
		}else{
			alert_notif("danger");
			redirect('feedback/tambah');
		}
	}

	public function ubah($v="")
	{
		$v = d_url($v);
		$data = array(
						"page" => "feedback_view",
						"ket" => "Ubah",
						"form" => "feedback/update",
						);
		$q = $this->Feedback_model->get_data($v);
		$res = $q->result();
		foreach ($res as $row) {
			$data['id_feedback'] = $row->id_feedback;
			$data['cb_type'] = $this->cb_type($row->type);
			$data['pesan'] = $row->pesan;
		}
		$this->load->view('index', $data);
	}

	public function update()
	{
		$data = $this->input->post();
		$id = $data['id_feedback'];
		unset($data['id_feedback']);
		unset($data['btnSimpan']);
		if($this->Feedback_model->update($data, $id)){
			alert_notif("success");
			redirect('feedback');
		}else{
			alert_notif("danger");
			redirect('feedback/ubah/'.e_url($id));
		}
	}

	public function delete($v='')
	{
		$v = d_url($v);
		if($this->Feedback_model->delete($v)){
			alert_notif("success");
			redirect('feedback');
		}else{
			alert_notif("danger");
			redirect('feedback');
		}
	}

}

/* End of file Feedback.php */
/* Location: ./application/controllers/Feedback.php */