<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Admin extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
	}
	public function index(){
		$data = array(
				'title' 		=> 'Dashboard | Sodagara Komputer',
				'template'  	=> 'admin/pages/dashboard',
				'menu_active'	=> 'dashboard'
			);
		$this->load_templates($data);
	}
	public function load_templates($data){
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/template/sidebar',$data);
		$this->load->view('admin/template/navbar',$data);
		$this->load->view('admin/template/content',$data);
		$this->load->view('admin/template/footer',$data);
	}
	public function login(){
		$data = array('title' => 'Login Page | Sodagara Komputer');
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/pages/login');
	}

}