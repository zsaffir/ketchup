<?php

include('zz_props.php');
include('zz_dbconn.php');

//**********************************************************************************
//some http headers to prevent problems from app

header('Access-Control-Allow-Origin: *');

//**********************************************************************************

$arr_response = array();

//**********************************************************************************

$api_key = post('api_key');
$api_pw = post('api_pw');
$authenticate = validate_api_key($api_key, $api_pw);

//**********************************************************************************

if($authenticate == true) {
	//get request_id
	$request_id = post('request_id');

	$rqactv = '';

	$rqrqid = $request_id;
	include('db_requests_selectRequestStatus.php');

	$arr_response['success'] = true;
	$arr_response['message'] = $rqactv;
}
else {
	$arr_response['success'] = false;
	$arr_response['message'] = 'Invalid KetchUp API Key';
}

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>