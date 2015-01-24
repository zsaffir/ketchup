<?php

// update requests as viewed for user

$rqcmem = sql_sanitize($rqcmem);

$stmtRequests = "UPDATE requests
SET rqview = 'Y'
WHERE rqcmem = '".$rqcmem."' AND rqview = ' '";

$queryRequests = mysqli_query($dbconn, $stmtRequests) or log_sql_error($stmtRequests);

?>