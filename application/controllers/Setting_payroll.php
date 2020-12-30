<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_payroll extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Payroll_model", "", TRUE);
	}

	public function index()
	{
		$data = array(
						"page" => "setting_payroll_view",
						"ket" => "Data",
						"form" => "setting_payroll/update"
						);
		$q = $this->Payroll_model->get_setting();
		foreach ($q as $k => $v) {
			$data[$k] = $v;
		}
		$this->load->view('index', $data);
	}

	public function update()
	{
		$data = $this->input->post();
		unset($data['btnSimpan']);
		if($this->Payroll_model->update_setting($data)){
			alert_notif("success");
			redirect('setting_payroll');
		}else{
			alert_notif("danger");
			redirect('setting_payroll');
		}
	}

}

/* End of file Setting_payroll.php */
/* Location: ./application/controllers/Setting_payroll.php */