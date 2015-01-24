<?php

// insert course

$ciscid = sql_sanitize($ciscid, 50);
$cipdid = sql_sanitize($cipdid, 10);
$cicrid = sql_sanitize($cicrid, 10);
$cicrnm = sql_sanitize($cicrnm, 50);
$ciprem = sql_sanitize($ciprem, 50);
$ciabal = sql_sanitize($ciabal, 2);

$stmtCourse_info = "INSERT INTO course_info
(ciscid, cipdid, cicrid, cicrnm, ciprem, ciabal)
VALUES ('".$ciscid."', '".$cipdid."', '".$cicrid."', '".$cicrnm."', '".$ciprem."', ".$ciabal.")";

$queryCourse_info = mysqli_query($dbconn, $stmtCourse_info) or log_sql_error($stmtCourse_info);

?>