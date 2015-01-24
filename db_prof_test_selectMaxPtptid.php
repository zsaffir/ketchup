<?php

// get max professor test id

$max_ptptid = 0;

$stmtProf_test = "SELECT MAX(ptptid)
FROM prof_test";

$queryProf_test = mysqli_query($dbconn, $stmtProf_test) or log_sql_error($stmtProf_test);

while($db_row = $queryProf_test->fetch_row()) {
	$ptptid = $db_row[0];
	$max_ptptid = $ptptid;
}

?>