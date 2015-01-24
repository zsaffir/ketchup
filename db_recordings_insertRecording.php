<?php

// insert recording

$rcendt = date('Ymd');
$rcentm = date('His');

$rcfile = sql_sanitize($rcfile, 50);
$rcstnm = sql_sanitize($rcstnm, 50);
$rcrqid = sql_sanitize($rcrqid, 5);
$rcrcpt = sql_sanitize($rcrcpt, 5);
$rcrctt = sql_sanitize($rcrctt, 5);
$rcendt = sql_sanitize($rcendt, 8);
$rcentm = sql_sanitize($rcentm, 6);

$stmtRecordings = "INSERT INTO recordings
(rcfile, rcstnm, rcrqid, rcrcpt, rcrctt, rcendt, rcentm)
VALUES ('".$rcfile."', '".$rcstnm."', ".$rcrqid.", ".$rcrcpt.", ".$rcrctt.", ".$rcendt.", ".$rcentm.")";

$queryRecordings = mysqli_query($dbconn, $stmtRecordings) or log_sql_error($stmtRecordings);

?>