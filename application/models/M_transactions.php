<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_transactions extends CI_Model {
	protected $tabel_name = 'transactions';

	public function get_all(){
		$query = $this->db->from($this->tabel_name)
				->order_by('id','desc')->get();
		return $query->result();
	}
	public function get_join($where = []){
		$this->db->select('t.*, c.username as customer_name');
	    $this->db->from('transactions t');
	    $this->db->join('customers c','c.id=t.customer_id');
	    if(!empty($where)){
	    	$this->db->where($where);
	    }
	    $this->db->order_by('t.id','desc');
	    $data = $this->db->get()->result();
	    return $data;
	}
	public function get_transaction_detail_join($where){
		$this->db->select('transactions.*, products.id as product_id, products.name as product_name, products.price as product_price, products.image_thumb as product_image, transaction_details.quantity as product_quantity, transaction_details.total_price as total_price, customers.username as customer_name');
		$this->db->from('transactions');
	    $this->db->join('transaction_details','transaction_details.transaction_id=transactions.id');
	    $this->db->join('customers','transactions.customer_id=customers.id');
	    $this->db->join('products','transaction_details.product_id=products.id');
	    $this->db->where($where);
	    $this->db->order_by('transactions.id','desc');
	    $data = $this->db->get()->result();
	    return $data;
	}
	public function get_transaction_detail($transaction_id){
		$this->db->select('td.*, p.name as product_name');
		$this->db->from('transactions t');
	    $this->db->join('transaction_details td','td.transaction_id=t.id');
	    $this->db->join('products p','td.product_id=p.id');
	    $this->db->where('t.id', $transaction_id);
	    $this->db->order_by('td.id','desc');
	    $data = $this->db->get()->result();
	    return $data;
	}
	public function insert($data){
		$query = $this->db->insert($this->tabel_name,$data);
		return $query;
	}
	public function update($data,$where){
		$query = $this->db->update($this->tabel_name,$data,$where);
		return $query;
	}
	public function delete($where){
		$query = $this->db->delete($this->tabel_name,$where);
		return $query;
	}
	public function get_detail($data){
		$this->db->from($this->tabel_name)
				 ->where($data);
		$query = $this->db->get();
		return $query;
	}
	public function get_trx_detail($transaction_id){
		$where = array('transaction_id' => $transaction_id);
		$this->db->from('transaction_details')
				 ->where($where);
		$query = $this->db->get();
		return $query->result();
	}
}
