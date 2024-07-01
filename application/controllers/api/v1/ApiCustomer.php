<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class ApiCustomer extends RESTController {
	function __construct(){
		parent::__construct();
		if (isset($_SERVER['HTTP_ORIGIN'])) {
	        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	        header('Access-Control-Allow-Credentials: true');
	        header('Access-Control-Max-Age: 86400');    // cache for 1 day
  	    }

  	    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  	        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
  	            header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");

  	        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
  	            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  	        exit(0);
  	    }
	}

    public function index_get($user_id){
    	// http://localhost/sodagarkom_web/api/v1/customer/[user_id] ['get methods'] for detail profil
    	$where = array('id' => $user_id);
    	$customer = $this->m_customers->get_detail($where)->result();
    	if($customer){
    		$this->response(
    			general_response('ok','success get detail data customer',$customer), 200);
    	}else{
    		$this->response(
    			general_response('false','No data on customer id',$customer), 404);
    	}
    }
   
    public function update_profile_put(){
    	$username  = test_input($this->put('username'));
		$email 	   = test_input($this->put('email'));
		$id 	   = test_input($this->put('id'));
		$full_name = test_input($this->put('full_name'));
		$phone 	   = test_input($this->put('phone'));
		$address   = test_input($this->put('address'));
    	// first to checking duplicate email
    	if(validate_oldval('email', $email, $id) && validate_duplicate('email',$email)){
		    $this->response(general_response('false','Email sudah terdaftar pada sistem',[]), 409);
		// second to checking duplicate username (with some condition)
		}else if(validate_oldval('username', $username, $id) && validate_duplicate('username',$username)){
			$this->response(general_response('false','Username sudah terdaftar pada sistem',[]), 409);
		}else if(!is_numeric($phone)){
			$this->response(general_response('false','Nomor telepon tidak valid. Harus berupa angka!',[]), 400);
		}else if(validate($username) && validate($email) && validate($full_name) && validate($address) && validate($phone)){
	    	$data = array(
	    		'username' => $username,
	    		'email' => $email,
	    		'full_name' => $full_name,
	    		'phone' => $phone,
	    		'address' => $address
	    	);
	    	if($this->put('password') != ''){
	    		$data['password'] = password_hash($this->put('password'), PASSWORD_DEFAULT);
	    	}
	    	$where = array('id' => $id);
	    	$update = $this->m_customers->update($data, $where);
	    	if($update){
	    		$this->response(general_response('ok','Berasil mengupdate data profile',[]), 200);
	    	}else{
	    		$this->response(general_response('false','Gagal mengupdate data profile',[]), 500);
	    	}
	    }else{
	    	$this->response(general_response('false','Kolom input data masih kosong',[]), 400);
	    }
    }
}