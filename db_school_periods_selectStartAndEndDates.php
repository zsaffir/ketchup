<?php

// get start and end dates for given school/period

$spstdt = 0;
$spendt = 0;

$spscid = sql_sanitize($spscid);
$sppdid = sql_sanitize($sppdid);

$stmtSchool_periods = "SELECT spstdt, spendt
FROM school_periods
WHERE spscid = '".$spscid."' AND sppdid = '".$sppdid."'";

$querySchool_periods = mysqli_query($dbconn, $stmtSchool_periods) or log_sql_error($stmtSchool_periods);

while($db_row = $querySchool_periods->fetch_row()) {
	$spstdt = $db_row[0];
	$spendt = $db_row[1];
}

?>