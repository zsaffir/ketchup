<?php

// get dates for course

$arr_dates = array();

$cdcrid = sql_sanitize($cdcrid);

$stmtCourse_dates = "SELECT cddate
FROM course_dates
WHERE cdcrid = '".$cdcrid."' 
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