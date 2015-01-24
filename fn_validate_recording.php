<?php

//**********************************************************************************
//validate a video - maintain security
//
//based off of: http://flowplayer.blacktrash.org/secure-http.html
//**********************************************************************************

include('zz_props.php');
include('zz_dbconn.php');

include('fn_get_session_data.php');

//**********************************************************************************
//some http headers to prevent problems from app

header('Access-Control-Allow-Origin: *');

//**********************************************************************************

set_time_limit(1200); //20 minute time limit
ini_set('memory_limit','100M');

//**********************************************************************************

$time = get('time');
$current_time = time();

$media_file = get('media_file');

$hash = get('hash');
$token = '--|<37(|-|UP--';
$checkhash = md5($token . '/' . $media_file . $time);

//**********************************************************************************
//validation

$got_error = '';
$error_msg = '';
$error_code = '';

if($got_error == '') {
	//make sure file exists

	//timestamp
	if($got_error == '') {
		if($current_time - $time > 2) {
			$got_error = 'Y';
			$error_msg = 'You are too late: '.($current_time - $time);
		}
	}

	//validate hash/token
	if($got_error == '') {
		if($checkhash != $hash) {
			$got_error = 'Y';
			$error_msg = 'Hash does not match';
			$error_code = 'HTTP/1.0 404 Not Found';
		}
	}

	//validate referer
	if($got_error == '') {
		$referer = $baseURL.'/education/app/listen.html';
		if($_SERVER['HTTP_REFERER'] != $referer) {
			$got_error = 'Y';
			$error_msg = 'Referer does not match';
			$error_code = 'HTTP/1.0 404 Not Found';
		}
	}

	//validate user can view this recording
	if($got_error == '') {
		$valid_file = '';

		$racmem = $user_email;
		$rafile = $media_file;
		include('db_recordings_access_selectValidateFilePermissions.php');

		if($valid_file == '') {
			$got_error = 'Y';
			$error_msg = 'User is not permitted to listen to this recording';
			$error_code = 'HTTP/1.0 401 Unauthorized';
		}
	}
}

//**********************************************************************************
//**********************************************************************************
//**********************************************************************************
//serve up video

if($got_error == '') {
	//$fsize = filesize($media_file_path);
	
	/*header('Content-Disposition: attachment; filename="'.$media_file.'"');
	header('Content-Type: video/aac');
	header('Content-Length: '.$fsize);
	session_cache_limiter('nocache');
	header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	header('Pragma: no-cache');
	$file = fopen($media_file_url, 'r');
	print(fread($file, $fsize));
	fclose($file);*/

	/*header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header('Content-Type: video/aac');
	header('Content-Disposition: attachment; filename="'.$media_file.'"');
	header("Content-Transfer-Encoding: binary");
	header("Content-length: ".$fsize);
	header("Cache-control: public"); //use this to open files directly
	$file = fopen($media_file_url, 'r');
	print(fread($file, $fsize));
	fclose($file);*/

	/*//THIS WORKS BUT IT IS REALLY SLOW	
	$url = 'https://api-content.dropbox.com/1/files/dropbox/recordings/'.$media_file;
	$dropbox_authorization = 'Bearer NvCbEjbwqTEAAAAAAAAAAXgfn7Yk2YMdVWlFHxQkvlLxZBDqqg-CnKxyOG7RgC-L';
	$headers = array(
		'Connection: keep-alive',
		'Authorization: '.$dropbox_authorization
	);
	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$audio_file = curl_exec($ch);
	curl_close($ch);
	
	echo $audio_file;*/

	/*//THIS WORKED
	$file = fopen($media_file_url, "r");
	if (!$file) {
	    error_log('could not open file');
	    exit;
	}
	while (!feof ($file)) {
	    $line = fgets($file, 1024);
	    echo $line;
	}
	fclose($file);*/

	//THIS WORKS

	header('Content-Type: audio/aac');
	session_cache_limiter('nocache');
	header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
	/*header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	header('Pragma: no-cache');*/

	$headers = array(
		'Connection: keep-alive',
		'Authorization: '.$dropbox_authorization
	);
	$url = 'https://api.dropbox.com/1/media/dropbox/recordings/'.$media_file;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$json_media = curl_exec($ch);
	
	$arr_media = json_decode($json_media , true);
	$streaming_url = $arr_media['url'];

	$file = fopen($streaming_url, "r");
	if(!$file) {
	    error_log('could not open file');
	    exit;
	}
	while(!feof ($file)) {
	    $line = fgets($file, 1024);
	    echo $line;
	}
	fclose($file);

	/*//THIS WORKS - STILL SLOW
	$dropbox_authorization = 'Bearer NvCbEjbwqTEAAAAAAAAAAXgfn7Yk2YMdVWlFHxQkvlLxZBDqqg-CnKxyOG7RgC-L';
	$headers = array(
		'Connection: keep-alive',
		'Authorization: '.$dropbox_authorization
	);
	$url = 'https://api.dropbox.com/1/media/dropbox/recordings/'.$media_file;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$json_media = curl_exec($ch);
	
	$arr_media = json_decode($json_media , true);
	$streaming_url = $arr_media['url'];

	  define('CHUNK_SIZE', 1024*1024); // Size (in bytes) of tiles chunk

	  // Read a file and display its content chunk by chunk
	  function readfile_chunked($filename, $retbytes = TRUE) {
	    $buffer = '';
	    $cnt =0;
	    // $handle = fopen($filename, 'rb');
	    $handle = fopen($filename, 'rb');
	    if ($handle === false) {
	      return false;
	    }
	    while (!feof($handle)) {
	      $buffer = fread($handle, CHUNK_SIZE);
	      echo $buffer;
	      @ob_flush();
	      flush();
	      if ($retbytes) {
	        $cnt += strlen($buffer);
	      }
	    }
	    $status = fclose($handle);
	    if ($retbytes && $status) {
	      return $cnt; // return num. bytes delivered like readfile() does.
	    }
	    return $status;
	  }

	$filename = $streaming_url;
	$mimetype = 'video/aac';
	header('Content-Type: '.$mimetype );
	readfile_chunked($filename);*/


}
else {
	error_log($error_msg);
	header($error_code);
}

//**********************************************************************************

include('zz_dbclose.php');

?>