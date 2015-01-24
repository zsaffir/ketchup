<?php

if(($baseURL == 'https://www.ketchuptechnology.com') || ((isset($this_is_a_cron)) && ($this_is_a_cron == 'Y'))) {
	$user = 'ketchup';
	$pw = 'hmlicmom1';
	$database = 'ketchup';
}
else {
	$user = 'root';
	$pw = 'root';
	$database = 'ketchup';
}

$dbconn = mysqli_connect('localhost', $user, $pw, $database);

//validate connection
if (mysqli_connect_errno($dbconn)) {
	echo "Database error: " . mysqli_connect_error();
	die();
}

?>