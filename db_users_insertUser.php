<?php

// insert user

$usendt = date('Ymd');
$usentm = date('His');

$usemal = sql_sanitize($usemal, 50);
$usnid = sql_sanitize($usnid, 50);
$uspw = sql_sanitize($uspw, 50);
$usscid = sql_sanitize($usscid, 50);
$usname = sql_sanitize($usname, 50);
$usimg = sql_sanitize($usimg, 100);
$usendt = sql_sanitize($usendt, 8);
$usentm = sql_sanitize($usentm, 6);

$stmtUsers = "INSERT INTO users
(usemal, usnid, uspw, usscid, usname, usimg, usendt, usentm)
VALUES ('".$usemal."', '".$usnid."', '".$uspw."', '".$usscid."', '".$usname."', '".$usimg."', ".$usendt.", ".$usentm.")";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

?>