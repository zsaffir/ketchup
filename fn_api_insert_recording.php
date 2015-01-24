<?php

include('zz_props.php');
include('zz_dbconn.php');

include('fn_api_functions.php');

//**********************************************************************************
//some http headers to prevent problems from app

header('Access-Control-Allow-Origin: *');

//**********************************************************************************

$arr_response = array();

//**********************************************************************************

$version = post('version', '1.0');

//**********************************************************************************
//validate

$got_error = '';
$error_msg = '';

if($got_error == '') {
	$api_key = post('api_key');
	$api_pw = post('api_pw');
	if(validate_api_key($api_key, $api_pw) != true) {
		$got_error = 'Y';
		$error_msg = 'Invalid KetchUp API Key';
	}
}

if($got_error == '') {
	$request_id = post('request_id');
	if($request_id == '') {
		$got_error = 'Y';
		$error_msg = 'Request ID is required';
	}
}

if($got_error == '') {
	//get request info
	$course_name = '';
	$classmate_usname = '';
	$classmate_usemal = '';
	$arr_students = array();

	$rqrqid = $request_id;
	$rqactv = ' ';
	include('db_requests_selectInfo.php');

	if(empty($arr_students)) {
		$got_error = 'Y';
		$error_msg = 'No request found for this recording';
	}
}

//**********************************************************************************
//take action

