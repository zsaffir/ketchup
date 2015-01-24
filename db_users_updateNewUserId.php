<?php

// update new user id

$usemal = strtolower($usemal);

$usemal = sql_sanitize($usemal);
$usnid = sql_sanitize($usnid, 50);

$stmtUsers = "UPDATE users
SET usnid = '".$usnid."' 
WHERE LOWER(usemal) = '".$usemal."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

?>