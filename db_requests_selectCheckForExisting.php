<?php

// get existing similar requests

$rqrqid = '';

$rqscid = sql_sanitize($rqscid);
$rqpdid = sql_sanitize($rqpdid);
$rqcrid = sql_sanitize($rqcrid);
$rqcmem = sql_sanitize($rqcmem);
$rqdate = sql_sanitize($rqdate);
$rqtime = sql_sanitize($rqtime);

$stmtRequests = "SELECT rqrqid
FROM requests
WHERE rqscid = '".$rqscid."' AND rqpdid = '".$rqpdid."' AND rqcrid = '".$rqcrid."' AND rqcmem = '".$rqcmem."' AND rqdate = ".$rqdate." AND rqtime = ".$rqtime." AND rqactv = ' '";

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

while($db_row = $queryRequests->fetch_row()) {
	$rqrqid = $db_row[0];
}

?>