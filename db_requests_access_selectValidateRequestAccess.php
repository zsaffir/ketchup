<?php

// validate course date

$valid_request_access = '';

$rbrqid = sql_sanitize($rbrqid);
$rbstem = sql_sanitize($rbstem);

$stmtRequests_access = "SELECT rbrqid
FROM requests_access
WHERE rbrqid = ".$rbrqid." AND rbstem = '".$rbstem."'";

$queryRequests_access = mysqli_query($dbconn, $stmtRequests_access) or log_sql_error($stmtRequests_access);

while($db_row = $queryRequests_access->fetch_row()) {
	$valid_request_access = 'Y';
}

?>