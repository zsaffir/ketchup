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
echo '<title>'.$site_title.' - Terms and Conditions</title>';
echo '<link href="'.$httpPrefix.'fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css" />';
echo '<link href="'.$baseURL.'/'.$programDirectory.'/css/css.css" rel="stylesheet" type="text/css" />';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/jquery-1.10.2.min.js" type="text/javascript"></script>';
echo '<script src="'.$baseURL.'/'.$programDirectory.'/js/all.js" type="text/javascript"></script>';
echo '</head>';

echo '<body>';

include('sub_header.php');

echo '<div id="content">';

/**********STEPS**********/

echo '<div class="post_container" id="terms">';
echo '<div class="siteWidth">';

echo '<h3>THE KETCHUP SERVICE</h3>';
echo '<p class="terms_text">';
echo 'KetchUp Technology (<span class="bold">"KetchUp"</span>) is pleased to offer a service that permits users to request, listen, and record audio of educational content through the KetchUp website, ketchuptechnology.com, and its mobile applications (collectively, the <span class="bold">"KetchUp Service"</span>). If you use the KetchUp Service, you accept these conditions of use (<span class="bold">"Conditions of Use"</span>). Please read them carefully.';
echo '</p>';

echo '<h3>ELECTRONIC COMMUNICATIONS</h3>';
echo '<p class="terms_text">';
echo 'When you use the KetchUp Service, or send e-mails to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by e-mail or by posting notices on or through the KetchUp Service. You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing.';
echo '</p>';

echo '<h3>COPYRIGHT</h3>';
echo '<p class="terms_text">';
echo 'All content included in or made available through the KetchUp Service, such as text, graphics, logos, button icons, images, audio clips, digital downloads, data compilations and KetchUp Content, is the property of KetchUp or its educational content providers and protected by United States and international copyright laws. The compilation of all content accessible through the KetchUp Service is the exclusive property of KetchUp and its educational content providers and protected by U.S. and international copyright laws.';
echo '</p>';

echo '<h3>LICENSE AND SITE ACCESS</h3>';
echo '<p class="terms_text">';
echo 'Subject to your compliance with these Conditions of Use and your payment of any applicable fees, KetchUp or its content providers grant you a limited, non-exclusive, non-transferable, license to access and make personal and non-commercial use of the KetchUp Service. ';
echo '</p>';

echo '<p class="terms_text">';
echo 'All rights not expressly granted to you in these Conditions of Use or any applicable Terms are reserved and retained by KetchUp, its licensors, or other content providers. Neither the KetchUp Service nor any part of it may be reproduced, duplicated, copied, sold, resold, visited, or otherwise exploited for any commercial purpose without express written consent of KetchUp. You are granted a limited, revocable, and nonexclusive right to create a hyperlink to the home page of ketchuptechnology.com so long as the link does not portray KetchUp, or its products or services in a false, misleading, derogatory, or otherwise offensive matter. You may not use any KetchUp logo or other proprietary graphic or trademark as part of the link without express written permission.';
echo '</p>';

echo '<p class="terms_text">';
echo 'You may not misuse the KetchUp Service. You may use the KetchUp Service only as permitted by law. The licenses granted by KetchUp terminate if you do not comply with these Conditions of Use or any applicable Terms.';
echo '</p>';

echo '<h3>YOUR ACCOUNT</h3>';
echo '<p class="terms_text">';
echo 'As a registered user of the KetchUp Service, you may receive or establish one or more passwords and accounts (<span class="bold">"KetchUp Account"</span>). If you use the KetchUp Service, you are responsible for maintaining the confidentiality of your KetchUp Account(s) and for restricting access to your computer(s) or device(s), and you agree to accept responsibility for all activities that occur under any of your KetchUp Accounts. KetchUp reserves the right to refuse service, terminate accounts, and remove or edit content in its sole discretion. The KetchUp Account information will only be used to verify your identity and access your course information, class rosters, and photo. ';
echo '</p>';

echo '<h3>REVIEWS, COMMENTS, COMMUNICATIONS, AND OTHER CONTENT</h3>';
echo '<p class="terms_text">';
echo 'We may permit visitors to post reviews, comments, photos, and other content; send messages and other communications; and submit suggestions, ideas, comments, questions, or other information. You agree that any content you contribute and any communications you send using means we provide will not be illegal, obscene, threatening, defamatory, invasive of privacy, infringing of intellectual property rights, or otherwise injurious to third parties or objectionable and does not consist of or contain software viruses, political campaigning, commercial solicitation, chain letters, mass mailings, or any form of “spam.” You may not use a false e-mail address, impersonate any person or entity, or otherwise mislead as to the origin of a message or other content. We reserve the right (but not the obligation) to remove or edit such content, but we do not regularly review posted content.';
echo '</p>';

