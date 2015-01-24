<?php

// update recordings as viewed for user

$racmem = sql_sanitize($racmem);

$stmtRecordings_access = "UPDATE recordings_access
SET raview = 'Y'
WHERE racmem = '".$racmem."' AND raview = ' '";

$queryRecordings_access = mysqli_query($dbconn, $stmtRecordings_access) or log_sql_error($stmtRecordings_access);

?>