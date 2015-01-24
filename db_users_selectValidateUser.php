<?php

// validate a user

$valid_user = '';

$usemal = sql_sanitize($usemal);

$stmtUsers = "SELECT usemal
FROM users
WHERE usemal = '".$usemal."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

while($db_row = $queryUsers->fetch_row()) {
	$valid_user = 'Y';
}

?>