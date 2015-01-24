<?php

// insert user

$usendt = date('Ymd');
$usentm = date('His');

$usfldt = $usendt;

$usemal = sql_sanitize($usemal, 50);
$usfldt = sql_sanitize($usfldt, 8);
$usendt = sql_sanitize($usendt, 8);
$usentm = sql_sanitize($usentm, 6);

$stmtUsers = "INSERT INTO users
(usemal, usfldt, usendt, usentm)
VALUES ('".$usemal."', ".$usfldt.", ".$usendt.", ".$usentm.")";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

?>