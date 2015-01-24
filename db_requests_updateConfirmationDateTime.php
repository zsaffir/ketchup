<?php

// update request as confirmed

$rqrqid = sql_sanitize($rqrqid);
$rqcfdt = sql_sanitize($rqcfdt, 8);
$rqcftm = sql_sanitize($rqcftm, 6);

$stmtRequests = "UPDATE requests
SET rqcfdt = ".$rqcfdt.", rqcftm = ".$rqcftm."
WHERE rqrqid = ".$rqrqid;

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

?>