<?php

include('zz_props.php');
include('zz_dbconn.php');

include('fn_get_session_data.php');

//**********************************************************************************
//some http headers to prevent problems from app

header('Access-Control-Allow-Origin: *');

//**********************************************************************************

$file_size = 0;

//**********************************************************************************

$media_file = get('media_file');

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

	//validate user can view this recording
	if($got_error == '') {
		$valid_file = '';

		$racmem = $user_email;
		$rafile = $media_file;
		include('db_recordings_access_selectValidateFilePermissions.php');

		if($valid_file == '') {
			$got_error = 'Y';
			$error_msg = 'User is not permitted to listen to this recording';
		}
	}
}

//**********************************************************************************
//**********************************************************************************
//**********************************************************************************
//get file size

if($got_error == '') {
	$headers = array(
		'Connection: keep-alive',
		'Authorization: '.$dropbox_authorization
	);
	$url = 'https://api.dropbox.com/1/metadata/dropbox/recordings/'.$media_file;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$json_metadata = curl_exec($ch);

	$arr_metadata = json_decode($json_metadata , true);
	$file_size = $arr_metadata['bytes'];
}
else {
	error_log($error_msg);
}

echo $file_size;

//**********************************************************************************

include('zz_dbclose.php');

?>