<?php

// get recordings for student

$arr_recordings = array();

$rcrqid = sql_sanitize($rcrqid);

$stmtRecordings = "SELECT rcfile
FROM recordings
WHERE rcrqid = ".$rcrqid;

$queryRecordings = mysqli_query($dbconn, $stmtRecordings) or log_sql_error($stmtRecordings);

while($db_row = $queryRecordings->fetch_row()) {
	$rcfile = $db_row[0];

	$arr_entry = array(
		'filename' => $rcfile
	);
	
	$arr_recordings[] = $arr_entry;
}


?>