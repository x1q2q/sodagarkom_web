<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_products extends CI_Model {
	protected $tabel_name = 'products';

	public function get_all(){
		$query = $this->db->from($this->tabel_name)
				->order_by('id','desc')->get();
		return $query->result();
	}
	public function get_join(){
		$this->db->select('p.*, c.name as category_name');
	    $this->db->from('products p');
	    $this->db->join('categories c','c.id=p.category_id');
	    $this->db->order_by('p.id','desc');
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
	public function get_detail_join($data){
		$this->db->select('p.*, c.name as category_name');
	    $this->db->from('products p');
	    $this->db->join('categories c','c.id=p.category_id');
	    $this->db->where($data);
	    $this->db->order_by('p.id','desc');
		$query = $this->db->get();
		return $query;
	}
	public function update_stock($handleTo, $qty, $product_id){
		if($handleTo == 'accepted'){
			$this->db->set('stock', 'stock-'.$qty, FALSE);
		}else if($handleTo == 'rejected'){
			$this->db->set('stock_temporary', 'stock_temporary+'.$qty, FALSE);
		}
		$this->db->where('id', $product_id);
		$this->db->update('products');
	}
}
