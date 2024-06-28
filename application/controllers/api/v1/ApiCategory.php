<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class ApiCategory extends RESTController {
	function __construct(){
		parent::__construct();
		if (isset($_SERVER['HTTP_ORIGIN'])) {
	        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	        header('Access-Control-Allow-Credentials: true');
	        header('Access-Control-Max-Age: 86400');    // cache for 1 day
  	    }

  	    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  	        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
  	            header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");

  	        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
  	            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  	        exit(0);
  	    }
	}

    public function index_get($id=''){
    	// http://localhost/sodagarkom_web/api/v1/categories ['get methods'] for all datas
    	// http://localhost/sodagarkom_web/api/v1/category/[8] ['get methods id'] for detail datas
        if(empty($id)){
        	$categories = $this->m_categories->get_all();
        	if($categories){
        		$this->response(
        			general_response('ok','success get all categories',$categories), 200);
        	}else{
        		$this->response(
        			general_response('false','No datas on categories ',$categories), 404);
        	}
        }else{
        	// insetad doing 2 query, just use sigle query then loop
        	// $all_category = $this->m_categories->get_all();
        	// $categories = [];
        	// foreach ($all_category as $key => $value) {
        	// 	$product_query = $this->m_products->get_detail(['category_id' => $value->id]);
        	// 	$new_data = [
        	// 		'id' => $value->id,
        	// 		'name' => $value->name,
        	// 		'description' => $value->description,
        	// 		'image_thumb' => $value->image_thumb,
        	// 		'products'=> [
        	// 			'data' => $product_query->result(), 
        	// 			'rows'=>$product_query->num_rows()
        	// 		]
        	// 	];
        	// 	array_push($categories,$new_data);
        	// }
        	$where = array('categories.id' => $id);
        	$selected_category = $this->m_categories->get_detail_join($where)->result();
        	$category_data = [];
			foreach ($selected_category as $category) {
			    if (!isset($category_data[$category->id])) {
			        $category_data[$category->id] = [
			            'id' => $category->id,
			            'name' => $category->name,
			            'description' => $category->description,
			            'image_thumb' => $category->image_thumb,
			            'products' => []
			        ];
			    }
			    if ($category->product_id) {
			        $category_data[$category->id]['products'][] = [
			            'id' => $category->product_id,
			            'category_id'=>$category->id,
			            'name' => $category->product_name,
			            'description' => $category->product_description,
			            'price' => $category->product_price,
			            'stock_temporary' => $category->product_stock_temporary, 
			            'image_thumb' => $category->product_image_thumb,
			            'category_name' => $category->name
			        ];
			    }
			}
			$category = array_values($category_data);
        	if($category){
        		$this->response(
        			general_response('ok','success get detail category',$category), 200);
        	}else{
        		$this->response(
        			general_response('false','Detail category not found',$category), 404);
        	}
        }
    }
}