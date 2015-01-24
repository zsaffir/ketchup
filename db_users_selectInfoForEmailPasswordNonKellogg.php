<?php

// get user info for user id/password

$usname = '';
$usemal = '';
$uspwrs = '';

$usnid = sql_sanitize($usnid);
$usnpw = sql_sanitize($usnpw);

$stmtUsers = "SELECT usname, usemal, uspwrs
FROM users
WHERE usnid = '".$usnid."' AND usnpw = '".$usnpw."' AND usscid <> 'kellogg.northwestern.edu'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

while($db_row = $queryUsers->fetch_row()) {
	$usname = $db_row[0];
	$usemal = $db_row[1];
	$uspwrs = $db_row[2];
}

?>