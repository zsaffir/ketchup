<?php

// get request

$course_name = '';
$classmate_usname = '';
$classmate_usemal = '';
$arr_students = array();

$rqrqid = sql_sanitize($rqrqid);
$rqactv = sql_sanitize($rqactv);

$stmtRequests = "SELECT cicrnm, classmate.usname, classmate.usemal, student.usname, student.usemal
FROM requests
JOIN course_info ON (ciscid = rqscid AND cicrid = rqcrid)
JOIN users AS classmate ON (classmate.usemal = rqcmem)
JOIN requests_access ON (rbrqid = rqrqid)
JOIN users AS student ON (student.usemal = rbstem)
WHERE rqrqid = ".$rqrqid." AND rqactv = '".$rqactv."'";

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

while($db_row = $queryRequests->fetch_row()) {
	$course_name = $db_row[0];
	$classmate_usname = $db_row[1];
	$classmate_usemal = $db_row[2];

	$student_usname = $db_row[3];
	$student_usemal = $db_row[4];

	$arr_students[] = array(
		'name' => $student_usname,
		'email' => $student_usemal
	);
}

?>