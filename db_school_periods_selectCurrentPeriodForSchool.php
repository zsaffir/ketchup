<?php

// get current period for school

$sppdid = '';

$spscid = sql_sanitize($spscid);

$stmtSchool_periods = "SELECT sppdid
FROM school_periods
WHERE spscid = '".$spscid."' AND spcurr = 'Y'";

$querySchool_periods = mysqli_query($dbconn, $stmtSchool_periods) or log_sql_error($stmtSchool_periods);

while($db_row = $querySchool_periods->fetch_row()) {
	$sppdid = $db_row[0];
}

?>