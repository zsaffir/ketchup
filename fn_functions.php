<?php

function get_url_directory() {
	$url = $_SERVER['REQUEST_URI'];
	$parts = explode('/', $url);

	$httpPrefix = 'http://';
	if((isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] == 'on')) {
		$httpPrefix = 'https://';
	}

	$url_directory = $httpPrefix.$_SERVER['HTTP_HOST'];
	for($i=0;$i<count($parts) - 1;$i++) {
		$url_directory .= $parts[$i].'/';
	}

	$url_directory = substr($url_directory, 0, -1);

	return $url_directory;
}

function log_sql_error($stmt) {
	global $dbconn;

	$arr_backtrace = debug_backtrace();
	error_log('SQL error in '.$arr_backtrace[0]['file'].':'."\n".$stmt."\n".mysqli_error($dbconn));
}

function sql_sanitize($field, $length='') {
	if($length != '') {
		$field = substr($field,0,$length);
	}

	$field = str_replace("'","''",$field);
	$field = trim($field);

	return $field;
}

function get($get_index, $default_value='') {
	$value = $default_value;
	if(isset($_GET[$get_index])) {
		$value = $_GET[$get_index];
		$value = stripslashes($value);
		$value = trim($value);
	}
	return $value;
}
function post($post_index, $default_value='') {
	$value = $default_value;
	if(isset($_POST[$post_index])) {
		$value = $_POST[$post_index];
		$value = stripslashes($value);
		$value = trim($value);
	}
	return $value;
}
function request($request_index, $default_value='') {
	$value = $default_value;
	if(isset($_REQUEST[$request_index])) {
		$value = $_REQUEST[$request_index];
		$value = stripslashes($value);
		$value = trim($value);
	}
	return $value;
}

function make_unix_date($dateCCYYMMDD, $timeHHMMSS=120000) {
	$year = substr($dateCCYYMMDD, 0, 4);
	$month = substr($dateCCYYMMDD, 4, 2);
	$day = substr($dateCCYYMMDD, 6, 2);
	$hour = substr($timeHHMMSS, 0, 2);
	$minute = substr($timeHHMMSS, 2, 2);
	$second = substr($timeHHMMSS, 4, 2);
	return mktime($hour, $minute, $second, $month, $day, $year);
}



/*These are some app specific functions*/

function validate_api_key($api_key, $api_pw) {
	//for now, these are hardcoded
	if(($api_key == 'this_is_the_api_key') && ($api_pw == 'this_is_the_api_pw')) {
		return true;
	}
	else {
		return false;
	}
}

function get_http_code_kellogg_credentials($user_id, $password) {
	$url = 'https://www4.kellogg.northwestern.edu/courses/';
	$credentials = $user_id.':'.$password;
	$headers = array(
		'Connection: keep-alive',
		'Authorization: Basic '.base64_encode($credentials)
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'); //arbitrarily picked firefox as a user agent
	curl_setopt($ch, CURLOPT_REFERER, $url); //set same url as the referring url
	$sign_in_url = curl_exec($ch);
	
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	return $http_code;
}


?>