<?php

// insert course

$ciscid = sql_sanitize($ciscid, 50);
$cipdid = sql_sanitize($cipdid, 10);
$cicrid = sql_sanitize($cicrid, 10);

$stmtCourse_info = "INSERT INTO course_info
(ciscid, cipdid, cicrid)
VALUES ('".$ciscid."', '".$cipdid."', '".$cicrid."')";

$queryCourse_info = mysqli_query($dbconn, $stmtCourse_info) or log_sql_error($stmtCourse_info);

?>