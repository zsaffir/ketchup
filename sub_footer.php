<?php

//**********************************************************************************
//incoming parms: parm_arr_navigation_links
//**********************************************************************************

echo '<div style="width:100%;overflow:hidden;">';

echo '<div class="post_container" id="footer_signup">';
echo '<div class="siteWidth">';

echo '<p class="post_text">';
echo '<a href="https://itunes.apple.com/us/app/ketchup-class-recorder/id812449953">Download KetchUp for iOS now!</a>';
echo '</p>';

echo '</div>';
echo '</div>';


echo '<div id="footer">';
echo '<div class="siteWidth">';

echo '<ul>';
echo '<li>';
echo '<a href="'.get_url_directory().'" class="hdr_non_img">Home</a>';
echo '</li>';

foreach($parm_arr_navigation_links as $label => $arr_details) {
	$link = $arr_details['link'];
	
	echo '<li>';
	echo '<a href="'.$link.'" class="hdr_non_img">'.$label.'</a>';
	echo '</li>';
}

echo '<li>';
echo '<a href="'.get_url_directory().'/terms.html" class="hdr_non_img">Terms of use</a>';
echo '</li>';
echo '</ul>';

echo '</div>';
echo '</div>';

echo '</div>';

?>