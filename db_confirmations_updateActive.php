<?php

// update confirmation active

$cfcfid = sql_sanitize($cfcfid);
$cfactv = sql_sanitize($cfactv, 1);

$stmtConfirmations = "UPDATE confirmations
SET cfactv = '".$cfactv."'
WHERE cfcfid = ".$cfcfid;

$queryConfirmations = mysqli_query($dbconn, $stmtConfirmations) or log_sql_error($stmtConfirmations);

?>