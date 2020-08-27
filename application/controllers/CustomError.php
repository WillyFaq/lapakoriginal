<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomError extends CI_Controller {

	public function index()
	{
		
	}

	public function error_404()
	{
		$data = array(
						"page" => "404"
						);
		$this->load->view('index', $data);
	}

}

/* End of file 404.php */
/* Location: ./application/controllers/404.php */