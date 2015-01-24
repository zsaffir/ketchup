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
$net_id = post('net_id');
$password = post('password');

if($form_submit == 'Y') {
	$got_error = '';
	$error_msg = '';

	//require net id
	if($got_error == '') {
		if($net_id == '') {
			$got_error = 'Y';
			$error_msg = 'Please enter your Net ID';
		}
	}

	//require password
	if($got_error == '') {
		if($password == '') {
			$got_error = 'Y';
			$error_msg = 'Please enter your Password';
		}
	}

	//validate credentials
	if($got_error == '') {
		$arr_valid_credentials = api_validate_credentials($api_key, $api_pw, $net_id, $password, '1.1');

		$success = $arr_valid_credentials['success'];
		if($success == true) {
			$user_id = $arr_valid_credentials['user_id'];
			$user_nid = $arr_valid_credentials['user_nid'];
			$user_pw = $arr_valid_credentials['user_pw'];
			$user_name = $arr_valid_credentials['user_name'];
			$user_email = $arr_valid_credentials['user_email'];
			$password_reset = $arr_valid_credentials['password_reset'];

			$_SESSION['user_id'] = $user_id;
			$_SESSION['user_nid'] = $user_nid;
			$_SESSION['user_name'] = $user_name;
			$_SESSION['user_email'] = $user_email;

			if($user_email == '') {
				//we need to confirm user email
				include('zz_dbclose.php');
				Header('Location: '.$baseURL.'/education/verify_email.html');
				die();
			}
			else {
				//load menu
				include('zz_dbclose.php');
				Header('Location: '.$baseURL.'/education/app/request.html');
				die();
			}
		}
		else {
			$got_error = 'Y';
			$error_msg = $arr_valid_credentials['message'];
		}
	}

	if($got_error != '') {
		$arr_prev_form_values = array();
		$arr_prev_form_values['net_id'] = $net_id;
		$arr_prev_form_values['error_msg'] = $error_msg;
		$_SESSION['arr_prev_form_values'] = $arr_prev_form_values;

		include('zz_dbclose.php');
		Header('Location: '.$currentURL);
		die();
	}
}

//**********************************************************************************

if(isset($arr_prev_form_values['net_id'])) {
	$net_id = $arr_prev_form_values['net_id'];
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
echo '</head>';

echo '<body>';

include('sub_header.php');

echo '<div id="content">';

/**********FOR STUDENTS**********/

echo '<div class="post_container" style="padding:20px 0;">';
echo '<div class="siteWidth">';

echo '<p class="post_text">';
echo 'Sign In';

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

echo '<div class="label">Net ID</div>';
echo '<div class="input"><input type="text" name="net_id" value="'.$net_id.'"></div>';
echo '<div class="cb"></div>';

echo '<div class="label">Password</div>';
echo '<div class="input"><input type="password" name="password" value=""></div>';
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