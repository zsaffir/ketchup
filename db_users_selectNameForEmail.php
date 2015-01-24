<?php

// get user name for user id

$usname = '';

$usemal = sql_sanitize($usemal);

$stmtUsers = "SELECT usname
FROM users
WHERE usemal = '".$usemal."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

while($db_row = $queryUsers->fetch_row()) {
	$usname = $db_row[0];
}

?>