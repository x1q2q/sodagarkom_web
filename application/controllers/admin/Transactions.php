<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Transactions extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(empty($this->session->userdata('admin_id'))){
			redirect('admin/login');
			return false;
		}
	}
	protected function load_templates($data){
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/template/sidebar',$data);
		$this->load->view('admin/template/navbar',$data);
		$this->load->view('admin/template/content',$data);
		$this->load->view('admin/template/footer',$data);
	}
	public function index(){
		$data = array(
				'title' 		=> 'Transactions | Sodagar Komputer',
				'template'  	=> 'admin/pages/transactions',
				'menu_active'	=> 'transactions',
				'data'			=> json_encode($this->m_transactions->get_join()),
				'payment_assets'=> payment_assets()
		);
		$this->load_templates($data);
	}
	public function get_data(){
		echo json_encode($this->m_transactions->get_join());
	}
	public function transaction_details($transaction_id){
		return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Transaction details succes retrieved!', 'data' => $this->m_transactions->get_transaction_detail($transaction_id))));
	}
	public function change_stock($handleTo,$transaction_id){
		// looping on transaction_details get where transaction id
		// if accepted [on id], stock = stock - (qty value on trx)
		// else if rejected [on id], stock_temporary = stock_temporary + (qty value on trx)
		$result = $this->m_transactions->get_trx_detail($transaction_id);
		foreach($result as $trx_detail){
			// do update stock/stock_temporary on products
			$this->m_products->update_stock($handleTo, $trx_detail->quantity, $trx_detail->product_id);
		}
	}
	public function handle_payment_proof($handleTo, $id){
		// $handleTo should be 'accepted' or 'rejected'
		$data = array('status' => $handleTo);
		$where = array('id' => $id);

		if($this->m_transactions->update($data,$where)){
			$this->change_stock($handleTo,$id);
			return $this->output->set_content_type('application/json')
	        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Transaction item has been '.$handleTo.'!')));
		}
	}
	public function delete($id){
		$where = array('id' => $id);

		if($this->m_transactions->delete($where)){
			return $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Transaction item has been deleted!')));
		}
	}
}