<?php

// update user password

$usemal = sql_sanitize($usemal);
$uspw = sql_sanitize($uspw, 50);

$stmtUsers = "UPDATE users
SET uspw = '".$uspw."', uspwrs = ' '
WHERE usemal = '".$usemal."'";

$queryUsers = mysqli_query($dbconn, $stmtUsers) or log_sql_error($stmtUsers);

?>