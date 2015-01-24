<?php

//**********************************************************************************

include('zz_props.php');
include('zz_dbconn.php');

include('fn_get_session_data.php');
include('fn_api_functions.php');

//**********************************************************************************

if($user_email != '') {
	Header('Location: '.$baseURL.'/education/app/request.html');
	die();
}

//**********************************************************************************

$form_submit = post('form_submit');
$email = post('email');

if($form_submit == 'Y') {
	$got_error = '';
	$error_msg = '';

	//require email
	if($got_error == '') {
		if($email == '') {
			$got_error = 'Y';
			$error_msg = 'Please enter your email';
		}
	}

	//require .northwestern.edu
	if($got_error == '') {
		if(strpos($email, '.northwestern.edu') === FALSE) {
			$got_error = 'Y';
			$error_msg = 'Please enter your school email';
		}
	}

	//validate credentials
	if($got_error == '') {
		$arr_response = api_send_email_confirmation($api_key, $api_pw, $user_nid, $email, '1.0');

		$success = $arr_response['success'];
		if($success == true) {
			$user_id = '';
			$user_nid = '';
			$user_name = '';
			$user_email = '';

			$_SESSION['user_id'] = $user_id;
			$_SESSION['user_nid'] = $user_nid;
			$_SESSION['user_name'] = $user_name;
			$_SESSION['user_email'] = $user_email;

			$error_msg = 'Confirmation Email Sent. Please click the link in your email to activate your KetchUp account. Then, sign in again.';

			$arr_prev_form_values['error_msg'] = $error_msg;
			$_SESSION['arr_prev_form_values'] = $arr_prev_form_values;

			//load sign in screen
			include('zz_dbclose.php');
			Header('Location: '.$baseURL.'/education/sign_in.html');
			die();
		}
		else {
			$error_msg = 'An error occurred. Please try again';

			$arr_prev_form_values['email'] = $email;
			$arr_prev_form_values['error_msg'] = $error_msg;
			$_SESSION['arr_prev_form_values'] = $arr_prev_form_values;

			include('zz_dbclose.php');
			Header('Location: '.$baseURL.'/education/verify_email.html');
			die();
		}
	}
	else {
		$arr_prev_form_values['email'] = $email;
		$arr_prev_form_values['error_msg'] = $error_msg;
		$_SESSION['arr_prev_form_values'] = $arr_prev_form_values;

		include('zz_dbclose.php');
		Header('Location: '.$baseURL.'/education/verify_email.html');
		die();
	}
}

//**********************************************************************************

if(isset($arr_prev_form_values['email'])) {
	$email = $arr_prev_form_values['email'];
}

//**********************************************************************************
//**********************************************************************************
//**********************************************************************************
//**********************************************************************************
//**********************************************************************************
//output

echo '<!DOCTYPE html>';
echo '<html dir="LTR" lang="en-US">';
echo '<head>';
echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<link rel="shortcut icon" href="'.$baseURL.'/favicon.ico" type="image/x-icon" />';
echo '<title>'.$site_title.' - Class Recordings for Education</title>';
echo '<link href="'.$httpPrefix.'fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css" />';
echo '<link href="'.$baseURL.'/'.$programDirectory.'/css/css.css" rel="stylesheet" type="text/css" />';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/jquery-1.10.2.min.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/all.js" type="text/javascript"></script>';
echo '<script>';
echo 'add_load_event(function(){document.getElementById(\'email\').focus();});';
echo '</script>';
echo '</head>';

echo '<body>';

include('sub_header.php');

echo '<div id="content">';

/**********FOR STUDENTS**********/

echo '<div class="post_container" style="padding:20px 0;">';
echo '<div class="siteWidth">';

echo '<p class="post_text">';
echo 'Please enter your school email address to complete your signup';

echo '<form method="post" action="'.$currentURLNoParms.'" id="sign_in_form">';
echo '<input type="hidden" name="form_submit" value="Y">';
echo '<input type="submit" class="post_submit_real">';

echo '<div class="label"></div>';
echo '<div class="input" style="color:red;">';
if(isset($arr_prev_form_values['error_msg'])) {
	echo $arr_prev_form_values['error_msg'];
}
else {
	echo '&nbsp';
}
echo '</div>';
echo '<div class="cb"></div>';	

echo '<div class="label">Email</div>';
echo '<div class="input"><input type="text" name="email" value="'.$email.'" id="email" style="width:350px;"></div>';
echo '<div class="cb"></div>';

echo '<div class="label">&nbsp;</div>';
echo '<div class="input"><a href="javascript:document.getElementById(\'sign_in_form\').submit();">Sign In</a></div>';
echo '<div class="cb"></div>';

echo '</form>';

echo '</p>';

echo '</div>';
echo '</div>';

/**********END CONTENT**********/

echo '</div>';

$parm_arr_navigation_links = $arr_navigation_links;
include('sub_footer.php');

echo '</body>';
echo '</html>';

//**********************************************************************************

include('zz_dbclose.php');

?>