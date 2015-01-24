<?php

// get requests

$arr_requests = array();

$rqcmem = sql_sanitize($rqcmem);

$stmtRequests = "SELECT rqrqid, rqdate, cicrnm, usname, usimg
FROM requests
JOIN requests_access ON (rbrqid = rqrqid)
JOIN users ON (usemal = rbstem)
JOIN course_info ON (ciscid = rqscid AND cipdid = rqpdid AND cicrid = rqcrid)
JOIN school_periods ON (spscid = ciscid AND sppdid = cipdid AND spcurr = 'Y')
WHERE rqcmem = '".$rqcmem."' AND rqactv = ' '";

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

while($db_row = $queryRequests->fetch_row()) {
	$rqrqid = $db_row[0];
	$rqdate = $db_row[1];
	$cicrnm = $db_row[2];
	$usname = $db_row[3];
	$usimg = $db_row[4];

	//this is sort of a stopgap measure
	//since we want the array to start from 0

	/*$arr_details = array(
		'request_id' => (int) $rqrqid,
		'request_date' => $rqdate,
		'course_name' => $cicrnm,
		'requester_name' => $usname //we could improve how this works - right now it will only show a single name (the last one)
	);

	if($usimg != '') {
		$arr_details['requester_img'] = $usimg;
	}
	*/
	
	if(isset($arr_requests[$rqrqid]) === FALSE) {
		$arr_details = array(
			'request_date' => $rqdate,
			'course_name' => $cicrnm,
			'arr_requesters' => array()
		);

		$arr_requests[$rqrqid] = $arr_details;
	}

	$arr_requester = array(
		'name' => $usname
	);

	if($usimg != '') {
		$arr_requester['img'] = $usimg;
	}

	$arr_requests[$rqrqid]['arr_requesters'][] = $arr_requester;
}

//in the end we need an array of child arrays
//that looks something like this
/*arr_requests: [
	{
		request_id: 1435,
		request_date: "20140909",
		course_name: "Math - Addition",
		requester_name: "Mark Vermeersch"
	},
	{
		request_id: 1544,
		request_date: "20141004",
		course_name: "Math - Addition",
		requester_name: "Mark Vermeersch"
	}
]*/

$arr_new_requests = array();

foreach($arr_requests as $request_id => $arr_details) {
	$request_date = $arr_details['request_date'];
	$course_name = $arr_details['course_name'];
	$arr_requesters = $arr_details['arr_requesters'];

	$requester_name = '';
	$requester_img = '';

	foreach($arr_requesters as $arr_details_2) {
		$name = $arr_details_2['name'];

		$img = '';
		if(isset($arr_details_2['img'])) {
			$img = $arr_details_2['img'];
		}

		//this could be improved for 3+
		if($requester_name != '') {
			$requester_name .= ' and ';
		}
		$requester_name .= $name;

		//take first img so it matches name
		if($requester_img == '') {
			if($img != '') {
				$requester_img = $img;
			}
		}
	}

	$arr_new_request = array(
		'request_id' => (int) $request_id,
		'request_date' => $request_date,
		'course_name' => $course_name,
		'requester_name' => $requester_name
	);

	if($requester_img != '') {
		$arr_new_request['requester_img'] = $requester_img;
	}

	$arr_new_requests[] = $arr_new_request;
}


$arr_requests = $arr_new_requests;

?>