<?php

include('zz_props.php');

include('fn_get_session_data.php');

//**********************************************************************************
//some http headers to prevent problems from app

header('Access-Control-Allow-Origin: *');

//**********************************************************************************

$file_size = 0;

//**********************************************************************************
//validation

$got_error = '';
$error_msg = '';

if($got_error == '') {
	//validate referer
	if($got_error == '') {
		$referer = $baseURL.'/education/app/listen.html';
		if($_SERVER['HTTP_REFERER'] != $referer) {
			$got_error = 'Y';
			$error_msg = 'Referer does not match';
		}
	}

	//validate user is signed in
	if($got_error == '') {
		if($user_email == '') {
			$got_error = 'Y';
			$error_msg = 'User is not signed in';
		}
	}
}

//**********************************************************************************
//**********************************************************************************
//**********************************************************************************
//get file size

if($got_error == '') {
	echo time();
}
else {
	error_log($error_msg);
}

//**********************************************************************************

?>