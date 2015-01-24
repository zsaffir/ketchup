<?php

// get max confirmation id

$max_cfcfid = 0;

$stmtConfirmations = "SELECT MAX(cfcfid)
FROM confirmations";

$queryConfirmations = mysqli_query($dbconn, $stmtConfirmations) or log_sql_error($stmtConfirmations);

while($db_row = $queryConfirmations->fetch_row()) {
	$cfcfid = $db_row[0];
	$max_cfcfid = $cfcfid;
}

?>