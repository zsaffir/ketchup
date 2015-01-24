<?php

// insert confirmations entry

$cfcfid = sql_sanitize($cfcfid, 5);
$cfactv = sql_sanitize($cfactv, 1);
$cfemal = sql_sanitize($cfemal, 50);
$cfusnid = sql_sanitize($cfusnid, 50);
$cfcncd = sql_sanitize($cfcncd, 10);
$cfvers = sql_sanitize($cfvers, 4);

$stmtConfirmations = "INSERT INTO confirmations
(cfcfid, cfactv, cfemal, cfusnid, cfcncd, cfvers)
VALUES (".$cfcfid.", '".$cfactv."', '".$cfemal."', '".$cfusnid."', '".$cfcncd."', '".$cfvers."')";

$queryConfirmations = mysqli_query($dbconn, $stmtConfirmations) or log_sql_error($stmtConfirmations);

?>