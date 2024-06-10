<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Categories extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$data = array(
				'title' 		=> 'Categories | Sodagara Komputer',
				'template'  	=> 'admin/pages/categories',
				'menu_active'	=> 'categories'
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
}