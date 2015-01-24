<?php

// get first logon date

$usfldt = sql_sanitize($usfldt, 8);

$usemal = sql_sanitize($usemal);

$stmtUsers = "UPDATE users
SET usfldt = ".$usfldt." 
WHERE usemal = '".$usemal."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

?>