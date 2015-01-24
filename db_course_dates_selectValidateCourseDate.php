<?php

// validate course date

$valid_course_date = '';

$cdscid = sql_sanitize($cdscid);
$cdpdid = sql_sanitize($cdpdid);
$cdcrid = sql_sanitize($cdcrid);
$cddate = sql_sanitize($cddate);

$stmtCourse_dates = "SELECT cdcrid
FROM course_dates
WHERE cdscid = '".$cdscid."' AND cdpdid = '".$cdpdid."' AND cdcrid = '".$cdcrid."' AND cddate = ".$cddate;

$queryCourse_dates = mysqli_query($dbconn, $stmtCourse_dates) or log_sql_error($stmtCourse_dates);

while($db_row = $queryCourse_dates->fetch_row()) {
	$valid_course_date = 'Y';
}

?>