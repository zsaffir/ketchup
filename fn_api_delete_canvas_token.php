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

if($version == '2.0') {
	$api_key = get('api_key');
	$api_pw = get('api_pw');
	$canvas_access_token = get('canvas_access_token');
	$canvas_domain = get('canvas_domain');
}

//**********************************************************************************

$arr_response = api_delete_canvas_token($api_key, $api_pw, $version, $canvas_access_token, $canvas_domain);

//**********************************************************************************
//echo out the response just in case api key/pw validation fails

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>