<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dashboard extends CI_Model {
	public function get_all_rows($table_name,$where=null){
		$query = $this->db->from($table_name)->get();
		if($where!=null){
			$query = $this->db->from($table_name)->where($where)->get();
		}
		return $query->num_rows();
	}
	public function get_total_revenue(){
		$this->db->select('sum(total_amount) as total_revenue');
	    $this->db->from('transactions');
	    $this->db->where('status','accepted');
	    $data = $this->db->get()->result();
	    return $data[0]->total_revenue;
	}
	public function get_transaction_per($type){
		$query = $this->db->from('transactions_per_'.$type)->get();
		return $query->result();
	}
	public function get_detail_transaction($type,$where){
		$query = $this->db->from('transactions_per_'.$type)
				->where($where)->get();
		return $query->result();
	}
	
}
