<?php

// get dates for course

$arr_dates = array();

$cddate_today = date('Ymd');

$cdcrid = sql_sanitize($cdcrid);
$cddate_today = sql_sanitize($cddate_today);

$stmtCourse_dates = "SELECT cddate
FROM course_dates
WHERE cdcrid = '".$cdcrid."' AND cddate >= ".$cddate_today." 
ORDER BY cddate";

$queryCourse_dates = mysqli_query($dbconn, $stmtCourse_dates) or log_sql_error($stmtCourse_dates);

while($db_row = $queryCourse_dates->fetch_row()) {
	$cddate = $db_row[0];

	$arr_dates[] = array(
		'ccyymmdd' => $cddate,
		'display_date' => date('l, M. jS', make_unix_date($cddate)),
	);
}

?>