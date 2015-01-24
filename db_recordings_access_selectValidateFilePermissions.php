<?php

// validate student is allowed to listen to this recording

$valid_file = '';

$racmem = sql_sanitize($racmem);
$rafile = sql_sanitize($rafile);

$stmtRecordings_access = "SELECT rafile 
FROM recordings_access
WHERE racmem = '".$racmem."' AND rafile = '".$rafile."'";

$queryRecordings_access = mysqli_query($dbconn, $stmtRecordings_access) or log_sql_error($stmtRecordings_access);

while($db_row = $queryRecordings_access->fetch_row()) {
	$valid_file = 'Y';
}


?>