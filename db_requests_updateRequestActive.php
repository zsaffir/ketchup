<?php

// update request as complete

$rqactv = sql_sanitize($rqactv, 1);

$rqrqid = sql_sanitize($rqrqid);

$stmtRequests = "UPDATE requests
SET rqactv = '".$rqactv."'
WHERE rqrqid = ".$rqrqid." AND rqactv = ' '";

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

?>