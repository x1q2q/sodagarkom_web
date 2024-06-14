<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Categories extends CI_Controller {
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
				'title' 		=> 'Categories | Sodagar Komputer',
				'template'  	=> 'admin/pages/categories',
				'menu_active'	=> 'categories',
				'data'			=> json_encode($this->m_categories->get_all()),
				'category_assets' => category_assets(),
		);
		$this->load_templates($data);
	}
	public function get_data(){
		echo json_encode($this->m_categories->get_all());
	}

	public function insert(){
		$name = test_input($this->input->post('name'));
		$description = test_input($this->input->post('description'));

		if(validate($name) && validate($description)){
			$data = array(
				'name' => $name,
				'description' => $description
			);

			if(!empty($_FILES['files']['name'])){
				$config['upload_path']          = './assets/uploads/categories';
				$config['allowed_types']        = 'JPG|jpg|png|jpeg';
				$config['max_size']             = 2048; // maks 2mb
				$this->load->library('upload',$config);
			 	if (!$this->upload->do_upload('files')) {
			 			return $this->output->set_content_type('application/json')
			        ->set_output(json_encode(array('code' => 500, 'status' => 'error','message' =>  strip_tags($this->upload->display_errors()))));
				}else{
					$upload = array('upload_data' => $this->upload->data());
					$data['image_thumb'] = $upload['upload_data']['file_name'];
			 	}
			}

			if($this->m_categories->insert($data)){
				return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Success insert a new category!')));
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
		$name = test_input($this->input->post('name'));
		$description = test_input($this->input->post('description'));

		if(validate($name) && validate($description)){
			$data = array(
				'name' => $name,
				'description' => $description
			);

			if(!empty($_FILES['edit_files']['name'])){
				$config['upload_path']          = './assets/uploads/categories';
				$config['allowed_types']        = 'JPG|jpg|png|jpeg';
				$config['max_size']             = 2048; // maks 2mb
				$this->load->library('upload',$config);
			 	if (!$this->upload->do_upload('edit_files')) {
			 		return $this->output->set_content_type('application/json')
			        ->set_output(json_encode(array('code' => 500, 'status' => 'error','message' =>  strip_tags($this->upload->display_errors()))));
				}else{
					$upload = array('upload_data' => $this->upload->data());
					$data['image_thumb'] = $upload['upload_data']['file_name'];
					
					$get_old_data = $this->m_categories->get_detail(array('id' => $id))->result();
					$old_image_file = $get_old_data[0]->image_thumb;
					if($old_image_file != ''){						
						$this->remove_old_image($old_image_file);
					}					
			 	}
			}
			$where = array('id' => $id);
			if($this->m_categories->update($data,$where)){
				return$this->output
		        ->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Category item has been updated!')));
			}
		}else{
			return $this->output->set_content_type('application/json')
		        ->set_output(json_encode(array('code' => 400, 'status' => 'error','message' => 'Input field is empty')));
		}
	}
	public function remove_old_image($name_file){
		$path = APPPATH.'../assets/uploads/categories/'.$name_file;
		if(unlink($path))
		{
		    return true;
		}
		return false;
	}
	public function delete($id){
		$where = array('id' => $id);
		$get_old_data = $this->m_categories->get_detail($where)->result();
		$old_image_file = $get_old_data[0]->image_thumb;

		if($this->m_categories->delete($where)){
			// removing images if its exist
			if($old_image_file != ''){				
				$this->remove_old_image($old_image_file);
			}

			return $this->output
	        ->set_content_type('application/json')
	        ->set_output(json_encode(array('code' => 200, 'status' => 'ok','message' => 'Category items has been deleted!')));
		}
	}
}