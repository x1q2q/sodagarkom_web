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
        			$date_parsed = get_waktu($trx->created_at, 'Ymd');
        			$code = "#TRX-".$trx->id."-".$date_parsed;
        			$transaction_data[$trx->id] = [
        				'id' => $trx->id,
        				'code' => $code,
        				'total_amount'=>intval($trx->total_amount),
        				'total_amount_product' => 0,
        				'total_shipping' =>  intval($trx->total_shipping),
        				'status'=> $trx->status,
        				'created_at'=>$trx->created_at,
        				'customer_name'=>$trx->customer_name,
        				'customer_id'=>$trx->customer_id,
        				'payment_method' => $trx->payment_method,
        				'payment_proof'=>$trx->payment_proof,
        				'products'=> []
        			];
        		}
        		if($trx->product_id){
					$transaction_data[$trx->id]['products'][] = [
			            'product_id' => $trx->product_id,
			            'product_name' => $trx->product_name,
			            'product_price' => $trx->product_price,
			            'product_image' => $trx->product_image,
			            'product_quantity' => $trx->product_quantity,
			            'total_price' => $trx->total_price
			        ];
			        $transaction_data[$trx->id]['total_amount_product'] += $trx->total_price;
        		}
        	}
        	$transactions = array_values($transaction_data);
        	if(count($transactions) > 0){
        		$this->response(
        			general_response('ok','success get all data transactions filter by user id',$transactions), 200);
        	}else if(count($transactions) == 0){
        		$this->response(
        			general_response('ok','empty data transactions filter',$transactions), 200);
        	}else{
        		$this->response(general_response('false','No datas on transactions id',$transactions), 400);
        	}
        }else{
        	$where = array('transactions.customer_id' => $user_id);
        	$where['transactions.id'] = $trx_id;
        	$selected_transaction = $this->m_transactions->get_transaction_detail_join($where);
        	$transaction_data = [];
        	foreach ($selected_transaction as $trx) {
        		if(!isset($transaction_data[$trx->id])){
        			$date_parsed = get_waktu($trx->created_at, 'Ymd');
        			$code = "#TRX-".$trx->id."-".$date_parsed;
        			$transaction_data[$trx->id] = [
        				'id' => $trx->id,
        				'code' => $code,
        				'total_amount'=> intval($trx->total_amount),
        				'total_amount_product' => 0,
        				'total_shipping' => intval($trx->total_shipping),
        				'status'=> $trx->status,
        				'created_at'=>$trx->created_at,
        				'customer_name'=>$trx->customer_name,
        				'customer_id'=>$trx->customer_id,
        				'payment_method' => $trx->payment_method,
        				'payment_proof'=>$trx->payment_proof,
        				'products'=> []
        			];
        		}
        		if($trx->product_id){
					$transaction_data[$trx->id]['products'][] = [
			            'product_id' => $trx->product_id,
			            'product_name' => $trx->product_name,
			            'product_price' => $trx->product_price,
			            'product_image' => $trx->product_image,
			            'product_quantity' => $trx->product_quantity,
			            'total_price' => $trx->total_price
			        ];
			        $transaction_data[$trx->id]['total_amount_product'] += $trx->total_price;
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
    	$filters = array(
    		['id' => 'all', 'name' => 'Semua'],
    		['id' => 'accepted', 'name' => 'Selesai'],
    		['id' => 'pending', 'name' => 'Pending'],
    		['id' => 'rejected', 'name' => 'Dibatalkan'],
    		['id' => 'reserved', 'name' => 'Perlu Upload']
    	);
        $this->response(general_response('ok','Success all filter transactions',$filters), 200);
    }
    function calculateTotalPrice($carts) {
	    $totalPrice = 0;
	    foreach ($carts as $cart) {
	        $totalPrice += $cart->quantity * $cart->product_price;
	    }
	    return $totalPrice;
	}
    public function confirm_get($user_id){
    	// http://localhost/sodagarkom_web/api/v1/confirm/[user_id]
    	$this->load->model('m_carts');

    	$payment_method = 'Transfer Manual';
    	$payment_method_text = "Silahkan transfer manual ke nomer rekening berikut: 
    	- BRI (+62) 820000213291231, a.n Aleksander Grahambell.
    	- BCA (+46) 8394238022342, a.n Aleksander Grahambell.
    	- BNI  (+46) 8394238022342, a.n Aleksander Grahambell.";
    	$default_total_shipping = 35000;

    	$customer = $this->m_customers->get_detail(array('id'=>$user_id))->result();
    	$where = array('customer_id' => $user_id, 'status' => 'active');
    	$carts = $this->m_carts->get_detail_join($where);
    	$carts_quantity = $carts->num_rows();
    	$carts_price =$this->calculateTotalPrice($carts->result());

    	$trx_confirmation = array(
    		'customer_id' => $user_id,
    		'customer_fullname' => $customer[0]->full_name,
    		'customer_address' => $customer[0]->address,
    		'payment_method' => $payment_method,
    		'payment_method_text' => $payment_method_text,
    		'total_shipping' => $default_total_shipping,
    		'total_cart_qty' => $carts_quantity,
    		'total_cart_price' => $carts_price,
    		'total_amount' => $carts_price + $default_total_shipping
    	);
    	if($trx_confirmation){
    		$this->response(
    			general_response('ok','Success get transaction confirm detail',$trx_confirmation), 200);
    	}else{
    		$this->response(
    			general_response('false','Detail confirm transaction not found',$trx_confirmation), 400);
    	}
    }
    public function update_status_put(){
    	$data = array('status' => $this->put('status'));
    	$where = array('id' => $this->put('transaction_id'));
    	$update = $this->m_transactions->update($data, $where);
    	if($update){
    		$this->response(
    			general_response('ok','Success update status transaction',[]), 200);
    	}else{
    		$this->response(
    			general_response('false','Error update status transaction',[]), 400);
    	}
    }
    public function upload_image_post(){
    	// http://localhost/sodagarkom_web/api/v1/transaction/upload_image
    	// $this->post('keyField')
    	// $_FILES['file']['name']
    	if(!empty($_FILES['file']['name'])){
    		$id = $this->post('transaction_id');

    		$dir = './assets/uploads/payments';
			$content_directory = FCPATH . $dir;
			if (!is_dir($content_directory)) {
			    mkdir($content_directory, 0777, true);
			}

			$config['upload_path']          = $content_directory;
			$config['allowed_types']        = 'JPG|jpg|png|jpeg';
			$config['max_size']             = 2048; // maks 2mb
			$this->load->library('upload',$config);
		 	if (!$this->upload->do_upload('file')) {
		 		$this->response(general_response('false','Error upload ',[]), 500);
		 		$filename_upload = '';
			}else{
				$upload = array('upload_data' => $this->upload->data());
				$filename_upload = $upload['upload_data']['file_name'];

				$get_old_data = $this->m_transactions->get_detail(array('id' => $id))->result();
				$old_image_file = $get_old_data[0]->payment_proof;
				if($old_image_file != '' && $old_image_file != null){						
					$this->remove_old_image($old_image_file);
				}
		 	}

		 	$data = array('payment_proof' => $filename_upload,'status' => 'pending');
	    	$where = array('id' => $id);
	    	$update = $this->m_transactions->update($data, $where);
	    	if($update){
	    		$this->response(general_response('ok','Success update status transaction',[]), 200);
	    	}else{
	    		$this->response(general_response('false','Error upload image payment proof transaction',[]), 500);
	    	}		 	
		}else{
    		$this->response(general_response('false','Error upload image empty',[]), 400);
    	}	
    }
    public function remove_old_image($name_file){
		$path = FCPATH.'./assets/uploads/payments/'.$name_file;
		if(unlink($path))
		{
		    return true;
		}
		return false;
	}
	public function checkout_post(){
		$this->load->model('m_carts');
		// http://localhost/sodagarkom_web/api/v1/transaction/checkout
		// $this->post('keyField')
		
		// insert into transactions
		$customer_id = $this->post('customer_id');
		$trx_data = array(
			'customer_id' => $customer_id,
			'total_amount' => $this->post('total_amount'),
			'total_shipping' => $this->post('total_shipping'),
			'payment_method' => strtolower($this->post('payment_method')),
			'payment_proof' => '',
			'status'	=> 'reserved'
		);
		if($this->db->insert('transactions', $trx_data)){
			$inserted_trx_id = $this->db->insert_id();
			// loop on active carts tables by [customer_id]
			// insert into transaction_details values($this->tansaction_id_get(),  $for->product_id, $for->quantity, $for->total_price)
			$where = array('carts.customer_id' => $customer_id, 'carts.status' => 'active');
	    	$carts = $this->m_carts->get_detail_join($where)->result();
			foreach ($carts as $value) {
				$data = array(
					'transaction_id' => $inserted_trx_id,
					'product_id'	 => $value->product_id,
					'quantity'		 => $value->quantity,
					'total_price'	 => ($value->product_price * $value->quantity)
				);
				$this->db->insert('transaction_details', $data);
			}
			// change status carts table[user_id] from active -> completed
			$update_carts = $this->m_carts->update(array('status' => 'completed'), $where);
			if($update_carts){
				$this->response(general_response('ok','Berhasil menambahkan transaksi, silahkan cek di menu transaksi',[]), 201);
			}else{
				$this->response(general_response('false','Failed to insert transaction',[]), 500);
			}
		}
	}

}