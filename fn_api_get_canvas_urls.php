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
//version

$version = get('version', '1.0');

//**********************************************************************************

$api_key = get('api_key'); 
$api_pw = get('api_pw');

$arr_response = api_get_canvas_urls($api_key, $api_pw, $version);

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>