<?php

// get all users

$usnid = sql_sanitize($usnid);
$usnpw = sql_sanitize($usnpw, 50);

$stmtUsers = "UPDATE users
SET usnpw = '".$usnpw."'
WHERE usnid = '".$usnid."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

?>