<?php

// get request status

$rqactv = '';

$rqrqid = sql_sanitize($rqrqid);

$stmtRequests = "SELECT rqactv
FROM requests
WHERE rqrqid = ".$rqrqid;

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

while($db_row = $queryRequests->fetch_row()) {
	$rqactv = $db_row[0];
}

?>