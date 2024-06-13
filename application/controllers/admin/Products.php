<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Products extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
	protected function load_templates($data){
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/template/sidebar',$data);
		$this->load->view('admin/template/navbar',$data);
		$this->load->view('admin/template/content',$data);
		$this->load->view('admin/template/footer',$data);
	}
	public function index(){
		$data = array(
				'title' 		=> 'Products | Sodagar Komputer',
				'template'  	=> 'admin/pages/products',
				'menu_active'	=> 'products',
				'data'			=> json_encode($this->m_products->get_join()),
				'categories'	=> $this->m_categories->get_all()
		);
		$this->load_templates($data);
	}
	public function get_data(){
		echo json_encode($this->m_products->get_join());
	}

	public function insert(){
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$request = json_decode($stream_clean);

		$name			= test_input($request->name);
		$description 	= test_input($request->description);
		$price			= test_input($request->price);
		$stock 			= test_input($request->stock);
		$category_id	= test_input($request->category_id);

		if(validate($name) && validate($description) && validate($price) && validate($stock) && validate($category_id)){
			$data = array(
				'name' => $name,
				'description' => $description,
				'price' => $price,
				'stock' => $stock,
				'category_id' => $category_id
			);
			if($this->m_products->insert($data)){
				return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Success insert a new product!')));
			}else{
				return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 500, 'status' => 'error','message' => 'Internal server Error')));
			}
		}else{
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 400, 'status' => 'error','message' => 'Input field is empty')));
		}

		
	}
	public function update($id){
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$request = json_decode($stream_clean);

		$name			= test_input($request->name);
		$description 	= test_input($request->description);
		$price			= test_input($request->price);
		$stock 			= test_input($request->stock);
		$category_id	= test_input($request->category_id);

		if(validate($name) && validate($description) && validate($price) && validate($stock) && validate($category_id)){
			$data = array(
				'name' => $name,
				'description' => $description,
				'price' => $price,
				'stock' => $stock,
				'category_id' => $category_id
			);
			$where = array('id' => $id);
			if($this->m_products->update($data,$where)){
				return$this->output
		        ->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Product item has been updated!')));
			}
		}else{
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 400, 'status' => 'error','message' => 'Input field is empty')));
		}
	}
	public function delete($id){
		$where = array('id' => $id);

		if($this->m_products->delete($where)){
			return $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Product items has been deleted!')));
		}
	}
}