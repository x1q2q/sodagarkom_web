<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class ApiProduct extends RESTController {
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

    public function index_get($id=''){
    	// http://localhost/sodagarkom_web/api/v1/products ['get methods'] for all datas
    	// http://localhost/sodagarkom_web/api/v1/product/[8] ['get methods id'] for detail datas
        if(empty($id)){
        	$products = $this->m_products->get_join();
        	if($products){
        		$this->response(
        			general_response('ok','success get all data products',$products), 200);
        	}else{
        		$this->response(
        			general_response('false','No datas on products',$products), 404);
        	}
        }else{
        	$where = array('p.id' => $id);
        	$product = $this->m_products->get_detail_join($where)->result();
        	if($product){
        		$this->response(
        			general_response('ok','success get detail product',$product), 200);
        	}else{
        		$this->response(
        			general_response('false','Detail product not found',$product), 404);
        	}
        }
    }
}