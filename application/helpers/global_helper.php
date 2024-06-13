<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function test_input($data){
	$data = trim($data);
 	$data = stripslashes($data);
 	$data = htmlspecialchars($data);
 	return $data;
}
function validate($text){
	return (!ctype_space($text) && $text !== "" && $text !== null);
}
function category_assets(){
	return base_url('assets/uploads/categories/');
}
function product_assets(){
	return base_url('assets/uploads/products/');
}
function payment_assets(){
	return base_url('assets/uploads/payments/');
}
