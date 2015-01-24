<?php

// get requests

$arr_requests = array();

$rqcmem = sql_sanitize($rqcmem);

$stmtRequests = "SELECT rqrqid, rqdate, cicrnm, usname, usimg
FROM requests
JOIN requests_access ON (rbrqid = rqrqid)
JOIN users ON (usemal = rbstem)
JOIN course_info ON (ciscid = rqscid AND cicrid = rqcrid)
WHERE rqcmem = '".$rqcmem."' AND rqactv = ' '";

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

while($db_row = $queryRequests->fetch_row()) {
	$rqrqid = $db_row[0];
	$rqdate = $db_row[1];
	$cicrnm = $db_row[2];
	$usname = $db_row[3];
	$usimg = $db_row[4];

	$arr_request = array(
		'request_id' => (int) $rqrqid,
		'request_date' => $rqdate,
		'course_name' => $cicrnm,
		'requester_name' => $usname //we could improve how this works - right now it will only show a single name (the last one)
	);
	
	if($usimg != '') {
		$arr_request['requester_img'] = $usimg;
	}
	
	$arr_requests[] = $arr_request;
}

?>