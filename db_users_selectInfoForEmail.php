<?php

// get user email for new id

$usname = '';
$uspwrs = '';

$usemal = sql_sanitize($usemal);

$stmtUsers = "SELECT usname, uspwrs
FROM users
WHERE usemal = '".$usemal."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

while($db_row = $queryUsers->fetch_row()) {
	$usname = $db_row[0];
	$uspwrs = $db_row[1];
}

?>