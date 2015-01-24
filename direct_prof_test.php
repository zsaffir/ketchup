<?php

//**********************************************************************************

include('zz_props.php');
//include('zz_dbconn.php');

include('fn_get_session_data.php');

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

echo '<div class="post_container" id="never_easier">';
echo '<div class="siteWidth">';

echo '<p class="post_text">';
echo 'Professors, interested in having more control over your class recordings? Enter your email address below to vote for this feature. If enough professors express interest, we\'ll develop this feature.';
echo '<br><br>';

echo '<form id="prof_test_form" method="post" action="'.$baseURL.'/education/prof_test_thanks.html">';
echo '<input type="submit" class="post_submit_real">';
echo '<input type="text" name="prof_email" class="post_input">';
echo '<br>';
echo '<a href="javascript:document.getElementById(\'prof_test_form\').submit();" class="post_submit_link">Submit</a>';

echo '</form>';

echo '</p>';

echo '</div>';
echo '</div>';

/**********STEPS**********/

echo '<div class="post_container" id="steps">';
echo '<div class="siteWidth">';

echo '<div class="student_step">';
echo '<img src="'.get_url_directory().'/img/request_icon_sel.png" alt="Request">';
echo 'Ask a friend to record the class you\'re going to miss';
echo '</div>';

echo '<div class="student_step">';
echo '<img src="'.get_url_directory().'/img/check_icon_sel.png" alt="Check">';
echo 'Your friend accepts the request';
echo '</div>';

echo '<div class="student_step">';
echo '<img src="'.get_url_directory().'/img/record_icon_sel.png" alt="Record">';
echo 'Your friend records the lecture on his or her smartphone';
echo '</div>';

echo '<div class="student_step">';
echo '<img src="'.get_url_directory().'/img/listen_icon_sel.png" alt="Listen">';
echo 'The recording is instantly available on your smartphone';
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

//include('zz_dbclose.php');

?>