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
}