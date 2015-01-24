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

$version = get('version', '1.0');

//**********************************************************************************

if($version == '1.0') {
	$api_key = get('api_key');
	$api_pw = get('api_pw');
	$email = get('email');
	$reset_requests = get('reset_requests');
	$reset_recordings = get('reset_recordings');
	$user_id = '';
	$canvas_access_token = '';
	$canvas_domain = '';
}
else {
	$api_key = get('api_key');
	$api_pw = get('api_pw');
	$email = '';
	$reset_requests = get('reset_requests');
	$reset_recordings = get('reset_recordings');
	$user_id = get('user_id');
	$canvas_access_token = get('canvas_access_token');
	$canvas_domain = get('canvas_domain');
}

//**********************************************************************************

$arr_response = api_get_unviewed_counts($api_key, $api_pw, $version, $email, $reset_requests, $reset_recordings, $user_id, $canvas_access_token, $canvas_domain);

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>