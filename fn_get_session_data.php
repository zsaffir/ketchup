<?php

$user_id = '';
$user_nid = '';
$user_name = '';
$user_email = '';

$arr_prev_form_values = array();

//**********************************************************************************

if(isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];
}
if(isset($_SESSION['user_nid'])) {
	$user_nid = $_SESSION['user_nid'];
}
if(isset($_SESSION['user_name'])) {
	$user_name = $_SESSION['user_name'];
}
if(isset($_SESSION['user_email'])) {
	$user_email = $_SESSION['user_email'];
}

if(isset($_SESSION['arr_prev_form_values'])) {
	$arr_prev_form_values = $_SESSION['arr_prev_form_values'];
}

//**********************************************************************************
//unset prev form values

unset($_SESSION['arr_prev_form_values']);

?>