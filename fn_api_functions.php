<?php

function api_validate_credentials($api_key, $api_pw, $user_id, $password, $version) {
	global $baseURL, $dbconn;

	$arr_response = array();

	//**********
	//validate api key
	//**********

	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	//**********
	//validate credentials
	//**********

	if($version == '1.0') {
		$usnid = '';
		$usname = '';
		$usemal = '';
		$uspwrs = '';
	
		$usemal = strtolower($user_id.'@kellogg.northwestern.edu');
		$uspw = $password;
		include('db_users_selectInfoForEmailPassword.php');
	
		if($usname == '') {
			$got_error = 'Y';
			$error_msg = 'Invalid username or password.';
		}
	}
	elseif($version == '1.1') {
		//fields we need for response
		$usnid = strtolower($user_id);
		$uspw = $password;

		$http_code = get_http_code_kellogg_credentials($usnid, $uspw);
		if($http_code != 200) {
			//check if this is a user id for another school
			
			$usnid = '';
			$usname = '';
			$usemal = '';
			$uspwrs = '';
			
			$usnid = strtolower($user_id);
			$usnpw = $password;
			include('db_users_selectInfoForEmailPasswordNonKellogg.php');
						
			if($usemal == '') {
				$got_error = 'Y';
				$error_msg = 'Invalid username or password. ('.$http_code.')';
			}
		}
		else {
			//get user info for this nid
			$usname = '';
			$usemal = '';
			$uspwrs = '';
			
			$usnid = strtolower($user_id);
			include('db_users_selectInfoForNid.php');
			
			if($usemal != '') {
				//store npw (and keep up to date)
				$usnid = strtolower($user_id);
				$usnpw = $password;
				include('db_users_updateNewPassword.php');
			}
		}
	}
	elseif($version == '1.2') {
		$user_email = $user_id;

		$valid_user = '';

		$usemal = $user_email;
		$uspw = $password;
		include('db_users_selectValidateUserPassword.php');

		//validate that this wasnt a kellogg net id and password combo
		if($valid_user == '') {
			$user_id = $user_email;
			$http_code = get_http_code_kellogg_credentials($user_id, $password);

			if($http_code == 200) {
				//only allow this if we know what user goes with the net id

				//get user info for this nid
				$usname = '';
				$usemal = '';
				$uspwrs = '';
				
				$usnid = strtolower($user_id);
				include('db_users_selectInfoForNid.php');
				
				if($usemal != '') {
					$valid_user = 'Y';
					
					if($usemal != '') {
						//store npw (and keep up to date)
						$usnid = strtolower($user_id);
						$usnpw = $password;
						include('db_users_updateNewPassword.php');
					}
	
					$user_email = $usemal;
				}
			}
		}

		if($valid_user == '') {
			$got_error = 'Y';
			$error_msg = 'Invalid username or password.';
		}
		else {
			$usname = '';
			$uspwrs = '';

			$usemal = $user_email;
			include('db_users_selectInfoForEmail.php');
		}
	}

	if($got_error == '') {
		$arr_response['success'] = true;
		if($version != '1.2') {
			$arr_response['user_id'] = $usemal;
			$arr_response['user_nid'] = $usnid;
			$arr_response['user_pw'] = $uspw;
		}
		$arr_response['user_name'] = $usname;
		$arr_response['user_email'] = $usemal;
		$arr_response['password_reset'] = $uspwrs;
		
		//store first logon date
		$usfldt = 0;
		
		$usemal = $usemal;
		include('db_users_selectFirstLogonDate.php');
	
		if($usfldt == 0) {
			$usfldt = date('Ymd');
			$usemal = $usemal;
			include('db_users_updateFirstLogonDate.php');
		}	
	}
	else {
		//log in failure table

		$max_flflid = 0;
		include('db_failures_selectMaxFlflid.php');

		$flflid = $max_flflid + 1;
		$flnid = $user_id;
		$flnpw = $password;
		include('db_failures_insertEntry.php');

		$arr_response['success'] = false;
		$arr_response['message'] = $error_msg;
	}

	return $arr_response;
}

function api_send_email_confirmation($api_key, $api_pw, $user_nid, $email, $version) {
	global $baseURL, $dbconn;

	$arr_response = array();

	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	if($got_error == '') {
		$user_nid = strtolower($user_nid);
		$email = strtolower($email);

		//require user id (prior to 1.2)
		if($got_error == '') {
			if($version != '1.2') {
				if($user_nid == '') {
					$got_error = 'Y';
					$error_msg = 'No user id received';
				}
			}
		}

		//require email
		if($got_error == '') {
			if($email == '') {
				$got_error = 'Y';
				$error_msg = 'No email received';
			}
		}

		//only duplicate accounts
		if($got_error == '') {
			if($version == '1.2') {
				$valid_user = '';

				$usemal = $email;
				include('db_users_selectValidateUser_v1.2.php');

				if($valid_user != 'Y') {
					$got_error = 'Y';
					$error_msg = 'Invalid user account';
				}
			}
		}

		//limit to specific domains
		if($got_error == '') {
			if($version == '1.2') {

			}
		}


		if($got_error == '') {
			//**********
			//randomly generate confirmation code
			//**********

			$confirmation_code = '';

			$arr_valid_characters = str_split('bcdfghjklmnpqrstvwxz'
				.'123456789'); // and any other characters
			$password_length = 10;
			
			foreach (array_rand($arr_valid_characters, $password_length) as $k) {
				$confirmation_code .= $arr_valid_characters[$k];
			}

			//**********
			//database
			//**********
			//get highest confirmation

			$max_cfcfid = 0;
			include('db_confirmations_selectMaxCfcfid.php');


			//insert entry
			$cfcfid = $max_cfcfid + 1;
			$cfactv = '';
			$cfemal = $email;
			$cfusnid = $user_nid;
			$cfcncd = $confirmation_code;
			$cfvers = $version;
			include('db_confirmations_insertEntry.php');

			//**********
			//build link
			//**********
			if($version == '1.2') {
				$activation_link = $baseURL.'/education/confirm-email/'.$cfcfid.'/'.$confirmation_code.'/confirm.html';
			}
			else {
				$activation_link = $baseURL.'/education/confirm-email/'.$cfcfid.'/'.$confirmation_code.'/'.$user_nid.'.html';
			}

			//**********
			//send email
			//**********
			$to = $email;
			$from = '"KetchUp Notifications" <notifications@ketchuptechnology.com>';
			$subject = 'Activate Your KetchUp Account';
			$message = 'Hello,';
			$message .= '<br><br>';
			$message .= 'Your KetchUp account is ready for activation. If you did not make this request, just ignore this email. Otherwise, please visit the link below to activate your account.';
			$message .= '<br><br>';
			$message .= '<a href="'.$activation_link.'">'.$activation_link.'</a>';
			$message .= '<br><br>';
			$message .= 'Thanks,';
			$message .= '<br><br>';
			$message .= 'The KetchUp Team';
			
			$headers = 'From: '.$from."\n";
			$headers .= 'MIME-Version: 1.0'."\n"; 
			$headers .= 'Content-type:text/html; charset=iso-8859-1'."\n";
			if(mail($to, $subject, $message, $headers)) {
			
			}
			else {
				$got_error = 'Y';
				$error_msg = 'Error sending confirmation email';
			}
		}

		if($got_error == '') {
			$arr_response['success'] = true;
		}
		else {
			$arr_response['success'] = false;
			$arr_response['message'] = $error_msg;
		}

		return $arr_response;
	}
}

