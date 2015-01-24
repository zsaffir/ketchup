<?php

// get unviewed request/recording counts

$arr_unviewed_counts = array();

$racmem = sql_sanitize($racmem);
$rqcmem = sql_sanitize($rqcmem);

$stmtRecordings = "SELECT 'requests', COUNT(rqrqid)
FROM requests
WHERE rqcmem = '".$rqcmem."' AND rqview = ' ' AND rqactv IN (' ', 'C')

UNION 

SELECT 'recordings', COUNT(rcrqid)
FROM recordings
JOIN recordings_access ON (rafile = rcfile)
WHERE racmem = '".$racmem."' AND raview = ' '";

$queryRecordings = mysqli_query($dbconn, $stmtRecordings) or log_sql_error($stmtRecordings);

while($db_row = $queryRecordings->fetch_row()) {
	$type = $db_row[0];
	$count = $db_row[1];
	
	$arr_unviewed_counts[$type] = $count;
}

?>