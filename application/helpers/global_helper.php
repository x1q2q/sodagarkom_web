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