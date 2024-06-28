<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class ApiTransaction extends RESTController {
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
	public function index_get($user_id,  $filter='all', $trx_id=''){
    	// http://localhost/sodagarkom_web/api/v1/transactions/[user_id]/[filter] ['get methods'] for all datas by user/customer id
    	// http://localhost/sodagarkom_web/api/v1/transaction/[user_id]/[filter]/[trx_id] ['get methods id'] for detail datas
        if(empty($trx_id)){
        	$where = array('transactions.customer_id' => $user_id);
        	if($filter != 'all'){
        		$where['transactions.status'] = $filter;
        	}
        	$selected_transaction = $this->m_transactions->get_transaction_detail_join($where);
        	$transaction_data = [];
        	foreach ($selected_transaction as $trx) {
        		if(!isset($transaction_data[$trx->id])){
        			$transaction_data[$trx->id] = [
        				'id' => $trx->id,
        				'total_amount'=>$trx->total_amount,
        				'status'=> $trx->status,
        				'created_at'=>$trx->created_at,
        				'customer_name'=>$trx->customer_name,
        				'customer_id'=>$trx->customer_id,
        				'payment_proof'=>$trx->payment_proof,
        				'products'=> []
        			];
        		}
        		if($trx->product_id){
					$transaction_data[$trx->id]['products'][] = [
			            'product_id' => $trx->product_id,
			            'product_name' => $trx->product_name,
			            'product_price' => $trx->product_price,
			            'product_quantity' => $trx->product_quantity,
			            'total_price' => $trx->total_price
			        ];
        		}
        	}
        	$transactions = array_values($transaction_data);
        	if($transactions){
        		$this->response(
        			general_response('ok','success get all data transaction by user id',$transactions), 200);
        	}else{
        		$this->response(
        			general_response('false','No datas on transactions id',$transactions), 404);
        	}
        }else{
        	$where = array('transactions.customer_id' => $user_id);
        	$where['transactions.id'] = $trx_id;
        	$selected_transaction = $this->m_transactions->get_transaction_detail_join($where);
        	$transaction_data = [];
        	foreach ($selected_transaction as $trx) {
        		if(!isset($transaction_data[$trx->id])){
        			$transaction_data[$trx->id] = [
        				'id' => $trx->id,
        				'total_amount'=>$trx->total_amount,
        				'status'=> $trx->status,
        				'created_at'=>$trx->created_at,
        				'customer_name'=>$trx->customer_name,
        				'customer_id'=>$trx->customer_id,
        				'payment_proof'=>$trx->payment_proof,
        				'products'=> []
        			];
        		}
        		if($trx->product_id){
					$transaction_data[$trx->id]['products'][] = [
			            'product_id' => $trx->product_id,
			            'product_name' => $trx->product_name,
			            'product_price' => $trx->product_price,
			            'product_quantity' => $trx->product_quantity,
			            'total_price' => $trx->total_price
			        ];
        		}
        	}
        	$transaction = array_values($transaction_data);
        	if($transaction){
        		$this->response(
        			general_response('ok','success get detail transaction',$transaction), 200);
        	}else{
        		$this->response(
        			general_response('false','Detail transaction not found',$transaction), 404);
        	}
        }
    }
    public function filter_get(){
    	// http://localhost/sodagarkom_web/api/v1/transaction/filters
    	$data = array(
    		['id' => '1', 'name' => 'all', 'display' => 'Semua'],
    		['id' => '2', 'name' => 'accepted', 'display' => 'Selesai'],
    		['id' => '3', 'name' => 'pending', 'display' => 'Pending'],
    		['id' => '4', 'name' => 'rejected', 'display' => 'Dibatalkan'],
    		['id' => '5', 'name' => 'reserved', 'display' => 'Perlu Upload']
    	);
        $this->response(
        			general_response('ok','Success all filter transactions',$data), 200);
    }

}