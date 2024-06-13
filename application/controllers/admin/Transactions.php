<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Transactions extends CI_Controller {
	public function __construct(){
		parent::__construct();
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
	public function update($id){
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$request = json_decode($stream_clean);

		$name			= test_input($request->name);
		$description 	= test_input($request->description);

		if(validate($name) && validate($description)){
			$data = array(
				'name' => $name,
				'description' => $description
			);
			$where = array('id' => $id);
			if($this->m_transactions->update($data,$where)){
				return$this->output
		        ->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Transaction item has been updated!')));
			}
		}else{
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 400, 'status' => 'error','message' => 'Input field is empty')));
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