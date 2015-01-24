<?php

include('zz_props.php');
include('zz_dbconn.php');

include('fn_api_functions.php');

//**********************************************************************************
//some http headers to prevent problems from app

header('Access-Control-Allow-Origin: *');

//**********************************************************************************

$arr_response = array();

//**********************************************************************************

$api_key = get('api_key');
$api_pw = get('api_pw');
$user_id = get('user_id');
$user_email = get('user_email');
$password = get('password');
$version = get('version', '1.0');

if($user_id == '') {
	$user_id = $user_email;
}

$arr_valid_credentials = api_validate_credentials($api_key, $api_pw, $user_id, $password, $version);

//**********************************************************************************
//take action

$success = $arr_valid_credentials['success'];
if($success == true) {
	$arr_response['success'] = $success;
	if($version != '1.2') {
		$arr_response['user_id'] = $arr_valid_credentials['user_id'];
		$arr_response['user_nid'] = $arr_valid_credentials['user_nid'];
		$arr_response['user_pw'] = $arr_valid_credentials['user_pw'];
	}
	$arr_response['user_name'] = $arr_valid_credentials['user_name'];
	$arr_response['user_email'] = $arr_valid_credentials['user_email'];
	$arr_response['password_reset'] = $arr_valid_credentials['password_reset'];
}
else {
	$arr_response['success'] = $success;
	$arr_response['message'] = $arr_valid_credentials['message'];
}

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>