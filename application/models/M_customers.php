<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_customers extends CI_Model {
	protected $tabel_name = 'customers';

	public function get_all(){
		$query = $this->db->from($this->tabel_name)
				->order_by('id','desc')->get();
		return $query->result();
	}
	public function insert($data){
		$query = $this->db->insert($this->tabel_name,$data);
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
	public function update_data($data,$where){
		$query = $this->db->update($this->tabel_name,$data,$where);
		return $query;
	}
}
