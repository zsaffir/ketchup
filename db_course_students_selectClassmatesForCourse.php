<?php

// get classmates for course

$arr_classmates = array();

$cscrid = sql_sanitize($cscrid);

$stmtCourse_students = "SELECT usemal, usname, usimg
FROM course_students
JOIN users ON usemal = csemal
WHERE cscrid = '".$cscrid."' AND usactv = ' '
ORDER BY usname";

$queryCourse_students = mysqli_query($dbconn, $stmtCourse_students) or log_sql_error($stmtCourse_students);

while($db_row = $queryCourse_students->fetch_row()) {
	$usemal = $db_row[0];
	$usname = $db_row[1];
	$usimg = $db_row[2];

	$arr_classmate = array(
		'id' => $usemal, //this is for backwards compatability and should be deleted
		'email' => $usemal,
		'name' => $usname,
	);

	if($usimg != '') {
		$arr_classmate['img_url'] = $usimg;
	}

	$arr_classmates[] = $arr_classmate;
}

?>