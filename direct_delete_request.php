<?php

//**********************************************************************************

include('zz_props.php');
include('zz_dbconn.php');

include('fn_get_session_data.php');
include('fn_api_functions.php');

//**********************************************************************************
//get request vars

$rqid = get('rqid');
$cncd = get('cncd');

//**********************************************************************************
//validate this request
//NOTE - LATER, THIS PAGE SHOULD TAKE TWO PARAMETERS.  CONFIRMATION CODES COULD BE DUPLICATE ON A RECORDING

$rqactv = '';
$valid_request = '';

$rbrqid = $rqid;
$rbcncd = $cncd;
include('db_requests_selectValidateRequestConfirmationCode.php');

//**********************************************************************************
//generate page title & message
//and delete request if applicable

$page_title = '';
$message = '';

if($valid_request == '') {
	$page_title = 'Invalid request';
	$message = 'Invalid request. This request could not be deleted.';
}
else {
	if($rqactv == 'D') {
		$page_title = 'Request deleted';
		$message = 'This request has already been deleted.';
	}
	else {
		$page_title = 'Request deleted';
		$message = 'Your request was successfully deleted.';

		api_delete_request($api_key, $api_pw, $rqid);
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
echo '<title>'.$site_title.' - '.$page_title.'</title>';
echo '<link href="'.$httpPrefix.'fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css" />';
echo '<link href="'.$baseURL.'/'.$programDirectory.'/css/css.css" rel="stylesheet" type="text/css" />';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/jquery-1.10.2.min.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/all.js" type="text/javascript"></script>';
echo '</head>';

echo '<body>';

$parm_arr_navigation_links = $arr_navigation_links;
include('sub_header.php');

/**********BEGIN CONTENT**********/

echo '<div id="content">';
echo '<div class="siteWidth" id="email_confirm">';
echo $message;
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