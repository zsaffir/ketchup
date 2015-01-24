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
//validate

$api_key = get('api_key');
$api_pw = get('api_pw');
$user_nid = get('user_nid');
$email = get('email');
$version = get('version', '1.0');

$arr_response = api_send_email_confirmation($api_key, $api_pw, $user_nid, $email, $version);

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>