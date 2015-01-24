<?php

// insert request

$rqendt = date('Ymd');
$rqentm = date('His');

$rqrqid = sql_sanitize($rqrqid, 5);
$rqscid = sql_sanitize($rqscid, 50);
$rqpdid = sql_sanitize($rqpdid, 10);
$rqcrid = sql_sanitize($rqcrid, 10);
$rqdate = sql_sanitize($rqdate, 8);
$rqtime = sql_sanitize($rqtime, 6);
$rqcmem = sql_sanitize($rqcmem, 50);
$rqcncd = sql_sanitize($rqcncd, 10);
$rqendt = sql_sanitize($rqendt, 8);
$rqentm = sql_sanitize($rqentm, 6);

$stmtRequests = "INSERT INTO requests
(rqrqid, rqscid, rqpdid, rqcrid, rqdate, rqtime, rqcmem, rqcncd, rqendt, rqentm)
VALUES (".$rqrqid.", '".$rqscid."', '".$rqpdid."', '".$rqcrid."', ".$rqdate.", ".$rqtime.", '".$rqcmem."', '".$rqcncd."', ".$rqendt.", ".$rqentm.")";

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

?>