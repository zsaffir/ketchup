<?php

// get user info for username/password

$usnid = '';
$usname = '';
$uspwrs = '';

$usemal = sql_sanitize($usemal);
$uspw = sql_sanitize($uspw);

$stmtUsers = "SELECT usnid, usname, usemal, uspwrs
FROM users
WHERE usemal = '".$usemal."' AND uspw = '".$uspw."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

while($db_row = $queryUsers->fetch_row()) {
	$usnid = $db_row[0];
	$usname = $db_row[1];
	$usemal = $db_row[2];
	$uspwrs = $db_row[3];
}

?>