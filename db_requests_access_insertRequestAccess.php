<?php

// insert request access

$rbendt = date('Ymd');
$rbentm = date('His');

$rbrqid = sql_sanitize($rbrqid, 5);
$rbstem = sql_sanitize($rbstem, 50);
$rbsrc = sql_sanitize($rbsrc, 7);
$rbendt = sql_sanitize($rbendt, 8);
$rbentm = sql_sanitize($rbentm, 6);

$stmtRequests_access = "INSERT INTO requests_access
(rbrqid, rbstem, rbsrc, rbendt, rbentm)
VALUES (".$rbrqid.", '".$rbstem."', '".$rbsrc."', ".$rbendt.", ".$rbentm.")";

$queryRequests_access = mysqli_query($dbconn, $stmtRequests_access) or log_sql_error($stmtRequests_access);

?>