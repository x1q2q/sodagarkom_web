<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_carts extends CI_Model {
	protected $tabel_name = 'carts';

	public function get_all(){
		$query = $this->db->from($this->tabel_name)
				->order_by('id','desc')->get();
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
	public function get_detail_join($data){
		$data = $this->db->select('carts.*, products.name as product_name, products.price as product_price, products.stock_temporary as stock, products.image_thumb as product_image, categories.name as category_name')
               ->from('carts')
               ->join('products', 'carts.product_id = products.id')
               ->join('categories', 'products.category_id = categories.id')
               ->where($data)
               ->get();
		return $data;
	}
	
}
