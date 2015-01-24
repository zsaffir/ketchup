<?php

// get max request id

$max_rqrqid = 0;

$stmtRequests = "SELECT MAX(rqrqid)
FROM requests";

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

while($db_row = $queryRequests->fetch_row()) {
	$rqrqid = $db_row[0];
	$max_rqrqid = $rqrqid;
}

?>