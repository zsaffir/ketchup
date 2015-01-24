<?php

//**********************************************************************************
//this script adds an entire class into the KetchUp interface
//based on the number in the PTMBA view classmates
//**********************************************************************************

include('zz_props.php');
include('zz_dbconn.php');

//**********************************************************************************
//create and execute the curl handle to get the token
	
$dropbox_app_key = 'uy8gnzludotn6ry';
$dropbox_app_secret = '94qjkvy6iyk6n22';
$dropbox_redirect_uri = 'https://www.ketchuptechnology.com/education/zeev_test.php';
$dropbox_code = 'zLNsH4tgxIQAAAAAAAAAAQ0LMMSUnFmQ1FDIFDZ_RBM';


$url = 'https://api.dropbox.com/1/oauth2/token';
$credentials = $dropbox_app_key.':'.$dropbox_app_secret;
$headers = array(
	'Authorization: Basic '.base64_encode($credentials)
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'code='.$dropbox_code.'&grant_type=authorization_code&redirect_uri='.$dropbox_redirect_uri);

//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'); //arbitrarily picked firefox as a user agent
//curl_setopt($ch, CURLOPT_REFERER, 'https://www4.kellogg.northwestern.edu/Courses.NET/Default.aspx'); //set class url as the referring url
$response = curl_exec($ch);

echo $response;
die();

//**********************************************************************************

include('zz_dbclose.php');

?>