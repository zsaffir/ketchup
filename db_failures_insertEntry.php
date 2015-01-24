<?php

// insert failures entry

$flendt = date('Ymd');
$flentm = date('His');

$flflid = sql_sanitize($flflid, 5);
$flnid = sql_sanitize($flnid, 50);
$flnpw = sql_sanitize($flnpw, 50);
$flendt = sql_sanitize($flendt, 8);
$flentm = sql_sanitize($flentm, 6);

$stmtFailures = "INSERT INTO failures
(flflid, flnid, flnpw, flendt, flentm)
VALUES (".$flflid.", '".$flnid."', '".$flnpw."', ".$flendt.", ".$flentm.")";

$queryFailures = mysqli_query($dbconn, $stmtFailures) or log_sql_error($stmtFailures);

?>