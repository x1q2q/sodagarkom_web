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
    			general_response('false','Empty data on carts id',$cart), 400);
    	}
    }
    public function index_post(){
    	$data = array(
    		'customer_id' => $this->post('customer_id'),
    		'product_id' => $this->post('product_id'),
    		'quantity'	 => 1,
    		'status'	 => 'active'
    	);
    	if($this->db->insert('carts', $data)){
    		$inserted_cart_id = $this->db->insert_id();
    		$where = array('carts.id' => $inserted_cart_id);
	    	$cart = $this->m_carts->get_detail_join($where)->result();
	    	$this->response(general_response('ok','Success add product to cart',$cart), 201);
    	}else{
    		$this->response(
    			general_response('false','Error add product to cart',$data), 400);
    	}

    }
    public function index_put(){

    }
    public function index_delete(){
    	// http://localhost/sodagarkom_web/api/v1/cart ['delete methods']['body x-www-form-urlencoded with keys id']

    	$id = $this->delete('id');
    	$where = array('id' => $id);
		$get_old_data = $this->m_products->get_detail($where)->result();
		$old_image_file = $get_old_data[0]->image_thumb;

		if($this->m_products->delete($where)){
			// removing images if its exist
			if($old_image_file != ''){				
				$this->remove_old_image($old_image_file);
			}
			$this->response(
        			general_response('ok','Succes deleting product',[]), 201);
		}
    }
}