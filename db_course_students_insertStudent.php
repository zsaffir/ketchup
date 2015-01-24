<?php

// insert student into class

$csscid = sql_sanitize($csscid, 50);
$cspdid = sql_sanitize($cspdid, 10);
$cscrid = sql_sanitize($cscrid, 10);
$csemal = sql_sanitize($csemal, 50);

$stmtCourse_students = "INSERT INTO course_students
(csscid, cspdid, cscrid, csemal)
VALUES ('".$csscid."', '".$cspdid."', '".$cscrid."', '".$csemal."')";

$queryCourse_students = mysqli_query($dbconn, $stmtCourse_students) or log_sql_error($stmtCourse_students);

?>