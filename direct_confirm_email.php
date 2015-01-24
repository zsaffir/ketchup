<?php

//**********************************************************************************

include('zz_props.php');
include('zz_dbconn.php');

include('fn_get_session_data.php');

//**********************************************************************************
//get request vars

$cfid = get('cfid', 0);
$cncd = get('cncd');
$usnid = get('usnid');

//for version 1.2
if($usnid == 'confirm') {
	$usnid = '';
}

//**********************************************************************************
//field focus

$field_focus = '';

//**********************************************************************************
//validate this confirmation

$cfemal = '';
$cfvers = '';

$cfcfid = $cfid;
$cfcncd = $cncd;
$cfusnid = $usnid;
include('db_confirmations_selectEmail.php');

//**********************************************************************************
//update user

if($cfemal != '') {
	if($cfvers == '1.2') {
		$submit_form = post('submit_form');
		//$input_name = post('input_name');
		$input_password_1 = post('input_password_1');
		$input_password_2 = post('input_password_2');
		
		//focus on name
		$field_focus = 'input_password_1';
		
		//check if user has already been created
		$uspw = '';
		
		$usemal = $cfemal;
		include('db_users_selectPasswordForEmail.php');

		$user_password = $uspw;

		if($user_password == '') {
			if($submit_form == 'Y') {
				$got_error = '';
				$error_msg = '';
				
				/*if($got_error == '') {
					if($input_name == '') {
						$got_error = 'Y';
						$error_msg = 'Please enter your name.';
					}
				}*/
				if($got_error == '') {
					if($input_password_1 == '') {
						$got_error = 'Y';
						$error_msg = 'Please enter a password';
						$field_focus = 'input_password_1';
					}
				}
				if($got_error == '') {
					if($input_password_2 == '') {
						$got_error = 'Y';
						$error_msg = 'Please enter a password';
						$field_focus = 'input_password_1';
					}
				}
				//additional password validation goes here
				//(min/max chars, 
				if($got_error == '') {
					if($input_password_1 != $input_password_2) {
						$got_error = 'Y';
						$error_msg = 'Passwords must match';
						$field_focus = 'input_password_1';
					}
				}
				
				//no errors
				if($got_error == '') {
					//sanitize name
					//$input_name = strtolower($input_name);
					//$input_name = ucwords($input_name);
					
					//parse out school id
					$arr_email_parts = explode('@', $cfemal);
					$school_id = $arr_email_parts[1];
					
					/*
					//insert user
					$usemal = $cfemal;
					$usnid = '';
					$uspw = $input_password_1;
					$usscid = $school_id;
					$usname = $input_name; 
					$usimg = '';
					include('db_users_insertUser.php');
					*/
					
					//update password
					
					$uspw = $input_password_1;
					$usemal = $cfemal;
					include('db_users_updatePassword.php');
					
					include('zz_dbclose.php');
					header('Location: '.$currentURL);
					die();
				}
			}
		}
		else {
			//flag confirmation as inactive
			$cfactv = 'X';
			$cfcfid = $cfid;
			include('db_confirmations_updateActive.php');
		}
	}
	else {
		//update user id
		$usnid = $usnid;
		$usemal = $cfemal;
		include('db_users_updateNewUserId.php');

		//flag confirmation as inactive
		$cfactv = 'X';
		$cfcfid = $cfid;
		include('db_confirmations_updateActive.php');
	}
}

//**********************************************************************************
//navigation links

$arr_navigation_links = array(
	//'For Students' => 'students.html',
	//'For Professors' => 'professors.html',
	//'Sign Up' => 'http://tflig.ht/1dzcSkm',
	'Contact Us' => 'mailto:support@ketchuptechnology.com'
);

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
echo '<title>'.$site_title.' - Confirm Email</title>';
echo '<link href="'.$httpPrefix.'fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css" />';
echo '<link href="'.$baseURL.'/'.$programDirectory.'/css/css.css" rel="stylesheet" type="text/css" />';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/jquery-1.10.2.min.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/all.js" type="text/javascript"></script>';
echo '</head>';

if($field_focus == '') {
	echo '<body>';
}
else {
	echo '<body onload="document.getElementById(\''.$field_focus.'\').focus();">';
}

$parm_arr_navigation_links = $arr_navigation_links;
include('sub_header.php');

/**********BEGIN CONTENT**********/

echo '<div id="content">';
echo '<div class="siteWidth" id="email_confirm">';
if($cfvers == '1.2') {
	if($user_password == '') {
		if($cfemal != '') {
			echo '<p class="post_text">Complete your signup</p>';
		
			echo '<form method="post" action="'.$currentURL.'" id="sign_up_form">';
			echo '<input type="hidden" name="submit_form" value="Y">';
			echo '<input type="submit" class="post_submit_real">';
			
			echo '<br>';
			if(isset($error_msg)) {
				if($error_msg != '') {
					echo '<span style="color:red;">';
					echo $error_msg;
					echo '</span>';
					echo '<br>';
				}
			}
			
			echo 'Email address:';
			echo '<br>';
			echo $cfemal;
			echo '<br><br>';
					
			/*echo 'Enter your full name:';
			echo '<br>';
			echo '<input type="text" name="input_name" maxlength="50" id="input_name" value="'.$input_name.'">';
			echo '<br><br>';*/
	
			echo 'Create a password';
			echo '<br>';
			echo '<input type="password" name="input_password_1" maxlength="50" id="input_password_1">';
			echo '<br><br>';
	
			echo 'Confirm your password';
			echo '<br>';
			echo '<input type="password" name="input_password_2" maxlength="50" id="input_password_2">';
			echo '<br><br>';
	
			echo '<a href="javascript:document.getElementById(\'sign_up_form\').submit();">Submit</a>';
			echo '<div class="cb"></div>';
			echo '</form>';
		}
	}
	else {
		echo 'Your email address ('.$cfemal.') has been confirmed! Log in to the KetchUp app on your smartphone to begin recording.';
		
	}
}
else {
	if($cfemal != '') {
		echo 'Your email address ('.$cfemal.') has been confirmed! Log in to the KetchUp app on your smartphone to begin recording.';
	}
	else {
		echo 'Your email address has already been confirmed. If you are experiencing problems, please <a href="mailto:support@ketchuptechnology.com">contact us.</a>';
	}
}
//echo '</div>';

/**********END CONTENT**********/

echo '</div>';

$parm_arr_navigation_links = $arr_navigation_links;
include('sub_footer.php');

echo '</body>';
echo '</html>';

//**********************************************************************************

include('zz_dbclose.php');

?>