function api_get_unviewed_counts($api_key, $api_pw, $version, $email, $reset_requests, $reset_recordings, $user_id, $canvas_access_token, $canvas_domain) {
	global $baseURL, $dbconn;

	$arr_response = array();

	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	if($got_error == '') {
		if($version == '1.0') {
			if($email == '') {
				$got_error = 'Y';
				$error_msg = 'Email is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($user_id == '') {
				$got_error = 'Y';
				$error_msg = 'User id is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_access_token == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas token is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_domain == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas domain is required';
			}
		}
	}

	if($got_error == '') {
		if($version == '1.0') {
			//reset requests (user is on the record page)
			if($reset_requests == 'Y') {
				$rqcmem = $email;
				include('db_requests_updateAsViewed.php');
			}
		
			//reset_recordings (user is on the listen page)
			if($reset_recordings == 'Y') {
				$racmem = $email;
				include('db_recordings_access_updateAsViewed.php');
			}

			//get recordings from db
			$arr_unviewed_counts = array();
			
			$racmem = $email;
			$rqcmem = $email;
			include('db_requestsRecordings_selectUnviewedCounts.php');

			$arr_response['success'] = true;
			$arr_response['unviewed_counts'] = $arr_unviewed_counts;
		}
		else {
			//reset requests (user is on the record page)
			if($reset_requests == 'Y') {
				$rqcmem = $user_id;
				include('db_requests_updateAsViewed.php');
			}
		
			//reset_recordings (user is on the listen page)
			if($reset_recordings == 'Y') {
				$racmem = $user_id;
				include('db_recordings_access_updateAsViewed.php');
			}

			//get recordings from db
			$arr_unviewed_counts = array();
			
			$racmem = $user_id;
			$rqcmem = $user_id;
			include('db_requestsRecordings_selectUnviewedCounts.php');

			$arr_response['success'] = true;
			$arr_response['unviewed_counts'] = $arr_unviewed_counts;
		}
	}
	else {
		$arr_response['success'] = false;
		$arr_response['message'] = $error_msg;
	}

	return $arr_response;
}

