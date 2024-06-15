<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('m_dashboard');
	}
	public function index(){
		$data = array(
				'title' 		=> 'Dashboard | Sodagara Komputer',
				'template'  	=> 'admin/pages/dashboard',
				'menu_active'	=> 'dashboard',
				'statistics'		=> json_encode(array(
					'total_customers' 	=> $this->m_dashboard->get_all_rows('customers'),
					'total_revenue' 	=> $this->m_dashboard->get_total_revenue(),
					'total_products'	=> $this->m_dashboard->get_all_rows('products'),
					'total_pending_transaction' => 
						$this->m_dashboard->get_all_rows('transactions',array('status' => 'pending')))),
				'data_by_days'	=> json_encode($this->m_dashboard->get_transaction_per('transactions_per_day')),
				'data_by_month'	=> json_encode($this->m_dashboard->get_transaction_per('transactions_per_month')),
				'data_by_years'	=> json_encode($this->m_dashboard->get_transaction_per('transactions_per_year'))
			);
		$this->load_templates($data);
	}
	public function transaction_days(){
		return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 200, 'data' => $this->m_dashboard->get_transaction_per('transactions_per_year'))));
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