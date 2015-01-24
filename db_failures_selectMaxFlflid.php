<?php

// get max failure id

$max_flflid = 0;

$stmtFailures = "SELECT MAX(flflid)
FROM failures";

$queryFailures = mysqli_query($dbconn, $stmtFailures) or log_sql_error($stmtFailures);

while($db_row = $queryFailures->fetch_row()) {
	$flflid = $db_row[0];
	$max_flflid = $flflid;
}

?>