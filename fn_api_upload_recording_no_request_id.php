<?php

include('zz_props.php');
include('zz_dbconn.php');

//**********************************************************************************
//some http headers to prevent problems from app

header('Access-Control-Allow-Origin: *');

//**********************************************************************************
//increase time limit/memory limit

ini_set('max_execution_time', 1800); //1800 seconds = 30 minutes
ini_set('memory_limit','1500M'); //note that this matches the global settings in php.ini

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
	if($_FILES['file']['error'] > 0) {
		$got_error = 'Y';
		$error_msg = $_FILES['file']['error'];
	}
}

//**********************************************************************************
//get file info

if($got_error == '') {
	$file_name = $_FILES['file']['name'];
	$file_basename = substr($file_name, 0, -4);
	$file_extension = strtolower(substr($file_name, -3));
	$file_size = ($_FILES['file']['size'] / 1024); // in Kb
	$file_temp_name = $_FILES['file']['tmp_name'];

	$destination_file = getcwd().'/'.$recordings_directory.'/'.$file_name;
	$unique_file_iterator = 0;
	while(file_exists($destination_file)) {
		$unique_file_iterator++;
		$file_name = $file_basename.'_'.$unique_file_iterator.'.'.$file_extension;
		$destination_file = getcwd().'/'.$recordings_directory.'/'.$file_name;
	}
}



//**********************************************************************************
//more validation

//file type
if($got_error == '') {
	$arr_acceptable_extensions = array(
		'mp3' => 'mp3',
		'mp4' => 'mp4',
		'm4a' => 'm4a',
		'wav' => 'wav'
	);
	if(isset($arr_acceptable_extensions[$file_extension]) === FALSE) {
		$got_error = 'Y';
		$error_msg = 'You may only upload audio files';
	}
}

//file size?

//existing file with same name
if($got_error == '') {
	if(file_exists($destination_file)) {
		$got_error = 'Y';
		$error_msg = 'That file already exists';	
	}
}

//**********************************************************************************
//take action

if($got_error == '') {
	if(move_uploaded_file($file_temp_name, $destination_file)) {
		
	}

	$arr_response['success'] = true;
}
else {
	error_log($error_msg);

	foreach($_POST as $index => $value) {
		error_log($index.': '.$value);
	}

	$arr_response['success'] = false;
	$arr_response['message'] = $error_msg;
}

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>