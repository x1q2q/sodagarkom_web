<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_transactions extends CI_Model {
	protected $tabel_name = 'transactions';

	public function get_all(){
		$query = $this->db->from($this->tabel_name)
				->order_by('id','desc')->get();
		return $query->result();
	}
	public function get_join(){
		$this->db->select('t.*, c.username as customer_name');
	    $this->db->from('transactions t');
	    $this->db->join('customers c','c.id=t.customer_id');
	    $this->db->order_by('t.id','desc');
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
}
