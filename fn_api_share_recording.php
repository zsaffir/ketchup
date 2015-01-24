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
$request_id = get('request_id');
$shared_by = get('shared_by');
$shared_with = get('shared_with');
$override_prev_access = get('override_prev_access'); //we may want to use this if sharing with multiple users

$arr_response = api_share_recording($api_key, $api_pw, $request_id, $shared_by, $shared_with, $override_prev_access);

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>