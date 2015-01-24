<?php

// insert course date

$valid_course_date = '';

$cdscid = sql_sanitize($cdscid, 50);
$cdpdid = sql_sanitize($cdpdid, 10);
$cdcrid = sql_sanitize($cdcrid, 10);
$cddate = sql_sanitize($cddate, 8);
$cdtime = sql_sanitize($cdtime, 6);

$stmtCourse_dates = "INSERT INTO course_dates
(cdscid, cdpdid, cdcrid, cddate, cdtime)
VALUES ('".$cdscid."', '".$cdpdid."', '".$cdcrid."', ".$cddate.", ".$cdtime.")";

$queryCourse_dates = mysqli_query($dbconn, $stmtCourse_dates) or log_sql_error($stmtCourse_dates);

?>