<?php

// get upcoming unconfirmed requests

$arr_requests = array();

$rqdate_in_10 = sql_sanitize($rqdate_in_10);
$rqtime_in_10 = sql_sanitize($rqtime_in_10);

$stmtRequests = "SELECT rqrqid, cicrnm, classmate.usname, classmate.usemal, student.usname
FROM requests
JOIN course_info ON (cicrid = rqcrid)
JOIN users AS classmate ON (classmate.usemal = rqcmem)
JOIN requests_access ON (rbrqid = rqrqid)
JOIN users AS student ON (student.usemal = rbstem)
WHERE rqcfdt = 0 AND rqcftm = 0 AND rqactv = ' ' AND ((rqdate < ".$rqdate_in_10.") OR ((rqdate = ".$rqdate_in_10.") AND (rqtime < ".$rqtime_in_10.")))";

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

while($db_row = $queryRequests->fetch_row()) {
	$rqrqid = $db_row[0];
	$cicrnm = $db_row[1];
	$classmate_usname = $db_row[2];
	$classmate_usemal = $db_row[3];
	$student_usname = $db_row[4];
	
	if(isset($arr_requests[$rqrqid]) === FALSE) {
		$arr_details = array(
			'cicrnm' => $cicrnm
			'classmate_usname' => $classmate_usname,
			'classmate_usemal' => $classmate_usemal,
			'arr_student_usname' => array()
		);

		$arr_requests[$rqrqid] = $arr_details;
	}

	$arr_requests[$rqrqid]['arr_student_usname'][] = $student_usname;
}

?>