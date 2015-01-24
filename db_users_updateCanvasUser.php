<?php

// update new user id

$usname = sql_sanitize($usname, 50);
$usscid = sql_sanitize($usscid, 50);
$uscanv = sql_sanitize($uscanv, 100);

$usemal = sql_sanitize($usemal);

$stmtUsers = "UPDATE users
SET usname = '".$usname."', usscid = '".$usscid."', uscanv = '".$uscanv."'
WHERE usemal = '".$usemal."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

?>