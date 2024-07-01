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
	// 400 bad request ->input field kosong
	// 404 id tidak ditemukan
	// 409 conflict/duplicate
	// 500 internal server error -> saat input error db/server
	// 200 everythings ok/updated
	// 201 success created
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
function validate_duplicate($field, $value){
	$CI = get_instance();
	// field between [username, email] return true if its duplicate/same value
	
	$where = array($field => $value);
	$rows = $CI->m_customers->get_detail($where)->result();
	return (count($rows) > 0);
}
function validate_oldval($field, $current_val, $id){
	$CI = get_instance();
	// if email / username updated is different value from old data
	// first to checking duplicate email (with some condition)
	
	$where_old_val = array('id' => $id);
	$old_val = $CI->m_customers->get_detail($where_old_val)->result_array();
	$old_field_val = $old_val[0][$field]; // between [username, email]
	return ($current_val != $old_field_val);
}