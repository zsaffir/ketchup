<?php

// get value for index

$ctvalu = '';

$ctindx = sql_sanitize($ctindx);

$stmtControl = "SELECT ctvalu
FROM control
WHERE ctindx = '".$ctindx."'";

$queryControl = mysqli_query($dbconn, $stmtControl) or log_sql_error($stmtControl);

while($db_row = $queryControl->fetch_row()) {
	$ctvalu = $db_row[0];
}

?>