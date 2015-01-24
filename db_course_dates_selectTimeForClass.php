<?php

// get time for course

$cdtime = 0;

$cdcrid = sql_sanitize($cdcrid);
$cddate = sql_sanitize($cddate);

$stmtCourse_dates = "SELECT cdtime
FROM course_dates
WHERE cdcrid = '".$cdcrid."' AND cddate>= ".$cddate;

$queryCourse_dates = mysqli_query($dbconn, $stmtCourse_dates) or log_sql_error($stmtCourse_dates);

while($db_row = $queryCourse_dates->fetch_row()) {
	$cdtime = $db_row[0];
}

?>