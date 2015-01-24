<?php

//**********************************************************************************

include('zz_props.php');
include('zz_dbconn.php');

include('fn_get_session_data.php');

//**********************************************************************************
//get recording

$media_file = get('media_file');
$media_file_path = realpath($recordings_directory.'/'.$media_file);

//**********************************************************************************
//**********************************************************************************
//**********************************************************************************
//**********************************************************************************
//**********************************************************************************
//output

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
echo '<title>'.$site_title.' - Playback</title>';
echo '<link href="'.$baseURL.'/'.$programDirectory.'/css/css.css" rel="stylesheet" type="text/css" />';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/jquery-1.10.2.min.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/all.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/flowplayer/flowplayer-3.2.13.min.js" type="text/javascript"></script>';
echo '</head>';

echo '<body>';

include('sub_header.php');

echo '<div id="content" class="siteWidth">';

echo '<h1>Listen to your Recording</h1>';

if(is_file($media_file_path)) {
	//flowplayer
	echo '<div id="player" style="display:block;width:640px;height:26px;">';
	echo '</div>';

	echo '<script>';
	echo '$f("player", "'.$baseURL.'/flowplayer/flowplayer-3.2.18.swf", {
		plugins: {
			recordings: {
				url: "'.$baseURL.'/flowplayer/flowplayer.securestreaming-3.2.8.swf",
				timestamp: parseInt(new Date / 1000)
			},
			controls: {
		        url: "'.$baseURL.'/flowplayer/flowplayer.controls-3.2.16.swf",
		 
		        // display properties such as size, location and opacity
		        top: 0,
		        left: 0,
		        bottom: 0,
		        opacity: 0.95,
		 
		        // styling properties (will be applied to all plugins)
		        backgroundGradient: "low",
		 
		        play: true,
		        scrubber: true,
		        volume: true,
		        fullscreen: false,
		        autoHide: false
		    }
		},
		clip: {
			baseUrl: "'.$baseURL.'/recordings",
			url: "'.$media_file.'",
			provider: "audio",
			urlResolvers: "recordings",
			scaling: "fit"
		},
		screen: {
	        width:0, height:0, top:0, right:0
	    }
	});';
	echo '</script>';
}
else {
	echo 'Recording could not be found';
}

echo '</div>'; //end content

echo '<div class="cb"></div>';

include('sub_footer.php');

echo '</body>';
echo '</html>';

//**********************************************************************************

include('zz_dbclose.php');

?>