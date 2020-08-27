<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->load->library('form_validation');
	}

	public function index()
	{
		$data = array(
						"form" => 'login/log_in'
					);
		$this->load->view('login_view', $data);
	}

	public function log_in(){
		$data = $this->input->post();
		$data['password'] = e_password($data['password']);
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('jabatan', 'user.id_jabatan = jabatan.id_jabatan');
		$this->db->where(array('username' => $data['username']));
		$user = $this->db->get()->row();
		if($user){
			$isPasswordTrue = $data["password"]==$user->password;
			$isActive = $user->sts;
            if($isPasswordTrue){
				print_pre($user);
				if($isActive==1){
					$this->session->set_flashdata('msg_title', 'Login Berhasil!');
					$this->session->set_flashdata('msg_status', 'alert-success');
					$this->session->set_flashdata('msg', 'Selamat datang '.$user->nama);
					$this->session->set_userdata(["user" => $user]);
					redirect('dahsboard');
				}else{
					$this->session->set_flashdata('msg_title', 'Login Gagal!');
					$this->session->set_flashdata('msg_status', 'alert-danger');
					$this->session->set_flashdata('msg', 'User di suspend!<br>Silahkan hubungi admin. ');
					redirect('login');
				}
            }else{
				$this->session->set_flashdata('msg_title', 'Login Gagal!');
				$this->session->set_flashdata('msg_status', 'alert-danger');
				$this->session->set_flashdata('msg', 'Password Salah! ');
				redirect('login');
            }
		}else{

			$this->session->set_flashdata('msg_title', 'Login Gagal!');
			$this->session->set_flashdata('msg_status', 'alert-danger');
			$this->session->set_flashdata('msg', 'User tidak ditemukan! ');
			redirect('login');
		}
	}

	public function log_out()
	{
		$this->session->unset_userdata('user');
		redirect('login');
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */