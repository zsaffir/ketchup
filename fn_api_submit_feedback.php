<?php

include('zz_props.php');
include('zz_dbconn.php');

//**********************************************************************************
//some http headers to prevent problems from app

header('Access-Control-Allow-Origin: *');

//**********************************************************************************

$arr_response = array();

//**********************************************************************************
//validate

$got_error = '';
$error_msg = '';

if($got_error == '') {
	$api_key = post('api_key');
	$api_pw = post('api_pw');
	if(validate_api_key($api_key, $api_pw) != true) {
		$got_error = 'Y';
		$error_msg = 'Invalid KetchUp API Key';
	}
}

if($got_error == '') {
	$user_email = post('user_email');
	
	//backward compatability
	if($user_email == '') {
		$user_id = post('user_id');
		$user_email = $user_id;
	}
	$feedback_text = post('feedback_text');
	
	//get user name
	$usname = '';

	$usemal = $user_email;
	include('db_users_selectNameForEmail.php');

	//submit feedback through test flight
	$to = 'dff0e24ed4da6ec7c3492c62ed36298d_ijkustcefu4donrugqydi@n.testflightapp.com';
	//$to = 'support@ketchuptechnology.com';
	$from = 'KetchUp Notifications <notifications@'.$_SERVER['HTTP_HOST'].'>';
	$subject = 'Feedback from '.$usname.' ('.$user_email.')';
	$message = $feedback_text;
	$headers = 'From: '.$from."\r\n";
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= 'Content-type:text/html; charset=iso-8859-1'."\r\n";
	if(mail($to, $subject, $message, $headers)) { 

	}
	else {
		error_log('could not send the feedback email');
	}

}

//**********************************************************************************
//take action

if($got_error == '') {
	$arr_response['success'] = true;
	$arr_response['message'] = 'Feedback submitted';
}
else {
	$arr_response['success'] = false;
	$arr_response['message'] = $error_msg;
	error_log('feedback problem: '.$error_msg);
}

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>