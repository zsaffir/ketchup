<?php

// validate request/confirmation code combo

$rqactv = '';
$valid_request = '';

$rbrqid = sql_sanitize($rbrqid);
$rbcncd = sql_sanitize($rbcncd);

$stmtRequests = "SELECT rqactv
FROM requests 
JOIN requests_access ON (rbrqid = rqrqid)
WHERE rbrqid = ".$rbrqid." AND rbcncd = '".$rbcncd."'";

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

while($db_row = $queryRequests->fetch_row()) {
	$rqactv = $db_row[0];
	$valid_request = 'Y';
}

?>