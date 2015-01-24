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

if($version == '1.0') {
	$api_key = get('api_key');
	$api_pw = get('api_pw');
	$course_id = get('course_id');
	$student_email = get('student_email');
	$canvas_access_token = '';
	$canvas_domain = '';
	
	if($student_email == '') {
		$student_id = get('student_id');
		if($student_id != '') {
			$student_email = $student_id.'@kellogg.northwestern.edu';
		}
	}
}
else {
	$api_key = get('api_key');
	$api_pw = get('api_pw');
	$course_id = get('course_id');
	$student_email = '';
	$canvas_access_token = get('canvas_access_token');
	$canvas_domain = get('canvas_domain');
}

//**********************************************************************************

$arr_response = api_get_students_for_course($api_key, $api_pw, $version, $course_id, $student_email, $canvas_access_token, $canvas_domain);

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>