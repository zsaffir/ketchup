<?php

//**********************************************************************************

include('zz_props.php');
include('zz_dbconn.php');

include('fn_get_session_data.php');
include('fn_api_functions.php');

//**********************************************************************************

if($user_email == '') {
	Header('Location: '.$baseURL.'/education/');
	die();
}

//**********************************************************************************
//get recordings

$arr_recordings = array();

$arr_valid_recordings = api_get_recordings_for_student($api_key, $api_pw, '1.0', $user_email, '', '', '');

//**********************************************************************************
//take action

$success = $arr_valid_recordings['success'];
if($success == true) {
	$arr_recordings = $arr_valid_recordings['recordings'];
}

//**********************************************************************************
//eliminate non-recordings (requests)

$arr_actual_recordings = array();

foreach($arr_recordings as $count => $arr_details) {
	if(isset($arr_details['filename'])) {
		$arr_actual_recordings[] = $arr_details;
	}
}

$arr_recordings = $arr_actual_recordings;

//**********************************************************************************

$default_recording = '';
$default_row = '';
foreach($arr_recordings as $count => $arr_details) {
	if(isset($arr_details['filename'])) {
		$default_recording = $arr_details['filename'];
		$default_row = $count;
		break;
	}
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
echo '<title>'.$site_title.' - Listen</title>';
echo '<link href="'.$httpPrefix.'fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css" />';
echo '<link href="'.$baseURL.'/'.$programDirectory.'/css/css.css" rel="stylesheet" type="text/css" />';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/jquery-1.10.2.min.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/all.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/listen.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/flowplayer/flowplayer-3.2.13.min.js" type="text/javascript"></script>';

echo '<script type="text/javascript">';
echo 'var default_recording = \''.$default_recording.'\';';
echo 'var recording_row = \''.$default_row.'\';';
echo '</script>';

echo '</head>';

echo '<body>';

include('sub_header.php');

echo '<div id="content">';

/**********PLAYBACK**********/

echo '<div id="app_nav">';
echo '<ul class="siteWidth">';
echo '<li><a href="'.$baseURL.'/education/app/request.html">Request a recording</a></li>';
echo '<li><span>Listen to a recording</span></li>';
echo '</ul>';
echo '</div>';

echo '<div class="post_container" style="padding:20px 0;">';
echo '<div class="siteWidth">';

echo '<div id="player"></div>';

echo '<div id="recordings_info">';
echo $user_name.'\'s KetchUp Recordings';
echo '</div>';

echo '<table id="listen_table">';

$class = 'white_row';
foreach($arr_recordings as $count => $arr_details) {
	$course = $arr_details['course'];
	$date = $arr_details['date'];
	$recorder = $arr_details['recorder'];

	$filename = '';
	if(isset($arr_details['filename'])) {
		$filename = $arr_details['filename'];
	}

	$expiration_date = '';
	if(isset($arr_details['expiration_date'])) {
		$expiration_date = $arr_details['expiration_date'];
	}

	if($filename != '') {
		echo '<tr id="row-'.$count.'" class="'.$class.'" onclick="javascript:load_audio(\''.$filename.'\', '.$count.');" onmouseover="row_over('.$count.');" onmouseout="row_out('.$count.');">';
		echo '<td class="status"></td>';
		echo '<td class="course">'.$course.'</td>';
		echo '<td class="date">'.$date.'</td>';
		echo '<td class="recorder">Recorded by '.$recorder.'</td>';
		echo '</tr>';

		if($class == 'white_row') {
			$class = 'color_row';
		}
		else {
			$class = 'white_row';
		}
	}
}
if(empty($arr_recordings)) {
	echo '<tr>';
	echo '<td style="width:653px;padding-left:30px;">You have no recordings yet. <a href="'.$baseURL.'/education/app/request.html">Request a recording from a classmate</a>.</td>';
	echo '</tr>';
}

echo '</table>';

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