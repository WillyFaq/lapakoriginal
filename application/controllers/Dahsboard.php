<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dahsboard extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
	}

	public function index()
	{
		$data = array(
						"page" => "home_view"
						);
		$this->load->view('index', $data);
	}

}

/* End of file Dahsboard.php */
/* Location: ./application/controllers/Dahsboard.php */