echo '<p class="terms_text">';
echo 'If you do post content or submit any material to us, and unless we indicate otherwise, you grant us a nonexclusive, royalty-free, perpetual, and irrevocable right to use, reproduce, modify, adapt, publish, translate, create derivative works from, distribute, and display such content throughout the world in any media. You grant KetchUp the right to use the name that you submit in connection with such content, if so chosen. You represent and warrant that you own or otherwise control all of the rights to the content that you post; that the content is accurate; that use of the content you supply does not violate this policy and will not cause injury to any person or entity; and that you will indemnify KetchUp for all claims resulting from content you supply. We reserve the right (but not the obligation) to monitor and edit or remove any activity or content. KetchUp takes no responsibility and assumes no liability for any content posted by you or any third party.';
echo '</p>';

echo '<h3>COPYRIGHT COMPLAINTS</h3>';
echo '<p class="terms_text">';
echo 'KetchUp respects the intellectual property of others. If you believe that your work has been copied in a way that constitutes copyright infringement, please contact us via ketchuptechnology.com. ';
echo '</p>';

echo '<h3>REMOVAL OF KETCHUP CONTENT OR OTHER MATERIALS</h3>';
echo '<p class="terms_text">';
echo 'We reserve the right to remove or disable access to any KetchUp Content or any other materials posted to or otherwise displayed through the KetchUp Service, including any KetchUp Content that violates or otherwise allegedly infringes on the copyright or other intellectual property, proprietary, or other rights of any person, company or other entity. We will not be liable for the removal of or disabling of access to any KetchUp Content or materials.';
echo '</p>';

echo '<h3>TERMINATION</h3>';
echo '<p class="terms_text">';
echo 'Your right to use the KetchUp Service will automatically terminate if you violate these Conditions of Use or any other Terms. In case of such termination, we may terminate your access to the KetchUp Service without notice. Our failure to insist upon or enforce your strict compliance with the Conditions of Use or any other Terms will not constitute a waiver of any of our rights.';
echo '</p>';

echo '<h3>TERMINATION OF KETCHUP SERVICE</h3>';
echo '<p class="terms_text">';
echo 'Our business may change over time and we reserve the right to modify the KetchUp Service. We also reserve the right to suspend or discontinue the KetchUp Service or your use of the KetchUp Service, in whole or in part, at any time with or without notice and without liability to you.';
echo '</p>';

echo '<h3>APPLICABLE LAW</h3>';
echo '<p class="terms_text">';
echo 'By using the KetchUp Service, you agree that the Federal Arbitration Act, applicable federal law, and the laws of the State of Delaware, without regard to principles of conflict of laws, will govern these Conditions of Use and any dispute of any sort that might arise between you and KetchUp.';
echo '</p>';

echo '<h3>SITE POLICIES, MODIFICATION, AND SEVERABILITY</h3>';
echo '<p class="terms_text">';
echo 'Please review our Terms. We reserve the right to make changes to the KetchUp Service, these Conditions of Use and any Terms at any time. Your continued use of the KetchUp Service following any changes will indicate your acceptance of such changes. If you do not agree to a change, you must immediately stop using the KetchUp Service. If any Terms or these Conditions of Use are deemed invalid, void, or for any reason unenforceable, that condition or term shall be deemed severable and shall not affect the validity and enforceability of any remaining condition or term.';
echo '</p>';

echo '<h3>ADDITIONAL SOFTWARE TERMS</h3>';
echo '<p class="terms_text">';
echo '<ol>';

echo '<li><span class="bold">Use of the KetchUp Software.</span> You may use KetchUp Software solely for the purposes of enabling you to use and enjoy the KetchUp Service as provided by KetchUp, and as permitted by the Conditions of Use, these Additional Software Terms and any applicable Terms. You may not incorporate any portion of the KetchUp Software into your own programs or compile any portion of it in combination with your own programs, transfer it for use with another service, or sell, rent, lease, lend, loan, distribute or sub-license the KetchUp Software or otherwise assign any rights to the KetchUp Software in whole or in part. You may not use the KetchUp Software for any illegal purpose. We may cease providing any KetchUp Software and we may terminate your right to use any KetchUp Software at any time. Your rights to use the KetchUp Software will automatically terminate without notice from us if you fail to comply with any of these Additional Software Terms, the Conditions of Use or any applicable Terms. Additional third party terms contained within or distributed with certain KetchUp Software that are specifically identified in related documentation may apply to that KetchUp Software and will govern the use of such software in the event of a conflict with the Conditions of Use or these Additional Software Terms. All software used in the KetchUp Service is the property of KetchUp or its software suppliers and protected by United States and international copyright laws.</li>';

echo '<li><span class="bold">Use of Third Party Services.</span> When you use the KetchUp Software, you may also be using the services of one or more third parties, such as a wireless carrier or a mobile platform provider. Your use of such third party services may be subject to the separate policies, terms of use, and fees of such third parties.</li>';

echo '<li><span class="bold">No Reverse Engineering.</span> You may not, and you will not encourage, assist or authorize any other person to modify, reverse engineer, decompile or disassemble, or otherwise tamper with, the KetchUp Software, whether in whole or in part, or create any derivative works from or of the KetchUp Software.</li>';

echo '<li><span class="bold">Updates.</span> In order to keep the KetchUp Software up-to-date, we may offer automatic or manual updates at any time and without notice to you.</li>';
echo '</ol>';
echo '</p>';
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