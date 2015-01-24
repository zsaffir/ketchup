<?php

// insert recording

$raendt = date('Ymd');
$raentm = date('His');

$rafile = sql_sanitize($rafile, 50);
$racmem = sql_sanitize($racmem, 50);

$stmtRecordings_access = "INSERT INTO recordings_access
(rafile, racmem, raendt, raentm)
VALUES ('".$rafile."', '".$racmem."', ".$raendt.", ".$raentm.")";

$queryRecordings_access = mysqli_query($dbconn, $stmtRecordings_access) or log_sql_error($stmtRecordings_access);

?>