<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class ApiProduct extends RESTController {
	function __construct(){
		parent::__construct();
	}

    public function index_get($id=''){
    	// http://localhost/sodagarkom_web/api/v1/products ['get methods'] for all datas
    	// http://localhost/sodagarkom_web/api/v1/product/[8] ['get methods id'] for detail datas
        if(empty($id)){
        	$products = $this->m_products->get_all();
        	if($products){
        		$this->response(
        			general_response('ok','success get all data products',$products), 200);
        	}else{
        		$this->response(
        			general_response('false','No datas on products',$products), 404);
        	}
        }else{
        	$where = array('id' => $id);
        	$products = $this->m_products->get_detail($where)->result();
        	if($products){
        		$this->response(
        			general_response('ok','success get detail product',$products), 200);
        	}else{
        		$this->response(
        			general_response('false','Detail product not found',$products), 404);
        	}
        }
    }
    public function index_delete(){
    	// http://localhost/sodagarkom_web/api/v1/product ['delete methods']['body x-www-form-urlencoded with keys id']

    	$id = $this->delete('id');
    	$where = array('id' => $id);
		$get_old_data = $this->m_products->get_detail($where)->result();
		$old_image_file = $get_old_data[0]->image_thumb;

		if($this->m_products->delete($where)){
			// removing images if its exist
			if($old_image_file != ''){				
				$this->remove_old_image($old_image_file);
			}
			$this->response(
        			general_response('ok','Succes deleting product',[]), 201);
		}
    }
}