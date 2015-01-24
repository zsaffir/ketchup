<?php

// get first logon date

$usfldt = 0;

$usemal = sql_sanitize($usemal);

$stmtUsers = "SELECT usfldt
FROM users
WHERE usemal = '".$usemal."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

while($db_row = $queryUsers->fetch_row()) {
	$usfldt = $db_row[0];
}

?>