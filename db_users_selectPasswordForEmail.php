<?php

// get password for email

$uspw = '';

$usemal = sql_sanitize($usemal);

$stmtUsers = "SELECT uspw
FROM users
WHERE usemal = '".$usemal."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

while($db_row = $queryUsers->fetch_row()) {
	$uspw = $db_row[0];
}

?>