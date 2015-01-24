<?php

// get email

$cfemal = '';
$cfvers = '';

$cfcfid = sql_sanitize($cfcfid);
$cfcncd = sql_sanitize($cfcncd);
$cfusnid = sql_sanitize($cfusnid);

$stmtConfirmations = "SELECT cfemal, cfvers
FROM confirmations
WHERE cfcfid = ".$cfcfid." AND cfcncd = '".$cfcncd."' AND cfusnid = '".$cfusnid."' AND cfactv = ' '";

$queryConfirmations = mysqli_query($dbconn, $stmtConfirmations) or log_sql_error($stmtConfirmations);

while($db_row = $queryConfirmations->fetch_row()) {
	$cfemal = $db_row[0];
	$cfvers = $db_row[1];
}

?>