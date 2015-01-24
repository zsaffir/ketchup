<?php

// insert email

$ptendt = date('Ymd');
$ptentm = date('His');

$ptptid = sql_sanitize($ptptid, 5);
$ptemal = sql_sanitize($ptemal, 100);
$ptendt = sql_sanitize($ptendt, 8);
$ptentm = sql_sanitize($ptentm, 6);

$stmtProf_test = "INSERT INTO prof_test
(ptptid, ptemal, ptendt, ptentm)
VALUES (".$ptptid.", '".$ptemal."', ".$ptendt.", ".$ptentm.")";

$queryProf_test = mysqli_query($dbconn, $stmtProf_test) or log_sql_error($stmtProf_test);

?>