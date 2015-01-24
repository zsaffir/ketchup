<?php

// get user email for new id

$usname = '';
$usemal = '';
$uspwrs = '';

$usnid = sql_sanitize($usnid);

$stmtUsers = "SELECT usname, usemal, uspwrs
FROM users
WHERE usnid = '".$usnid."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

while($db_row = $queryUsers->fetch_row()) {
	$usname = $db_row[0];
	$usemal = $db_row[1];
	$uspwrs = $db_row[2];
}

?>