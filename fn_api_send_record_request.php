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

$version = post('version', '1.0');

//**********************************************************************************

if($version == '1.0') {
	$api_key = get('api_key');
	$api_pw = get('api_pw');
	$student_id = '';
	$student_email = get('student_email');
	$student_name = get('student_name');
	$school_id = get('school_id');
	$course_id = get('course_id');
	$course_name = get('course_name');
	$date = get('date');
	$time = get('time');
	$classmate_id = '';
	$classmate_email = get('classmate_email');
	$classmate_name = get('classmate_name');
	$source = get('source', 'iOS');
	$canvas_access_token = '';
	$canvas_domain = '';

	//backward compatibility
	if($school_id == '') {
		//parse out school id
		$arr_email_parts = explode('@', $student_email);
		$school_id = $arr_email_parts[1];
	}

	//if we have no time, get from course_info table
	if($time == '') {
		$cdtime = 0;

		$cdcrid = $course_id;
		$cddate = $date;
		include('db_course_dates_selectTimeForClass.php');

		$time = $cdtime;
	}
}
else {
	$api_key = post('api_key');
	$api_pw = post('api_pw');
	$student_id = post('student_id');
	$student_email = '';
	$student_name = post('student_name');
	$school_id = '';
	$course_id = post('course_id');
	$course_name = post('course_name');
	$date = post('date');
	$time = post('time');
	$classmate_id = post('classmate_id');
	$classmate_email = '';
	$classmate_name = post('classmate_name');
	$source = post('source');
	$canvas_access_token = post('canvas_access_token');
	$canvas_domain = post('canvas_domain');
}

//**********************************************************************************

$arr_response = api_send_record_request($api_key, $api_pw, $version, $student_id, $student_name, $student_email, $school_id, $course_id, $course_name, $date, $time, $classmate_id, $classmate_email, $classmate_name, $source, $canvas_access_token, $canvas_domain);

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>