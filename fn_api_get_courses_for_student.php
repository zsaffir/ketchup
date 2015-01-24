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

if($version == '1.0') {
	$api_key = get('api_key');
	$api_pw = get('api_pw');
	$student_email = get('student_email');
	$canvas_access_token = '';
	$canvas_domain = '';

	//backward compatability
	if($student_email == '') {
		$student = get('student');
		if($student != '') {
			$student_email = $student;
			if(strpos($student_email, '@') === FALSE) {
				$student_email .= '@kellogg.northwestern.edu';
			}
		}
	}
}
else {
	$api_key = get('api_key'); 
	$api_pw = get('api_pw');
	$student_email = '';
	$canvas_access_token = get('canvas_access_token');
	$canvas_domain = get('canvas_domain');
}

$arr_response = api_get_courses_for_student($api_key, $api_pw, $version, $student_email, $canvas_access_token, $canvas_domain);

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>