function api_get_courses_for_student($api_key, $api_pw, $version, $email, $canvas_access_token, $canvas_domain) {
	global $baseURL, $dbconn;

	$arr_response = array();

	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	if($got_error == '') {
		if($version == '1.0') {
			if($email == '') {
				$got_error = 'Y';
				$error_msg = 'Email is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_access_token == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas token is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_domain == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas domain is required';
			}
		}
	}

	if($got_error == '') {
		//get courses
		if($version == '1.0') {
			$arr_courses = array();

			$csemal = $email;
			include('db_course_students_selectCoursesForStudent.php');

			$arr_response['success'] = true;
			$arr_response['courses'] = $arr_courses;
		}
		else {
			//send api call to get courses
			$headers = array(
				//'Authorization: Bearer '.$canvas_access_token,
			);
			$url = 'https://'.$canvas_domain.'/api/v1/courses/';
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

			$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($response_code == 200) {
				$arr_canvas_courses = json_decode($response_body, true);

				$arr_ketchup_courses = array();

				foreach($arr_canvas_courses as $arr_canvas_course) {
					$course_id = $arr_canvas_course['id'];
					$course_name = $arr_canvas_course['name'];
					$remaining_absences = 2; //temp

					$arr_ketchup_courses[] = array(
						'course_id' => $course_id,
						'course_name' => $course_name,
						'remaining_absences' => $remaining_absences
					);
				}

				$arr_response['success'] = true;
				$arr_response['arr_courses'] = $arr_ketchup_courses;
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
	else {
		$arr_response['success'] = false;
		$arr_response['message'] = $error_msg;
	}

	return $arr_response;
}

function api_get_students_for_course($api_key, $api_pw, $version, $course_id, $student_email, $canvas_access_token, $canvas_domain) {
	global $baseURL, $dbconn;

	$arr_response = array();

	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	if($got_error == '') {
		if($course_id == '') {
			$got_error = 'Y';
			$error_msg = 'Course is required';
		}
	}

	if($got_error == '') {
		if($version == '1.0') {
			if($student_email == '') {
				$got_error = 'Y';
				$error_msg = 'Student is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_access_token == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas token is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_domain == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas domain is required';
			}
		}
	}

	if($got_error == '') {
		//get courses
		if($version == '1.0') {
			$arr_classmates = array();

			$cscrid = $course_id;
			include('db_course_students_selectClassmatesForCourse.php');

			$arr_response['success'] = true;
			$arr_response['students'] = $arr_classmates;
		}
		else {
			//send api call to get courses
			$headers = array(
				//'Authorization: Bearer '.$canvas_access_token,
			);
			
			$url = 'https://'.$canvas_domain.'/api/v1/courses/'.$course_id.'/users/?enrollment_type=student';
			$url .= '&access_token='.$canvas_access_token;  //because putting authorization in the header wasnt working for some reason.  ultimately should figure that out.
			
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
				$arr_canvas_classmates = json_decode($response_body, true);

				$arr_classmates = array();

				foreach($arr_canvas_classmates as $arr_canvas_classmate) {
					$classmate_id = $arr_canvas_classmate['id'];
					$classmate_name = $arr_canvas_classmate['name'];

					$arr_classmates[] = array(
						'id' => $classmate_id,
						'name' => $classmate_name
					);
				}

				$arr_response['success'] = true;
				$arr_response['classmates'] = $arr_classmates;
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
	else {
		$arr_response['success'] = false;
		$arr_response['message'] = $error_msg;
	}

	return $arr_response;
}

function api_get_canvas_urls($api_key, $api_pw, $version) {
	global $baseURL, $dbconn;

	$arr_response = array();

	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	if($got_error == '') {
		//get courses
		$arr_canvas_urls = array();

		include('db_school_info_selectCanvasUrls.php');

		$arr_response['success'] = true;
		$arr_response['canvas_urls'] = $arr_canvas_urls;
	}
	else {
		$arr_response['success'] = false;
		$arr_response['message'] = $error_msg;
	}

	return $arr_response;
}

function api_get_dates_for_course($api_key, $api_pw, $version, $course_id, $canvas_access_token, $canvas_domain) {
	global $baseURL, $dbconn;

	$arr_response = array();

	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	if($got_error == '') {
		if($course_id == '') {
			$got_error = 'Y';
			$error_msg = 'Course is required';
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_access_token == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas token is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_domain == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas domain is required';
			}
		}
	}

	if($got_error == '') {
		//get courses
		if($version == '1.0') {
			$arr_dates = array();

			$cdcrid = $course_id;
			include('db_course_dates_selectCurrentDatesForCourse.php');

			$arr_response['success'] = true;
			$arr_response['dates'] = $arr_dates;
				
			//check if we should allow manual date select
			//we will allow this if there are no dates at all in the system for this course
			if(empty($arr_dates)) {
				$arr_dates = array();

				$cdcrid = $course_id;
				include('db_course_dates_selectAllDatesForCourse.php');

				if(empty($arr_dates)) {
					$arr_response['allow_manual_date_select'] = true;
				}
			}
		}
		else {
			//send api call to get dates
			$headers = array(
				//'Authorization: Bearer '.$canvas_access_token,
			);

			$url = 'https://'.$canvas_domain.'/api/v1/calendar_events?type=event&all_events=true&context_codes[]=course_'.$course_id;
			$url .= '&access_token='.$canvas_access_token;  //because putting authorization in the header wasnt working for some reason.  ultimately should figure that out.

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

			$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($response_code == 200) {
				$arr_canvas_course_dates = json_decode($response_body, true);

				$arr_course_dates = array();

				foreach($arr_canvas_course_dates as $arr_canvas_course_date) {
					$course_date_canvas = $arr_canvas_course_date['start_at'];
					$course_date_unix = strtotime($course_date_canvas);
					$course_date_ccyymmdd = date('Ymd', $course_date_unix);
					$course_date_hhmmss = date('His', $course_date_unix);

					//only include future events
					$today_ccyymmdd = date('Ymd');
					if($today_ccyymmdd <= $course_date_ccyymmdd) {
						$arr_course_dates[] = array(
							'ccyymmdd' => $course_date_ccyymmdd,
							'hhmmss' => $course_date_hhmmss,
							'display_date' => date('l, M. jS', $course_date_unix),
						);
					}
				}

				$arr_response['success'] = true;
				$arr_response['dates'] = $arr_course_dates;

				//we will now allow manual date select even if dates are present
				$arr_response['allow_manual_date_select'] = true;
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
	else {
		$arr_response['success'] = false;
		$arr_response['message'] = $error_msg;
	}

	return $arr_response;
}

function api_send_record_request($api_key, $api_pw, $version, $student_id, $student_name, $student_email, $school_id, $course_id, $course_name, $date, $time, $classmate_id, $classmate_email, $classmate_name, $source, $canvas_access_token, $canvas_domain) {
	global $baseURL, $programDirectory, $dbconn;

	$arr_response = array();

	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($student_id == '') {
				$got_error = 'Y';
				$error_msg = 'Student id is required';
			}
		}
	}

	if($got_error == '') {
		if($version == '1.0') {
			if($student_email == '') {
				$got_error = 'Y';
				$error_msg = 'Student email is required';
			}
		}
	}

	if($got_error == '') {
		if($student_name == '') {
			$got_error = 'Y';
			$error_msg = 'Student name is required';
		}
	}

	if($got_error == '') {
		if($version == '1.0') {
			if($school_id == '') {
				$got_error = 'Y';
				$error_msg = 'School id is required';
			}
		}
	}

	if($got_error == '') {
		if($course_id == '') {
			$got_error = 'Y';
			$error_msg = 'Course is required';
		}
	}

	if($got_error == '') {
		if($course_name == '') {
			$got_error = 'Y';
			$error_msg = 'Course name is required';
		}
	}

	if($got_error == '') {
		if($date == '') {
			$got_error = 'Y';
			$error_msg = 'Date is required';
		}
	}

	/*if($got_error == '') {
		if($time == '') {
			$got_error = 'Y';
			$error_msg = 'Time is required';
		}
	}*/

	if($got_error == '') {
		if($version != '1.0') {
			if($classmate_id == '') {
				$got_error = 'Y';
				$error_msg = 'Classmate id is required';
			}
		}
	}

	if($got_error == '') {
		if($version == '1.0') {
			if($classmate_email == '') {
				$got_error = 'Y';
				$error_msg = 'Classmate email is required';
			}
		}
	}

	if($got_error == '') {
		if($classmate_name == '') {
			$got_error = 'Y';
			$error_msg = 'Classmate name is required';
		}
	}

	if($got_error == '') {
		if($source == '') {
			$got_error = 'Y';
			$error_msg = 'Source is required';
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_access_token == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas token is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_domain == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas domain is required';
			}
		}
	}

	//at this point, check for existing similar request
	if($got_error == '') {
		//get current period for this school
		$sppdid = '';

		$spscid = $school_id;
		include('db_school_periods_selectCurrentPeriodForSchool.php');

		//check for similar
		$similar_recording_id = '';

		$rqrqid = '';

		$rqscid = $school_id;
		$rqpdid = $sppdid;
		$rqcrid = $course_id;
		if($version == '1.0') {
			$rqcmem = $classmate_email;
		}
		else {
			$rqcmem = $classmate_id;	
		}
		$rqdate = $date;
		$rqtime = $time;
		include('db_requests_selectCheckForExisting.php');

		//if we got one, make sure user hasnt already requested for this
		if($rqrqid != '') {
			$similar_recording_id = $rqrqid;

			$valid_request_access = '';

			$rbrqid = $rqrqid;
			$rbstem = $student_email;
			include('db_requests_access_selectValidateRequestAccess.php');

			if($valid_request_access != '') {
				$got_error = 'Y';
				$error_msg = 'You may not send more than one request to that user for this class.';
			}
		}
	}

	//send request
	if($got_error == '') {
		if($time == '') {
			$time = 0;
		}
	
		if($version == '1.0') {
			if($similar_recording_id == '') {
				//get max rqrqid
				$max_rqrqid = 0;
				include('db_requests_selectMaxRqrqid.php');

				/*//get current period for this school (this is up above now)
				$sppdid = '';
				$spscid = $school_id;
				include('db_school_periods_selectCurrentPeriodForSchool.php');*/

				//**********
				//randomly generate confirmation code (necessary for verifying deletion)
				//**********

				$confirmation_code = '';

				$arr_valid_characters = str_split('bcdfghjklmnpqrstvwxz'
					.'123456789'); // and any other characters
				$password_length = 10;
				
				foreach (array_rand($arr_valid_characters, $password_length) as $k) {
					$confirmation_code .= $arr_valid_characters[$k];
				}

				//insert request
				$rqrqid = ($max_rqrqid + 1);
				$rqscid = $school_id;
				$rqpdid = $sppdid;
				$rqcrid = $course_id;
				$rqdate = $date;
				$rqtime = $time;
				$rqcmem = $classmate_email;
				$rqcncd = $confirmation_code;
				include('db_requests_insertRequest.php');

				//insert request access
				$rbrqid = ($max_rqrqid + 1);
				$rbstem = $student_email;
				$rbsrc = $source;
				include('db_requests_access_insertRequestAccess.php');
			}
			else {
				//insert request access
				$rbrqid = $similar_recording_id;
				$rbstem = $student_email;
				$rbsrc = $source;
				include('db_requests_access_insertRequestAccess.php');

				//get confirmation code
				$rqcncd = '';

				$rqrqid = $similar_recording_id;
				include('db_requests_selectConfirmationCode.php');
			}

			//**********
			//send email
			//**********
			//to classmate (who will record)

			$to = $classmate_email;
			$from_email = 'notifications@'.$_SERVER['HTTP_HOST'];
			$from = '"KetchUp Notifications" <'.$from_email.'>';
			$subject = 'KetchUp Record Request from '.$student_name;
			$message = 'Hi '.$classmate_name.',';
			$message .= '<br><br>';
			$message .= $student_name.' will be missing '.$course_name.' on '.date('l, M. jS', make_unix_date($date)).' and has asked you to record the audio of the class using the KetchUp Class Recorder app. By using KetchUp to record and upload the audio, you will make it easy for '.$student_name.' to listen to the lecture and stay caught up in class!';
			$message .= '<br><br>';
			
			$message .= '<span style="font-weight:bold;">How to record</span><br>';
			$message .= 'Step 1 - On your iPhone or iPad, <a href="https://itunes.apple.com/us/app/ketchup-class-recorder/id812449953">download the KetchUp app here.</a> (Android coming soon)<br>';
			$message .= 'Step 2 - On the day of class, Login to KetchUp with your username and password.<br>';
			$message .= 'Step 3 - Select the request from '.$student_name.' and begin recording the lecture. When done, select upload and you\'re done! <br>';
			$message .= '<br>';

			$message .= '<span style="font-weight:bold;">Remember</span>: Always be sure the professor has granted permission to record the lecture. For best recording quality, sit near the front of the class. For a 3 hour lecture, you can expect the recording to use about 25% battery life.';
			$message .= '<br><br>';
			$message .= 'For more information on KetchUp, please visit <a href="'.$baseURL.'/'.$programDirectory.'/">'.$baseURL.'/'.$programDirectory.'/</a>.';
			$message .= '<br><br>';
			
			$message .= 'Thanks!';
			$message .= '<br><br>';
			$message .= 'The KetchUp Team<br>';
			$message .= 'Ben, Zeev, and Mark';

			$message .= '<br><br>';
			$message .= '<span style="font-style:italic;">KetchUp is sponsored by the Kellogg Education Technology Incubator (KETI).</span>';

			$message .= '<br><br><br>';
			$message .= '--------------------------------------------------------------------------------';
			$message .= '<br>';
			$message .= 'If you cannot fulfill this request, please click <a href="'.$baseURL.'/'.$programDirectory.'/delete_request/'.$rqrqid.'/'.$rqcncd.'.html">here</a> to delete the request.  Both you and the requester will receive a confirmation';
			
			//based on http://www.shotdev.com/php/php-mail/php-send-email-upload-form-attachment-file/
			$boundary = md5(uniqid(time()));
			
			//actual headers
			$headers = 'From: '.$from."\n";
			$headers .= 'MIME-Version: 1.0'."\n"; 
			$headers .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'."\n\n";
			$headers .= 'This is a multi-part message in MIME format.'."\n";
			
			//message
			$headers .= '--'.$boundary."\n";
			$headers .= 'Content-type: text/html; charset=utf-8'."\n";
			$headers .= 'Content-Transfer-Encoding: 7bit'."\n\n";
			$headers .= $message."\n\n";

			//attachment
			/*$headers .= '--'.$boundary."\n";
			$headers .= 'Content-Type: application/octet-stream; name="ketchup.ics"'."\n";
			$headers .= 'Content-Transfer-Encoding: base64'."\n";
			$headers .= 'Content-Disposition: attachment; filename="ketchup.ics"'."\n\n";
			$headers .= chunk_split(base64_encode($invitation))."\n\n";*/
			
			if(mail($to, $subject, null, $headers)) { 

			}
			else {
				error_log('could not send the request email 1 - id '.$rqrqid);
			}


			//**********
			//send email
			//**********
			//to student (who will be absent)
			
			$to = $student_email;
			$from_email = 'notifications@'.$_SERVER['HTTP_HOST'];
			$from = '"KetchUp Notifications" <'.$from_email.'>';
			$subject = 'Your KetchUp Record Request';
			$message = 'Hi '.$student_name.',';
			$message .= '<br><br>';
			$message .= 'Your request to record '.$course_name.' has been sent to '.$classmate_name.'.  Thank you for using KetchUp!';
			$message .= '<br><br>';
			$message .= 'We want to remind you that as usual, all recordings must comply with your school\'s honor code.  If you have not done so already, make sure you ask your professor for permission to record this lecture.';
			$message .= '<br><br>';
			
			$message .= 'Thanks!';
			$message .= '<br><br>';
			$message .= 'The KetchUp Team<br>';
			$message .= 'Ben, Zeev, and Mark';

			$message .= '<br><br>';
			$message .= '<span style="font-style:italic;">KetchUp is sponsored by the Kellogg Education Technology Incubator (KETI).</span>';

			//actual headers
			$headers = 'From: '.$from."\n";
			$headers .= 'MIME-Version: 1.0'."\n"; 
			$headers .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'."\n\n";
			$headers .= 'This is a multi-part message in MIME format.'."\n";
			
			//message
			$headers .= '--'.$boundary."\n";
			$headers .= 'Content-type: text/html; charset=utf-8'."\n";
			$headers .= 'Content-Transfer-Encoding: 7bit'."\n\n";
			$headers .= $message."\n\n";
			
			if(mail($to, $subject, null, $headers)) { 

			}
			else {
				error_log('could not send the request email 2 - id '.$rqrqid);
			}

			$arr_response['success'] = true;
			$arr_response['message'] = 'A request to record '.$course_name.' on '.date('l, M. jS', make_unix_date($date)).' was successfully sent to '.$classmate_name;
		}
		else {
			//version 2.0

			//**********
			//user
			//**********
			//check if we have a record for this student
			//(for now we are storing user id in usemal)
			$valid_user = '';

			$usemal = $student_id;
			include('db_users_selectValidateUser.php');

			//insert user if we have to
			if($valid_user == '') {
				$usemal = $student_id;
				include('db_users_insertUser2.php');
			}
			
			//update user
			$usname = $student_name;
			$usscid = $canvas_domain;
			$uscanv = $canvas_access_token;

			$usemal = $student_id;
			include('db_users_updateCanvasUser.php');

			//**********
			//class
			//**********
			//check if we have a record for this course
			$valid_course = '';

			$ciscid = $canvas_domain;
			$cipdid = '';
			$cicrid = $course_id;
			include('db_course_info_selectValidateCourse.php');

			//insert course if we have to
			if($valid_course == '') {
				include('db_course_info_insertCourse2.php');
			}

			//update course
			$cicrnm = $course_name;
			$ciprem = '';
			$ciabal = 0;
			include('db_course_info_updateCourse.php');

			if($similar_recording_id == '') {
				//**********
				//randomly generate confirmation code (necessary for verifying deletion)
				//**********

				$confirmation_code = '';

				$arr_valid_characters = str_split('bcdfghjklmnpqrstvwxz'
					.'123456789'); // and any other characters
				$password_length = 10;
				
				foreach (array_rand($arr_valid_characters, $password_length) as $k) {
					$confirmation_code .= $arr_valid_characters[$k];
				}

				//**********
				//request
				//**********
				//get max rqrqid
				$max_rqrqid = 0;
				include('db_requests_selectMaxRqrqid.php');

				//insert request
				$rqrqid = ($max_rqrqid + 1);
				$rqscid = $canvas_domain;
				$rqpdid = '';
				$rqcrid = $course_id;
				$rqdate = $date;
				$rqtime = $time;
				$rqcmem = $classmate_id;
				$rqcncd = $confirmation_code;
				include('db_requests_insertRequest.php');

				//insert request access
				$rbrqid = ($max_rqrqid + 1);
				$rbstem = $student_id;
				$rbsrc = $source;
				include('db_requests_access_insertRequestAccess.php');
			}
			else {
				//insert request access
				$rbrqid = $similar_recording_id;
				$rbstem = $student_email;
				$rbsrc = $source;
				include('db_requests_access_insertRequestAccess.php');

				//get confirmation code
				$rqcncd = '';

				$rqrqid = $similar_recording_id;
				include('db_requests_selectConfirmationCode.php');
			}

			//**********
			//message to classmate (who will record)
			//**********

			$classmate_subject = 'KetchUp Record Request from '.$student_name;
			$classmate_message = 'Hi '.$classmate_name.',';
			$classmate_message .= '<br><br>';
			$classmate_message .= $student_name.' will be missing '.$course_name.' on '.date('l, M. jS', make_unix_date($date)).' and has asked you to record the audio of the class using the KetchUp Class Recorder app. By using KetchUp to record and upload the audio, you will make it easy for '.$student_name.' to listen to the lecture and stay caught up in class!';
			$classmate_message .= '<br><br>';
			
			$classmate_message .= 'How to record<br>';
			$classmate_message .= 'Step 1 - On your iPhone or iPad, <a href="https://itunes.apple.com/us/app/ketchup-class-recorder/id812449953">download the KetchUp app here.</a> (Android coming soon)<br>';
			$classmate_message .= 'Step 2 - On the day of class, Login to KetchUp with your username and password.<br>';
			$classmate_message .= 'Step 3 - Select the request from '.$student_name.' and begin recording the lecture. When done, select upload and you\'re done! <br>';
			$classmate_message .= '<br>';

			$classmate_message .= '<span style="font-weight:bold;">Remember</span>: Always be sure the professor has granted permission to record the lecture. For best recording quality, sit near the front of the class. For a 3 hour lecture, you can expect the recording to use about 25% battery life.';
			$classmate_message .= '<br><br>';
			$classmate_message .= 'For more information on KetchUp, please visit <a href="'.$baseURL.'/'.$programDirectory.'/">'.$baseURL.'/'.$programDirectory.'/</a>.';
			$classmate_message .= '<br><br>';
			
			$classmate_message .= 'Thanks!';
			$classmate_message .= '<br><br>';
			$classmate_message .= 'The KetchUp Team<br>';
			$classmate_message .= 'Ben, Zeev, and Mark';

			$classmate_message .= '<br><br>';
			$classmate_message .= '<span style="font-style:italic;">KetchUp is sponsored by the Kellogg Education Technology Incubator (KETI).</span>';

			$classmate_message .= '<br><br><br>';
			$classmate_message .= '--------------------------------------------------------------------------------';
			$classmate_message .= '<br>';
			$classmate_message .= 'If you cannot fulfill this request, please click <a href="'.$baseURL.'/'.$programDirectory.'/delete_request/'.$rqrqid.'/'.$rqcncd.'.html">here</a> to delete the request.  Both you and the requester will receive a confirmation';
			
			//**********
			//message to student (who will be absent)
			//**********

			$student_subject = 'Your KetchUp Record Request';
			$student_message = 'Hi '.$student_name.',';
			$student_message .= '<br><br>';
			$student_message .= 'Your request to record '.$course_name.' has been sent to '.$classmate_name.'.  Thank you for using KetchUp!';
			$student_message .= '<br><br>';
			$student_message .= 'We want to remind you that as usual, all recordings must comply with your school\'s honor code.  If you have not done so already, make sure you ask your professor for permission to record this lecture.';
			$student_message .= '<br><br>';
			
			$student_message .= 'Thanks!';
			$student_message .= '<br><br>';
			$student_message .= 'The KetchUp Team<br>';
			$student_message .= 'Ben, Zeev, and Mark';

			$student_message .= '<br><br>';
			$student_message .= '<span style="font-style:italic;">KetchUp is sponsored by the Kellogg Education Technology Incubator (KETI).</span>';

			//users cannot start a conversation with themselves in canvas
			//so we will take a different path for those instances
			if($classmate_id == $student_id) {
				//first need to get the student email

				//send api call to send message within canvas
				$headers = array(
					//'Authorization: Bearer '.$canvas_access_token,
				);

				$url = 'https://'.$canvas_domain.'/api/v1/users/'.$student_id.'/profile';
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

					$student_email = $primary_email;
					$classmate_email = $primary_email;
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

				//**********
				//send classmate email
				//**********
				$from_email = 'notifications@'.$_SERVER['HTTP_HOST'];
				$from = '"KetchUp Notifications" <'.$from_email.'>';
				
				//based on http://www.shotdev.com/php/php-mail/php-send-email-upload-form-attachment-file/
				$boundary = md5(uniqid(time()));
				
				//actual headers
				$headers = 'From: '.$from."\n";
				$headers .= 'MIME-Version: 1.0'."\n"; 
				$headers .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'."\n\n";
				$headers .= 'This is a multi-part message in MIME format.'."\n";
				
				//message
				$headers .= '--'.$boundary."\n";
				$headers .= 'Content-type: text/html; charset=utf-8'."\n";
				$headers .= 'Content-Transfer-Encoding: 7bit'."\n\n";
				$headers .= $classmate_message."\n\n";
				
				if(mail($classmate_email, $classmate_subject, null, $headers)) { 

				}
				else {
					error_log('could not send the request email 1 - id '.$rqrqid);
				}

				//**********
				//send student email
				//**********
				$from_email = 'notifications@'.$_SERVER['HTTP_HOST'];
				$from = '"KetchUp Notifications" <'.$from_email.'>';
				
				//based on http://www.shotdev.com/php/php-mail/php-send-email-upload-form-attachment-file/
				$boundary = md5(uniqid(time()));

				//actual headers
				$headers = 'From: '.$from."\n";
				$headers .= 'MIME-Version: 1.0'."\n"; 
				$headers .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'."\n\n";
				$headers .= 'This is a multi-part message in MIME format.'."\n";
				
				//message
				$headers .= '--'.$boundary."\n";
				$headers .= 'Content-type: text/html; charset=utf-8'."\n";
				$headers .= 'Content-Transfer-Encoding: 7bit'."\n\n";
				$headers .= $student_message."\n\n";

				if(mail($student_email, $student_subject, null, $headers)) { 

				}
				else {
					error_log('could not send the request email 2 - id '.$rqrqid);
				}

				$arr_response['success'] = true;
				$arr_response['message'] = 'A request to record '.$course_name.' on '.date('l, M. jS', make_unix_date($date)).' was successfully sent to '.$classmate_name;
			}
			else {
				//**********
				//send classmate email (who will record)
				//**********
				//for canvas messages, cannot use html so modify formatting here
				$classmate_message = str_replace('<br>', "\n", $classmate_message);
				$classmate_message = strip_tags($classmate_message);

				//send api call to send message within canvas
				$headers = array(
					//'Authorization: Bearer '.$canvas_access_token,
				);

				$url = 'https://'.$canvas_domain.'/api/v1/conversations';
				$url .= '?access_token='.$canvas_access_token;  //because putting authorization in the header wasnt working for some reason.  ultimately should figure that out.

				$arr_post_fields = array(
					'recipients[]' => $classmate_id,
					'subject' => $classmate_subject,
					'body' => $classmate_message,
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
					//keep going - should probably nest better here.
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

				//**********
				//send student email (who will be absent)
				//**********
				//for canvas messages, cannot use html so modify formatting here
				$student_message = str_replace('<br>', "\n", $student_message);
				$student_message = strip_tags($student_message);

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
	else {
		$arr_response['success'] = false;
		$arr_response['message'] = $error_msg;
	}

	return $arr_response;
}

function api_get_recordings_for_student($api_key, $api_pw, $version, $email, $student_id, $canvas_access_token, $canvas_domain) {
	global $baseURL, $dbconn;

	$arr_response = array();

	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	if($got_error == '') {
		if($version == '1.0') {
			if($email == '') {
				$got_error = 'Y';
				$error_msg = 'Email is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($student_id == '') {
				$got_error = 'Y';
				$error_msg = 'Student id is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_access_token == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas token is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_domain == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas domain is required';
			}
		}
	}

	
	if($got_error == '') {
		if($version == '1.0') {
			$arr_recordings = array();
			
			$racmem = $email;
			$rbstem = $email;
			include('db_recordings_selectRecordingsForStudent.php');

			$arr_response['success'] = true;
			$arr_response['recordings'] = $arr_recordings;
		}
		else {
			$arr_recordings = array();
			
			$racmem = $student_id;
			$rbstem = $student_id;
			include('db_recordings_selectRecordingsForStudentNoPeriod.php');

			$arr_response['success'] = true;
			$arr_response['recordings'] = $arr_recordings;
		}
	}
	else {
		$arr_response['success'] = false;
		$arr_response['message'] = $error_msg;
	}

	return $arr_response;
}

function api_get_requests_for_student($api_key, $api_pw, $version, $email, $student_id, $canvas_access_token, $canvas_domain) {
	global $baseURL, $dbconn;

	$arr_response = array();

	//validation
	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	if($got_error == '') {
		if($version == '1.0') {
			if($email == '') {
				$got_error = 'Y';
				$error_msg = 'Email is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($student_id == '') {
				$got_error = 'Y';
				$error_msg = 'Student id is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_access_token == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas token is required';
			}
		}
	}

	if($got_error == '') {
		if($version != '1.0') {
			if($canvas_domain == '') {
				$got_error = 'Y';
				$error_msg = 'Canvas domain is required';
			}
		}
	}

	if($got_error == '') {
		if($version == '1.0') {
			$arr_requests = array();

			$rqcmem = $email;
			include('db_requests_selectOpenRequestsForStudent.php');

			$arr_response['success'] = true;
			$arr_response['requests'] = $arr_requests;
		}
		else {
			$arr_requests = array();

			$rqcmem = $student_id;
			include('db_requests_selectOpenRequestsForStudentNoPeriod.php');

			$arr_response['success'] = true;
			$arr_response['requests'] = $arr_requests;
		}
	}
	else {
		$arr_response['success'] = false;
		$arr_response['message'] = $error_msg;
	}

	return $arr_response;
}

function api_delete_request($api_key, $api_pw, $request_id) {
	global $baseURL, $dbconn;

	//validation
	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
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

		//send email to student(s)

		foreach($arr_students as $arr_details) {
			$student_name = $arr_details['name'];
			$student_email = $arr_details['email'];

			$to = $student_email;
			$from = '"KetchUp Notifications" <notifications@ketchuptechnology.com>';
			$subject = 'KetchUp Request to record '.$course_name.' Cancelled';
			$message = 'Hi '.$student_name.',';
			$message .= '<br><br>';
			$message .= 'Unfortunately, '.$classmate_usname.' has cancelled your request to record '.$course_name.'. Requests are cancelled for a variety of reasons, including absence, or not having an iPhone. If you are still going to be absent, please ask another student to record the class for you.';
			$message .= '<br><br>';
			$message .= 'Thanks,';
			$message .= '<br><br>';
			$message .= 'The KetchUp Team';
			
			$headers = 'From: '.$from."\n";
			$headers .= 'MIME-Version: 1.0'."\n"; 
			$headers .= 'Content-type:text/html; charset=iso-8859-1'."\n";
			mail($to, $subject, $message, $headers);
		}

		//send email to classmate
		$to = $classmate_usemal;
		$from = '"KetchUp Notifications" <notifications@ketchuptechnology.com>';
		$subject = 'KetchUp Request to record '.$course_name.' Cancelled';
		$message = 'Hi '.$classmate_usname.',';
		$message .= '<br><br>';
		$message .= 'Your decision to cancel a recording request for '.$course_name.' has been received. We have notified ';
		
		//student names (we could improve this for 3+ students)
		$student_name_text = '';
		foreach($arr_students as $arr_details) {
			$student_name = $arr_details['name'];
			$student_email = $arr_details['email'];
		
			$student_name_text .= ' and '.$student_name;
		}
		$student_name_text = substr($student_name_text, 5);
		$message .= $student_name_text;
		
		$message .= ' that you will be unable to record the class.';
		$message .= '<br><br>';
		$message .= 'Thanks,';
		$message .= '<br><br>';
		$message .= 'The KetchUp Team';
		
		$headers = 'From: '.$from."\n";
		$headers .= 'MIME-Version: 1.0'."\n"; 
		$headers .= 'Content-type:text/html; charset=iso-8859-1'."\n";
		mail($to, $subject, $message, $headers);

		//update request as cancelled
		$rqrqid = $request_id;
		$rqactv = 'C';
		include('db_requests_updateRequestActive.php');
	}
}

function api_share_recording($api_key, $api_pw, $request_id, $shared_by, $shared_with, $override_prev_access) {
	global $baseURL, $dbconn;

	//validation
	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	//require request_id
	if($got_error == '') {
		if($request_id == '') {
			$got_error = 'Y';
			$error_msg = 'Please specify which recording to share.';
		}
	}

	//require shared_by
	if($got_error == '') {
		if($shared_by == '') {
			$got_error = 'Y';
			$error_msg = 'Please indicate who is sharing this recording.';
		}
	}

	//require shared_with
	if($got_error == '') {
		if($shared_with == '') {
			$got_error = 'Y';
			$error_msg = 'Please indicate who this recording should be shared with.';
		}
	}

	if($got_error == '') {
		//get request info
		$course_name = '';
		$classmate_usname = '';
		$classmate_usemal = '';
		$arr_students = array();
		
		$rqrqid = $request_id;
		$rqactv = 'D';
		include('db_requests_selectInfo.php');
	}

	if($course_name == '') {
		$got_error = 'Y';
		$error_msg = 'That recording cannot be shared';
	}

	//get recordings to give access
	if($got_error == '') {
		$arr_recordings = array();

		$rcrqid = $request_id;
		include('db_recordings_selectRecordingsForRequestId.php');

		//check if shared_with already has access
		//if override_prev_access == 'Y', then ignore the fact that user may already have access
		if($override_prev_access == '') {
			foreach($arr_recordings as $arr_recording) {
				$filename = $arr_recording['filename'];

				$valid_file = '';

				$rafile = $filename;
				$racmem = $shared_with;
				include('db_recordings_access_selectValidateFilePermissions.php');

				if($valid_file == 'Y') {
					$got_error = 'Y';
					$error_msg = 'User already has access to that recording';
					break;
				}
			}
		}
	}

	//verify that there are recordings to share
	if($got_error == '') {
		if(empty($arr_recordings)) {
			$got_error = 'Y';
			$error_msg = 'No recordings to share';
		}
	}

	//verify that shared_by has access to this recording
	if($got_error == '') {
		foreach($arr_recordings as $arr_recording) {
			$filename = $arr_recording['filename'];

			$valid_file = '';

			$rafile = $filename;
			$racmem = $shared_by;
			include('db_recordings_access_selectValidateFilePermissions.php');

			if($valid_file == '') {
				$got_error = 'Y';
				$error_msg = 'You do not have permission to share this recording.';
				break;
			}
		}
	}

	//verify that shared_with is enrolled in the same course
	if($got_error == '') {
		
	}


	//give classmate access
	if($got_error == '') {
		foreach($arr_recordings as $arr_recording) {
			$filename = $arr_recording['filename'];

			$rafile = $filename;
			$racmem = $shared_with;
			include('db_recordings_access_insertRecordingAccess.php');
		}

		//**********
		//send confirmation email
		//**********
		//get shared_with name
		$usname = '';

		$usemal = $shared_with;
		include('db_users_selectNameForEmail.php');

		$shared_with_name = $usname;

		//get shared_by name (IMPORTANT - THIS MAY BE DIFFERENT THAN THE ORIGINAL REQUESTER)
		$usname = '';

		$usemal = $shared_by;
		include('db_users_selectNameForEmail.php');

		$shared_by_name = $usname;

		//send email
		$to = $shared_with;
		$from = '"KetchUp Notifications" <notifications@'.$_SERVER['HTTP_HOST'].'>';
		$subject = 'KetchUp Recording for '.$course_name.' is now available!';
		$message = 'Hi '.$shared_with_name.',<br><br>Your recording of '.$course_name.' is now available on KetchUp!  Be sure to thank '.$shared_by_name.' for sharing it with you.';
		$message .= '<br><br>';

		$message .= 'Thanks!';
		$message .= '<br><br>';
		$message .= 'The KetchUp Team<br>';
		$message .= 'Ben, Zeev, and Mark';

		$headers = 'From: '.$from."\r\n";
	 	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	 	$headers .= 'Content-type:text/html; charset=iso-8859-1'."\r\n";
	 	mail($to, $subject, $message, $headers);
	 	
		//log that this was successful
		$arr_share_recordings['success'] = true;
	}
	else {
		$arr_share_recordings['success'] = false;
		$arr_share_recordings['message'] = $error_msg;
	}

	return $arr_share_recordings;
}

function api_delete_canvas_token($api_key, $api_pw, $version, $canvas_access_token, $canvas_domain) {
	global $baseURL, $dbconn;

	$arr_response = array();

	//validation
	$got_error = '';
	$error_msg = '';

	if($got_error == '') {
		if(validate_api_key($api_key, $api_pw) != true) {
			$got_error = 'Y';
			$error_msg = 'Invalid KetchUp API Key';
		}
	}

	if($got_error == '') {
		if($version == '2.0') {
			$headers = array(
				'Authorization: Bearer '.$canvas_access_token,
			);

			$url = 'https://'.$canvas_domain.'/login/oauth2/token';
			//$url .= '?access_token='.$canvas_access_token;  //because putting authorization in the header wasnt working for some reason.  ultimately should figure that out.

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HEADER, true);
			//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
			
			//going to assume for now that this works
			$arr_response['success'] = true;
		}
	}
	else {
		$arr_response['success'] = false;
		$arr_response['message'] = $error_msg;
	}

	return $arr_response;
}

?>