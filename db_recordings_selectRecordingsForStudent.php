<?php

// get recordings for student

$arr_recordings = array();

$racmem = sql_sanitize($racmem);
$rbstem = sql_sanitize($rbstem);

/*$stmtRecordings = "SELECT rcfile AS file, rcstnm AS stnm, rqcrnm AS crnm, rcrcpt AS rcpt, rcrctt AS rctt, rcendt AS endt, rcentm AS entm, 0 AS request_endt, 0 AS request_entm
FROM recordings
JOIN recordings_access ON (rafile = rcfile AND racmem = '".$racmem."')
JOIN requests ON (rqrqid = rcrqid)
JOIN school_periods ON (spscid = rqscid AND sppdid = rqpdid AND spcurr = 'Y')

UNION 

SELECT '' AS file, usname AS stnm, rqcrnm AS crnm, 0 AS rcpt, 0 AS rctt, rqdate AS endt, rqtime AS entm, rqendt AS request_endt, rqentm AS request_entm
FROM requests
JOIN requests_access ON (rbrqid = rqrqid AND rbstem = '".$rbstem."')
JOIN users ON usemal = rqcmem 
JOIN school_periods ON (spscid = rqscid AND sppdid = rqpdid AND spcurr = 'Y')
WHERE rqactv = ' ' AND rqcmem <> '' 

ORDER BY endt DESC, entm DESC, file, request_endt DESC, request_entm DESC";*/

$stmtRecordings = "SELECT rcfile AS file, rcstnm AS stnm, cicrnm AS crnm, rcrcpt AS rcpt, rcrctt AS rctt, rcendt AS endt, rcentm AS entm, 0 AS request_endt, 0 AS request_entm
FROM recordings
JOIN recordings_access ON (rafile = rcfile AND racmem = '".$racmem."')
JOIN requests ON (rqrqid = rcrqid)
JOIN course_info ON (ciscid = rqscid AND cipdid = rqpdid AND cicrid = rqcrid)
JOIN school_periods ON (spscid = rqscid AND sppdid = rqpdid AND spcurr = 'Y')

UNION 

SELECT '' AS file, usname AS stnm, cicrnm AS crnm, 0 AS rcpt, 0 AS rctt, rqdate AS endt, rqtime AS entm, rqendt AS request_endt, rqentm AS request_entm
FROM requests
JOIN requests_access ON (rbrqid = rqrqid AND rbstem = '".$rbstem."')
JOIN users ON usemal = rqcmem 
JOIN course_info ON (ciscid = rqscid AND cipdid = rqpdid AND cicrid = rqcrid)
JOIN school_periods ON (spscid = rqscid AND sppdid = rqpdid AND spcurr = 'Y')
WHERE rqactv = ' ' AND rqcmem <> '' 

ORDER BY endt DESC, entm DESC, file, request_endt DESC, request_entm DESC";

$queryRecordings = mysqli_query($dbconn, $stmtRecordings) or log_sql_error($stmtRecordings);

while($db_row = $queryRecordings->fetch_row()) {
	$rcfile = $db_row[0];
	$rcstnm = $db_row[1];
	$cicrnm = $db_row[2];
	$rcrcpt = $db_row[3];
	$rcrctt = $db_row[4];
	$rcendt = $db_row[5];

	$course = $cicrnm;
	if($rcrctt > 1) {
		$course .= ' - part '.$rcrcpt;
	}

	$arr_entry = array( 
		'course' => $course,
		'date' => date('n/j/y', make_unix_date($rcendt)),
		'recorder' => $rcstnm
	);

	if($rcfile != '') {
		$arr_entry['filename'] = $rcfile;
		$arr_entry['expiration_date'] = 20141231;
	}

	$arr_recordings[] = $arr_entry;
}


?>