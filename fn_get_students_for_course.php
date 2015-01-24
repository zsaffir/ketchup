<?php

//**********************************************************************************
//get students in a class based on the id in the kellogg cms
//
//incoming parms: $parm_cms_id
//
//outgoing parms: $parm_arr_students
//
//**********************************************************************************

$parm_arr_students = array();

//**********
//create and execute the curl handle for images
//**********

$username = '';
$password = '';

$url = 'https://www4.kellogg.northwestern.edu/courses/Roster.aspx?section='.$parm_cms_id;
$credentials = $username.':'.$password;
$headers = array(
	'Connection: keep-alive',
	'Authorization: Basic '.base64_encode($credentials)
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'); //arbitrarily picked firefox as a user agent
curl_setopt($ch, CURLOPT_REFERER, 'https://www4.kellogg.northwestern.edu/Courses.NET/Default.aspx'); //set class url as the referring url
$response_images = curl_exec($ch);

//**********
//parse out info
//**********
//first isolate the tables of photos
$work_string = $response_images;
$start_pos = strpos($work_string, '<table BORDER=1 cellpadding=0 cellspacing=2 name="ClassmatesTable">');
$work_string = substr($work_string, $start_pos);

$end_pos = strpos($work_string, '<div id="footer">');
$work_string = substr($work_string, 0, $end_pos);

//now split into the tables
$work_string = strtolower($work_string);
$arr_tables = explode('<table border=1 cellpadding=0 cellspacing=2>', $work_string);

foreach($arr_tables as $table) {
	//split into cells
	$arr_cells = explode('<td', $table);
	
	foreach($arr_cells as $cell) {
		//determine if this entry is worth looking at (some are not cells)
		$start_pos = strpos($cell, '<img src="');
		if($start_pos !== false) {
			//get student image
			$start_pos += 10;

			$cell = substr($cell, $start_pos);
			$end_pos = strpos($cell, '"');
			$student_image = substr($cell, 0, $end_pos);

			//make http instead of https
			$student_image = str_replace('https://', 'http://', $student_image);
			$student_image = trim($student_image);

			//get student name
			$start_pos = strpos($cell, '<b>') + 3;
			$cell = substr($cell, $start_pos);

			$cell = str_replace('<br>', '', $cell);
			$cell = str_replace("\n", '', $cell);
			$cell = str_replace("\r", ' ', $cell);
			$cell = str_replace('</b> ', '', $cell);
			$cell = str_replace('</td>', '', $cell);
			$cell = strip_tags($cell);
			$student_name = trim($cell);

			$parm_arr_students[$student_name] = array('img' => $student_image);
		}
	}
}

//**********
//**********
//**********
//now these are the same, so use the same curl response
//**********
//**********
//**********

$response_ids = $response_images;

//**********
//parse out info
//**********
//first isolate the table of students
$work_string = $response_ids;
$start_pos = strpos($work_string, '<TABLE border=0>');
$work_string = substr($work_string, $start_pos);

$end_pos = strpos($work_string, '</table>');
$work_string = substr($work_string, 0, $end_pos);

//split into rows
$work_string = strtolower($work_string);
$arr_rows = explode('<tr>', $work_string);

foreach($arr_rows as $row) {
	//cant do '<a' because some students dont link (see Jeff Prussack in 18091)

	$start_pos = strpos($row, '<td') + 1;
	$row = substr($row, $start_pos);

	$start_pos = strpos($row, '<td') + 1;
	$row = substr($row, $start_pos);
		
	$start_pos = strpos($row, '<td') + 1;
	$row = substr($row, $start_pos);
	
	if($start_pos !== false) {
		$row = '<'.$row;

		//get student name
		$formatted_student_name = $row;
		$end_pos = strpos($formatted_student_name, '</td>');
		$formatted_student_name = substr($formatted_student_name, 0, $end_pos);
		$formatted_student_name = strip_tags($formatted_student_name);

		//name is given as (lastname, firstname) so we need to switch it to match the other screen
		$nbsp_pos = strpos($formatted_student_name, '&nbsp;');
		$first_name = substr($formatted_student_name, ($nbsp_pos + 6));
		$last_name = substr($formatted_student_name, 0, $nbsp_pos - 1);

		$student_name = $first_name.' '.$last_name;
		$student_name = trim($student_name);

		//get user id
		$user_id = $row;
		$start_pos = strpos($user_id, 'mailto:') + 7;
		$user_id = substr($user_id, $start_pos);

		$end_pos = strpos($user_id, '"');
		$user_id = substr($user_id, 0, $end_pos);

		//insert user id into array
		$parm_arr_students[$student_name]['email'] = $user_id;
	}
}

//**********************************************************************************
//remove blank record

unset($parm_arr_students['']);

//**********************************************************************************
//for now, remove students who dont have an email address
//not sure why this happens, but see 18091 for example

foreach($parm_arr_students as $student_name => $arr_student) {
	$email = $arr_student['email'];
	if($email == '') {
		unset($parm_arr_students[$student_name]);
	}
}

//**********************************************************************************

?>