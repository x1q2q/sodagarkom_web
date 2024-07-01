<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_categories extends CI_Model {
	protected $tabel_name = 'categories';

	public function get_all(){
		$query = $this->db->from($this->tabel_name)
				->order_by('id','asc')->get();
		return $query->result();
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
	public function get_join(){
		$data = $this->db->select('categories.id, categories.name, categories.description, categories.image_thumb, products.id as product_id, products.name as product_name, products.description as product_description, products.price as product_price, products.stock_temporary as product_stock_temporary, products.image_thumb as product_image_thumb')
                       ->from('categories')
                       ->join('products', 'products.category_id = categories.id', 'left')
                       ->get();
	    return $data;
	}
	public function get_detail_join($data){
		$data = $this->db->select('categories.id, categories.name, categories.description, categories.image_thumb, products.id as product_id, products.name as product_name, products.description as product_description, products.price as product_price, products.stock_temporary as product_stock_temporary, products.image_thumb as product_image_thumb')
                       ->from('categories')
                       ->join('products', 'products.category_id = categories.id', 'left')
                       ->where($data)
                       ->get();
		return $data;
	}
}
