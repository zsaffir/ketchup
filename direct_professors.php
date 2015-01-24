<?php

//**********************************************************************************

include('zz_props.php');
include('zz_dbconn.php');

include('fn_get_session_data.php');

//**********************************************************************************
//navigation links

$arr_navigation_links = array(
	'For Students' => 'students.html',
	'For Professors' => 'professors.html',
	'Sign Up' => 'http://tflig.ht/1dzcSkm'
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
echo '<link rel="shortcut icon" href="'.$baseURL.'/favicon.ico" type="image/x-icon" />';
echo '<title>'.$site_title.'</title>';
echo '<link href="'.$httpPrefix.'fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css" />';
echo '<link href="'.$baseURL.'/'.$programDirectory.'/css/css.css" rel="stylesheet" type="text/css" />';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/jquery-1.10.2.min.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/all.js" type="text/javascript"></script>';
echo '</head>';

echo '<body>';

$parm_arr_navigation_links = $arr_navigation_links;
include('sub_header.php');

echo '<div id="content">';

/**********FOR STUDENTS**********/

echo '<div class="post_container" style="background-image:url(\''.get_url_directory().'/img/KetchupBackground-1.jpg\');height:420px;">';

echo '<div class="siteWidth">';
echo '<p class="post_text" style="font-size:24px;line-height:32px;width:405px;">';
echo 'KetchUp is committed to protecting the IP of professors.';
echo '</p>';
echo '</div>';

echo '</div>';

/**********STEPS**********/

echo '<div class="post_container" id="student_steps" style="padding:20px 0 20px 0;">';
echo '<div class="siteWidth">';

echo '<div style="margin:0 auto;width:720px;">';
echo '<div class="student_step">';
echo '<img src="'.get_url_directory().'/img/check_icon_sel.png" alt="Check">';
echo 'When a recording is accepted, you receive an email notification. Professors have the right to veto any recording for any reason.';
echo '</div>';

echo '<div class="student_step">';
echo '<img src="'.get_url_directory().'/img/record_icon_sel.png" alt="Record">';
echo 'Once the recording is completed, you receive another email. If the recording was unauthorized, you can immediately disable access.';
echo '</div>';

echo '<div class="student_step">';
echo '<img src="'.get_url_directory().'/img/listen_icon_sel.png" alt="Listen">';
echo 'Recordings are only available for a limited time. The default is the end of the current quarter, but you can modify that at any time through your control panel.';
echo '</div>';
echo '</div>';

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