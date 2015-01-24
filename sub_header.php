<?php

//**********************************************************************************
//incoming parms: parm_arr_navigation_links
//**********************************************************************************
//for facebook like button

/*echo '<div id="fb-root"></div>';
echo '<script src="'.$baseURL.'/js/fb.js" type="text/javascript"></script>';

//**********************************************************************************
//for google analytics

echo '<script src="'.$baseURL.'/js/ga.js" type="text/javascript"></script>';*/

//**********************************************************************************
//navigation links

$arr_navigation_links = array(
	'Contact Us' => array(
		'link' => 'mailto:support@ketchuptechnology.com'
	)
);

if((isset($_SESSION['user_email']) === FALSE) || ($_SESSION['user_email'] == '')) {
	$arr_navigation_links['Sign In'] = array(
		'link' => $baseURL.'/education/sign_in.html',
		'class' => 'menu_rt'
	);
}
else {
	$arr_navigation_links['Sign Out'] = array(
		'link' => 'javascript:sign_out();',
		'class' => 'menu_rt'
	);
	$arr_navigation_links['My Account'] = array(
		'link' => $baseURL.'/education/app/request.html',
		'class' => 'menu_rt'
	);
}

/*$arr_navigation_links['Professor Control Center'] = array(
	'link' => $baseURL.'/education/prof_test.html',
	'class' => 'menu_rt'
);*/

$parm_arr_navigation_links = $arr_navigation_links;

//**********************************************************************************

echo '<div id="header">';
echo '<ul class="siteWidth">';

echo '<li>';
echo '<a href="'.$baseURL.'/" class="hdr_img"><img src="'.$baseURL.'/'.$programDirectory.'/img/ketchup_logo_white.png" alt="KetchUp"><h1>KetchUp</h1></a>';
echo '</li>';

foreach($parm_arr_navigation_links as $label => $arr_details) {
	$link = $arr_details['link'];
	
	//start tag
	echo '<li class="';	
	if($link == $currentURLNoParms) {
		echo 'hdr_sel ';
	}

	if(isset($arr_details['class'])) {
		echo $arr_details['class'].' ';
	}
	echo '">';
	
	//content
	if($link != $currentURLNoParms) {
		echo '<a href="'.$link.'" class="hdr_non_img">'.$label.'</a>';
	}
	else {
		echo $label;
	}
	
	//close tag
	echo '</li>';
}
echo '</ul>';

echo '</div>';

?>