<?php

// get confirmation code

$rqcncd = '';

$rqrqid = sql_sanitize($rqrqid);

$stmtRequests = "SELECT rqcncd
FROM requests
WHERE rqrqid = ".$rqrqid;

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

while($db_row = $queryRequests->fetch_row()) {
	$rqcncd = $db_row[0];
}

?>