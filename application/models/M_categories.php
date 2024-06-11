<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_categories extends CI_Model {
	protected $tabel_name = 'categories';

	public function get_all(){
		$query = $this->db->get($this->tabel_name);
		return $query->result();
	}
	public function tambah($data){
		$query = $this->db->insert($this->tabel_name,$data);
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
