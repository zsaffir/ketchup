<?php

//**********************************************************************************

include('zz_props.php');
//include('zz_dbconn.php');

include('fn_get_session_data.php');

//**********************************************************************************

if($user_email == '') {
	Header('Location: '.$baseURL.'/education/');
	die();
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
echo '<title>'.$site_title.' - Request</title>';
echo '<link href="'.$httpPrefix.'fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css" />';
echo '<link href="'.$baseURL.'/'.$programDirectory.'/css/css.css" rel="stylesheet" type="text/css" />';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/jquery-1.10.2.min.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/all.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/request.js" type="text/javascript"></script>';

echo '<script type="text/javascript">';
echo 'var user = new Object();';
echo 'user.id = \''.$user_id.'\';';
echo 'user.email = \''.$user_email.'\';';
echo 'user.name = \''.$user_name.'\';';
echo '</script>';

echo '</head>';

echo '<body>';

include('sub_header.php');

echo '<div id="content">';

/**********FOR STUDENTS**********/

echo '<div id="app_nav">';
echo '<ul class="siteWidth">';
echo '<li><span>Request a recording</span></li>';
echo '<li><a href="'.$baseURL.'/education/app/listen.html">Listen to a recording</a></li>';
echo '</ul>';
echo '</div>';

echo '<div class="post_container" style="padding:20px 0;">';
echo '<div class="siteWidth">';

echo '<p class="post_text" id="request_container">';
echo 'Loading...';
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

//include('zz_dbclose.php');

?>