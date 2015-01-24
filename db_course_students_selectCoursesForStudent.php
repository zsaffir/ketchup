<?php

// get courses for student

$arr_courses = array();

$csemal = sql_sanitize($csemal);

$stmtCourse_students = "SELECT cicrid, cicrnm, (ciabal - csabus)
FROM course_students
JOIN course_info ON (ciscid = csscid AND cipdid = cspdid AND cicrid = cscrid)
JOIN school_periods ON (spscid = csscid AND sppdid = cspdid AND spcurr = 'Y')
WHERE csemal = '".$csemal."'
ORDER BY cicrnm";

$queryCourse_students = mysqli_query($dbconn, $stmtCourse_students) or log_sql_error($stmtCourse_students);

while($db_row = $queryCourse_students->fetch_row()) {
	$cicrid = $db_row[0];
	$cicrnm = $db_row[1];
	$remaining_absences = $db_row[2];

	$arr_courses[] = array( 
		'course_id' => $cicrid,
		'course_name' => $cicrnm,
		'remaining_absences' => $remaining_absences
	);
}

?>