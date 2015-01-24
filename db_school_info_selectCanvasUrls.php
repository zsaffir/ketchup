<?php

// get canvas urls

$arr_canvas_urls = array();

$stmtSchool_info = "SELECT siscnm, sicanv
FROM school_info
ORDER BY siscnm";

$querySchool_info = mysqli_query($dbconn, $stmtSchool_info) or log_sql_error($stmtSchool_info);

while($db_row = $querySchool_info->fetch_row()) {
	$siscnm = $db_row[0];
	$sicanv = $db_row[1];

	$arr_canvas_urls[] = array( 
		'school_name' => $siscnm,
		'canvas_url' => $sicanv
	);
}

?>