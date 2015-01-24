<?php

// check if student is in a course

$valid_course_student = '';

$csscid = sql_sanitize($csscid);
$cspdid = sql_sanitize($cspdid);
$cscrid = sql_sanitize($cscrid);
$csemal = sql_sanitize($csemal);

$stmtCourse_students = "SELECT cscrid
FROM course_students
WHERE csscid = '".$csscid."' AND cspdid = '".$cspdid."' AND cscrid = '".$cscrid."' AND csemal = '".$csemal."'";

$queryCourse_students = mysqli_query($dbconn, $stmtCourse_students) or log_sql_error($stmtCourse_students);

while($db_row = $queryCourse_students->fetch_row()) {
	$valid_course_student = 'Y';
}

?>