if($got_error == '') {
	$file_index = post('file_index', 0);
	$count_files = post('count_files', 1);
	$filename = post('filename');

	$recorder_is_also_recipient = false;

	//insert recording
	//later we will want to only insert one filename that is a composite of all files
	if($count_files == 1) {
		$rcfile = $filename;
		$rcstnm = $classmate_usname;
		$rcrqid = $request_id;
		$rcrcpt = 1;
		$rcrctt = 1;
	}
	else {
		$rcfile = $filename;
		$rcstnm = $classmate_usname;
		$rcrqid = $request_id;	
		$rcrcpt = $file_index;
		$rcrctt = $count_files;
	}
	include('db_recordings_insertRecording.php');

	//get canvas info
	//it was too complicated to do this in app
	if($version != '1.0') {
		$usscid = '';
		$uscanv = '';

		$usemal = $classmate_usemal;
		include('db_users_selectCanvasInfoForUser.php');

		$canvas_domain = $usscid;
		$canvas_access_token = $uscanv;

		//also get recorder's email address
		$headers = array(
			//'Authorization: Bearer '.$canvas_access_token,
		);

		$url = 'https://'.$canvas_domain.'/api/v1/users/'.$classmate_usemal.'/profile';
		$url .= '?access_token='.$canvas_access_token;  //because putting authorization in the header wasnt working for some reason.  ultimately should figure that out.

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		$response = curl_exec($ch);

		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$response_header = substr($response, 0, $header_size);
		$response_body = substr($response, $header_size);

		/*echo $url.'<br>';
		echo $response.'<br>';
		echo '<pre>';
		print_r(curl_getinfo($ch));
		echo '</pre>';
		die();*/

		$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($response_code == 200) {
			//keep going - should probably nest better here.
			$obj_response_body = json_decode($response_body);
			$primary_email = $obj_response_body->primary_email;

			$recorder_email = $primary_email;
		}
		elseif($response_code == 401) {
			//need to determine if you are unauthenticated or just plain unauthorized
			if(strpos(strtoupper($response_header), 'WWW-AUTHENTICATE') !== FALSE) {
				$arr_response['success'] = false;
				$arr_response['message'] = 'You must authenticate with Canvas to view this content.';
				$arr_response['reauthenticate'] = true;
			}
			else {
				$obj_response_body = json_decode($response_body);
				$arr_errors = $obj_response_body->errors;
				$obj_error_0 = $arr_errors[0];
				$canvas_error_message = $obj_error_0->message;
				$canvas_error_message = ucfirst($canvas_error_message);

				$arr_response['success'] = false;
				$arr_response['message'] = $canvas_error_message;
			}
		}
		else {
			$arr_response['success'] = false;
			$arr_response['message'] = 'Unknown error ('.$response_code.') - Please contact KetchUp support.';
		}
	}

	//give access
	foreach($arr_students as $arr_details) {
		$student_name = $arr_details['name'];
		$student_email = $arr_details['email'];

		$rafile = $filename;
		$racmem = $student_email;
		include('db_recordings_access_insertRecordingAccess.php');

		if($file_index == $count_files) {
			$message = 'Hi '.$student_name.',<br><br>Your recording of '.$course_name.' is now available on KetchUp!  Be sure to thank '.$classmate_usname.' for recording it for you.';
			$message .= '<br><br>';

			$message .= 'Thanks!';
			$message .= '<br><br>';
			$message .= 'The KetchUp Team<br>';
			$message .= 'Ben, Zeev, and Mark';

			if($version == '1.0') {
				//send email that recording is ready
				$to = $student_email;
				$from = '"KetchUp Notifications" <notifications@'.$_SERVER['HTTP_HOST'].'>';
				$subject = 'KetchUp Recording for '.$course_name.' is now available!';

				$headers = 'From: '.$from."\r\n";
			 	$headers .= 'MIME-Version: 1.0'."\r\n"; 
			 	$headers .= 'Content-type:text/html; charset=iso-8859-1'."\r\n";

				if(mail($to, $subject, $message, $headers)) { 

				}
				else {
					error_log('could not send the upload email 1 for request '.$request_id.' to '.$student_name.' ('.$student_email.')');
				}
			}
			else {
				if($classmate_usemal == $student_email) {
					//send email that recording is ready
					$to = $recorder_email;
					$from = '"KetchUp Notifications" <notifications@'.$_SERVER['HTTP_HOST'].'>';
					$subject = 'KetchUp Recording for '.$course_name.' is now available!';

					$headers = 'From: '.$from."\r\n";
				 	$headers .= 'MIME-Version: 1.0'."\r\n"; 
				 	$headers .= 'Content-type:text/html; charset=iso-8859-1'."\r\n";

					if(mail($to, $subject, $message, $headers)) { 

					}
					else {
						error_log('could not send the upload email 1 for request '.$request_id.' to '.$student_name.' ('.$student_email.')');
					}
				}
				else {
					//for canvas messages, cannot use html so modify formatting here
					$message = str_replace('<br>', "\n", $message);
					$message = strip_tags($message);

					//send api call to send message within canvas
					$headers = array(
						//'Authorization: Bearer '.$canvas_access_token,
					);

					$url = 'https://'.$canvas_domain.'/api/v1/conversations';
					$url .= '?access_token='.$canvas_access_token;  //because putting authorization in the header wasnt working for some reason.  ultimately should figure that out.

					$arr_post_fields = array(
						'recipients[]' => $student_id,
						'subject' => $student_subject,
						'body' => $student_message,
						'mode' => 'sync',
						'scope' => 'unread'
					);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_HTTPGET, false);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_post_fields);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_HEADER, true);
					$response = curl_exec($ch);

					$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
					$response_header = substr($response, 0, $header_size);
					$response_body = substr($response, $header_size);

					/*echo $url.'<br>';
					echo $response.'<br>';
					echo '<pre>';
					print_r(curl_getinfo($ch));
					echo '</pre>';
					die();*/

					$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					if($response_code == 200) {
						$arr_response['success'] = false;
						$arr_response['message'] = 'A request to record '.$course_name.' on '.date('l, M. jS', make_unix_date($date)).' was successfully sent to '.$classmate_name;
					}
					elseif($response_code == 401) {
						//need to determine if you are unauthenticated or just plain unauthorized
						if(strpos(strtoupper($response_header), 'WWW-AUTHENTICATE') !== FALSE) {
							$arr_response['success'] = false;
							$arr_response['message'] = 'You must authenticate with Canvas to view this content.';
							$arr_response['reauthenticate'] = true;
						}
						else {
							$obj_response_body = json_decode($response_body);
							$arr_errors = $obj_response_body->errors;
							$obj_error_0 = $arr_errors[0];
							$canvas_error_message = $obj_error_0->message;
							$canvas_error_message = ucfirst($canvas_error_message);

							$arr_response['success'] = false;
							$arr_response['message'] = $canvas_error_message;
						}
					}
					else {
						$arr_response['success'] = false;
						$arr_response['message'] = 'Unknown error ('.$response_code.') - Please contact KetchUp support.';
					}
				}
			}
		}
	}


	//if this is the last file in the upload, insert entries
	//note that this could be a composite file
	if($file_index == $count_files) {
	 	//send thank you email
		$message = 'Hi '.$classmate_usname.',<br><br>Thanks for recording '.$course_name.'!  Because of you, ';
		
		$students_text = '';
		if(count($arr_students) <= 2) {
			foreach($arr_students as $arr_details) {
				$student_name = $arr_details['name'];
				$student_email = $arr_details['email'];

				$students_text .= ' and '.$student_name;
			}
			$students_text = substr($students_text, 5);
			$message .= $students_text;
		}
		else {
			foreach($arr_students as $students_index => $arr_details) {
				$student_name = $arr_details['name'];
				$student_email = $arr_details['email'];

				if($students_index == (count($arr_students) - 1)) {
					$students_text .= 'and ';
				}
				$students_text .= $student_name.', ';
			}
			$students_text = substr($students_text, 0, -2);
			$message .= $students_text;
		}

		$message .= ' already ';

		if(count($arr_students) > 1) {
			$message .= 'have';
		}
		else {
			$message .= 'has';	
		}

		$message .= ' access to this lecture.';
		$message .= '<br><br>';

		$message .= 'Thanks!';
		$message .= '<br><br>';
		$message .= 'The KetchUp Team<br>';
		$message .= 'Ben, Zeev, and Mark';

		if($version == '1.0') {
			$to = $classmate_usemal;
			$from = '"KetchUp Notifications" <notifications@'.$_SERVER['HTTP_HOST'].'>';
			$subject = 'KetchUp Recording for '.$course_name.' is now available!';

			$headers = 'From: '.$from."\r\n";
		 	$headers .= 'MIME-Version: 1.0'."\r\n"; 
		 	$headers .= 'Content-type:text/html; charset=iso-8859-1'."\r\n";

			if(mail($to, $subject, $message, $headers)) { 

			}
			else {
				error_log('could not send the upload email 2 for request '.$request_id);
			}
		}
		else {
			$to = $recorder_email;
			$from = '"KetchUp Notifications" <notifications@'.$_SERVER['HTTP_HOST'].'>';
			$subject = 'KetchUp Recording for '.$course_name.' is now available!';

			$headers = 'From: '.$from."\r\n";
		 	$headers .= 'MIME-Version: 1.0'."\r\n"; 
		 	$headers .= 'Content-type:text/html; charset=iso-8859-1'."\r\n";

			if(mail($to, $subject, $message, $headers)) { 

			}
			else {
				error_log('could not send the upload email 2 for request '.$request_id);
			}
		}

		//update original request as complete
		$rqrqid = $request_id;
		$rqactv = 'D';
		include('db_requests_updateRequestActive.php');
	}

	$arr_response['success'] = true;
}
else {
	error_log($error_msg);

	foreach($_POST as $index => $value) {
		error_log($index.': '.$value);
	}

	$arr_response['success'] = false;
	$arr_response['message'] = $error_msg;
}

//**********************************************************************************

echo json_encode($arr_response);

//**********************************************************************************

include('zz_dbclose.php');

?>