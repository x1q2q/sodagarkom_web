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
// for api structure response
function general_response($status, $message, $resources){
	return [
		'data' 		=> $resources,
		'rows'		=> count($resources),
		'status'	=> $status,
		'message'	=> $message
	];
}
function get_waktu($strip,$format){
	$asal = new Datetime($strip);
	$waktu = $asal->format($format);
	return $waktu;
}