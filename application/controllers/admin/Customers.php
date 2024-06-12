<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Customers extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$data = array(
				'title' 		=> 'Customers | Sodagar Komputer',
				'template'  	=> 'admin/pages/customers',
				'menu_active'	=> 'customers',
				'data'			=> json_encode($this->m_customers->get_all())
		);
		$this->load_templates($data);
	}
	public function get_data(){
		echo json_encode($this->m_customers->get_all());
	}
	public function load_templates($data){
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/template/sidebar',$data);
		$this->load->view('admin/template/navbar',$data);
		$this->load->view('admin/template/content',$data);
		$this->load->view('admin/template/footer',$data);
	}

	public function validate_duplicate($field, $value){
		// field between [username, email] return true if its duplicate/same value
		$where = array($field => $value);
		$rows = $this->m_customers->get_detail($where)->result();
		return (count($rows) > 0);
	}
	public function validate_oldval($field, $current_val, $id){
		// if email / username updated is different value from old data
		// first to checking duplicate email (with some condition)
		$where_old_val = array('id' => $id);
		$old_val = $this->m_customers->get_detail($where_old_val)->result_array();
		$old_field_val = $old_val[0][$field]; // between [username, email]
		return ($current_val != $old_field_val);
	}
	
	public function insert(){
		// we can use this
		// $data = json_decode(file_get_contents('php://input'), true);
		// $username = json_encode($data['username']);

		// or this
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$request = json_decode($stream_clean);

		$username		= test_input($request->username);
		$email 			= test_input($request->email);
		$password		= md5($request->password);
		$full_name 		= test_input($request->full_name);
		$address 		= test_input($request->address);
		$phone 			= test_input($request->phone);	

		// first to checking duplicate email
		if($this->validate_duplicate('email',$email)){
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 400, 'status' => 'error','message' => 'Email sudah terdaftar pada sistem')));
		// second to checking duplicate username
		}else if($this->validate_duplicate('username',$username)){
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 400, 'status' => 'error','message' => 'Username sudah terdaftar pada sistem')));
		}else if(validate($username) && validate($email) && validate($password) && validate($full_name) && validate($address) && validate($phone)){
			$data = array(
				'username' => $username,
				'email' => $email,
				'password' => $password,
				'full_name' => $full_name,
				'address' => $address,
				'phone' => $phone
				// 'is_accepted' => 1
			);
			if($this->m_customers->insert($data)){
				return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Success insert a new customer!')));
			}else{
				return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 500, 'status' => 'error','message' => 'Internal server Error')));
			}
		}else{
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 400, 'status' => 'error','message' => 'Kolom input masih ada yang kosong')));
		}

		
	}
	public function update($id){
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$request = json_decode($stream_clean);

		$username		= test_input($request->username);
		$email 			= test_input($request->email);
		$full_name 		= test_input($request->full_name);
		$address 		= test_input($request->address);
		$phone 			= test_input($request->phone);

		if($this->validate_oldval('email', $email, $id) && $this->validate_duplicate('email',$email)){
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 400, 'status' => 'error','message' => 'Email sudah terdaftar pada sistem')));

		// second to checking duplicate username (with some condition)
		}else if($this->validate_oldval('username', $username, $id) && $this->validate_duplicate('username',$username)){
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 400, 'status' => 'error','message' => 'Username sudah terdaftar pada sistem')));
		}else if(validate($username) && validate($email) && validate($full_name) && validate($address) && validate($phone)){
			$data = array(
				'username' => $username,
				'email' => $email,
				'full_name' => $full_name,
				'address' => $address,
				'phone' => $phone
			);
			$where = array('id' => $id);
			if($this->m_customers->update($data,$where)){
				return$this->output
		        ->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Customer account has been updated!')));
			}
		}else{
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 400, 'status' => 'error','message' => 'Kolom input masih ada yang kosong')));
		}
	}
	public function activate($id){
		$data = array('is_accepted' => 1);
		$where = array('id' => $id);

		if($this->m_customers->update($data,$where)){
			return $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Customer account has been activated!')));
		}
	}
	public function delete($id){
		$where = array('id' => $id);

		if($this->m_customers->delete($where)){
			return $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Customer account has been deleted!')));
		}
	}
}