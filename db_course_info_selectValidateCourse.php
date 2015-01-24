<?php

// check if course exists

$valid_course = '';

$ciscid = sql_sanitize($ciscid);
$cipdid = sql_sanitize($cipdid);
$cicrid = sql_sanitize($cicrid);

$stmtCourse_info = "SELECT cicrid
FROM course_info
WHERE ciscid = '".$ciscid."' AND cipdid = '".$cipdid."' AND cicrid = '".$cicrid."'";

$queryCourse_info = mysqli_query($dbconn, $stmtCourse_info) or log_sql_error($stmtCourse_info);

while($db_row = $queryCourse_info->fetch_row()) {
	$valid_course = 'Y';
}

?>