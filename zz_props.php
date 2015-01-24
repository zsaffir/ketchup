<?php

//**********************************************************************************

date_default_timezone_set('America/Chicago');

//**********************************************************************************

include('fn_functions.php');

//**********************************************************************************

$site_title = 'KetchUp';

//**********************************************************************************

session_start();

//**********************************************************************************

$secureConn = 0;
$httpPrefix = 'http://';
if((isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] == 'on')) {
	$secureConn = 1;
	$httpPrefix = 'https://';
}

//**********************************************************************************

$baseURL = $httpPrefix.$_SERVER['HTTP_HOST'];
$programDirectory = 'education';

//**********************************************************************************

$currentPage = substr($_SERVER['REQUEST_URI'],1);
$currentPageNoParms = substr($currentPage,0,strpos($currentPage,'?'));
if($currentPageNoParms == '') $currentPageNoParms = $currentPage;

$currentURL = $baseURL.'/'.$currentPage;
$currentURLNoParms = $baseURL.'/'.$currentPageNoParms;

//**********************************************************************************

$api_key = 'this_is_the_api_key';
$api_pw = 'this_is_the_api_pw';

$dropbox_authorization = 'Bearer NvCbEjbwqTEAAAAAAAAAAXgfn7Yk2YMdVWlFHxQkvlLxZBDqqg-CnKxyOG7RgC-L';

//**********************************************************************************

//$_SESSION['test'] = 'zeev';

?>