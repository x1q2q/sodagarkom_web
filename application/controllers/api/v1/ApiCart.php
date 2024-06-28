<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class ApiCart extends RESTController {
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
  	    $this->load->model('m_carts');
	}

    public function index_get($user_id){
    	// http://localhost/sodagarkom_web/api/v1/cart/[user_id] ['get methods'] for detail cart
    	$where = array('customer_id' => $user_id, 'status' => 'active');
    	$cart = $this->m_carts->get_detail_join($where)->result();
    	if($cart){
    		$this->response(
    			general_response('ok','success get data carts by customer',$cart), 200);
    	}else{
    		$this->response(
    			general_response('false','No data on carts id',$cart), 404);
    	}
    }
}