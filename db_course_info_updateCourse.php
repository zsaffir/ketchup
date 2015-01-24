<?php

// update course

$cicrnm = sql_sanitize($cicrnm, 50);
$ciprem = sql_sanitize($ciprem, 50);
$ciabal = sql_sanitize($ciabal, 2);

$ciscid = sql_sanitize($ciscid);
$cipdid = sql_sanitize($cipdid);
$cicrid = sql_sanitize($cicrid);

$stmtCourse_info = "UPDATE course_info
SET cicrnm = '".$cicrnm."', ciprem = '".$ciprem."', ciabal = ".$ciabal."
WHERE ciscid = '".$ciscid."' AND cipdid = '".$cipdid."' AND cicrid = '".$cicrid."'";

$queryCourse_info = mysqli_query($dbconn, $stmtCourse_info) or log_sql_error($stmtCourse_info);

?>