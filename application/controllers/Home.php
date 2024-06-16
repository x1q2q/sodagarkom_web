<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$data = array('title' => 'Home Page Sodagar Komputer');

	}
	public function login(){
		$sesi = $this->session->userdata('admin_id');
		if(!empty($sesi)){
			redirect('admin/dashboard');
		}
		$data = array('title' => 'Login Page | Sodagar Komputer');
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/pages/login');
	}
	public function login_aksi(){
		$this->load->model('m_dashboard');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username','Username','trim|required');
		$this->form_validation->set_rules('password','Password','trim|required');
		if($this->form_validation->run() == FALSE){
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 404, 'status' => 'error','message' => 'Username atau password masih kosong')));
		}else{
			$nama 	= $this->input->post('username');
			$passwd = $this->input->post('password');
			$where = array('username' => $nama);

			if(validate($nama) && validate($passwd)){
				$login = $this->m_dashboard->get_detail($where);
				$admin = $login->row();
				if($admin){
					if (!password_verify($passwd, $admin->password)) {
						return $this->output->set_content_type('application/json')
		        		->set_output(json_encode(array('code' => 404, 'status' => 'error','message' => 'Password masih salah')));
					}

				$this->session->set_userdata(['admin_id'=> $admin->id]);

				return $this->output->set_content_type('application/json')
		       	 ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Berhasil login. Halaman akan segera dialihkan!')));
				}else{
					return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 404, 'status' => 'error','message' => 'Username atau password tidak ada')));
				}
			}
		}
	}
	public function logout(){
		$this->session->unset_userdata('admin_id');
		redirect('admin/login');
	}

}