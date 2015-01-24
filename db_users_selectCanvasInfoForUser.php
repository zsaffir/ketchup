<?php

// get canvas info for user id

$usscid = '';
$uscanv = '';

$usemal = sql_sanitize($usemal);

$stmtUsers = "SELECT usscid, uscanv
FROM users
WHERE usemal = '".$usemal."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

while($db_row = $queryUsers->fetch_row()) {
	$usscid = $db_row[0];
	$uscanv = $db_row[1];
}

?>