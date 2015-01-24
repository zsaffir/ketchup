<?php

//**********************************************************************************
//this cron job should be run every 5 minutes
//**********************************************************************************

$this_is_a_cron = 'Y';

include('zz_props.php');
include('zz_dbconn.php');

//**********************************************************************************
//get requests without confirmations that occur in the next 10 minutes

$arr_requests = array();

$unix_in_10 = date('Ymd|His', (mktime() + 600));
list($rqdate_in_10, $rqtime_in_10) = explode('|', $unix_in_10);

include('db_requests_selectUpcomingUnconfirmedRequests.php');

//**********************************************************************************

foreach($arr_requests as $rqrqid => $arr_details) {
	//$rqrqid = $arr_details['rqrqid'];
	$cicrnm = $arr_details['cicrnm'];
	$classmate_usname = $arr_details['classmate_usname'];
	$classmate_usemal = $arr_details['classmate_usemal'];
	$arr_student_usname = $arr_details['arr_student_usname'];
	
	
	//send email
	$to = $classmate_usemal;
	$from = '"KetchUp Notifications" <notifications@ketchuptechnology.com>';
	$subject = 'Reminder to record '.$cicrnm;
		
	$message = 'Hi '.$classmate_usname.',';
	$message .= '<br><br>';
	$message .= 'We just wanted to remind you that it\'s almost time to make a recording of '.$cicrnm.' for ';
	
	//put in student names (this could be improved for 3+ students)
	$student_name_text = '';	
	foreach($arr_student_usname as $student_usname) {
		$student_name_text .= ' and '.$student_usname;	
	}
	$student_name_text = substr($student_name_text, 5);
	$message .= $student_name_text;

	$message .= '.';
	$message .= '<br><br>';
	$message .= 'Thanks,';
	$message .= '<br><br>';
	$message .= 'The KetchUp Team';
	$headers = 'From: '.$from."\n";
	$headers .= 'MIME-Version: 1.0'."\n"; 
	$headers .= 'Content-type:text/html; charset=iso-8859-1'."\n";
	mail($to, $subject, $message, $headers);
	
	//update db
	$rqrqid = $rqrqid;
	$rqcfdt = date('Ymd');
	$rqcftm = date('His');
	include('db_requests_updateConfirmationDateTime.php');
}

//**********************************************************************************

include('zz_dbclose.php');

?>