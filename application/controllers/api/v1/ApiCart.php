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
    	// http://localhost/sodagarkom_web/api/v1/cart/insert ['delete methods']['body x-www-form-urlencoded with keys id']
    	// insert new if product id not exits on cart, and update if product is exist
    	$customer_id = $this->post('customer_id');
    	$product_id = $this->post('product_id');
    	
    	$where_product = array('product_id' => $product_id,'customer_id' => $customer_id, 'status' => 'active');
    	$product_on_cart = $this->m_carts->get_detail($where_product);
    	
    	if($product_on_cart->num_rows() > 0){
    		// do update
    		$result_product_on_cart = $product_on_cart->result();
    		$updated_cart_id = $result_product_on_cart[0]->id;
			$where_update = array(
	    		'qty'	=> '1',
	    		'cart_id'=> $updated_cart_id
	    	);
		    	
    		if($this->m_carts->add_quantity($where_update)){
	    		$where = array('carts.id' => $updated_cart_id);
		    	$cart = $this->m_carts->get_detail_join($where)->result();
		    	$this->response(general_response('ok','Success update product quantity to cart',$cart), 201);
	    	}else{
	    		$this->response(general_response('false','Error add product quantity to cart',$data), 400);
	    	}
    	}else{
    		// do insert
    		$data = array(
	    		'customer_id' => $customer_id,
	    		'product_id' => $product_id,
	    		'quantity'	 => '1',
	    		'status'	 => 'active'
	    	);

    		if($this->db->insert('carts', $data)){
	    		$inserted_cart_id = $this->db->insert_id();
	    		$where = array('carts.id' => $inserted_cart_id);
		    	$cart = $this->m_carts->get_detail_join($where)->result();
		    	$this->response(general_response('ok','Berhasil menambahkan produk ke keranjang',$cart), 201);
	    	}else{
	    		$this->response(general_response('false','Error add product to cart',$data), 400);
	    	}
    	}

    }
    public function update_qty_put(){
	// http://localhost/sodagarkom_web/api/v1/cart/update_qty
    	$cart_id = $this->put('cart_id');
    	$qty = $this->put('quantity');

		$update = $this->db->update('carts',array('quantity' => $qty),array('id' => $cart_id));
		if($update){			
			$this->response(general_response('ok','Keranjang telah diupdate',[]), 200);
		}else{
    		$this->response(general_response('false','Error updating cart',[]), 400);
    	}
    }
    public function delete_get($cart_id){
    	// http://localhost/sodagarkom_web/api/v1/cart/delete ['get  methods'] bcs delete error occurs
    	// $cart_id = $this->delete('cart_id');

    	$where = array('carts.id' => $cart_id);
    	$cart_result = $this->m_carts->get_detail_join($where)->result();

		$delete = $this->db->delete('carts',array('id' => $cart_id));
		if($delete){			
			$this->response(general_response('ok','Keranjang telah dihapus',$cart_result), 200);
		}else{
    		$this->response(general_response('false','Error deleting cart',$cart_result), 400);
    	}
    }
}