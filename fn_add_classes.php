<?php

//**********************************************************************************
//this script adds new classes
//**********************************************************************************

include('zz_props.php');
include('zz_dbconn.php');

//**********************************************************************************
//sometimes this script takes a while...

set_time_limit(1200); //20 minutes

//**********************************************************************************
//we want any output to happen instantly so we can monitor progress

@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);
@ini_set('output_buffering', 0);
for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
ob_implicit_flush(1);

//**********************************************************************************

$new_classes = '18845,DECS-430-A,Business Analytics I,Monday,09:30 - 12:30
18846,DECS-430-A,Business Analytics I,Monday,09:30 - 12:30
18848,DECS-430-A,Business Analytics I,Monday,09:30 - 12:30
18850,DECS-430-A,Business Analytics I,Monday,09:30 - 12:30
19053,MORS-935-C,Personal Leadership Insights,Monday,10:10 - 12:00
18844,DECS-430-A,Business Analytics I,Monday,14:00 - 17:00
18847,DECS-430-A,Business Analytics I,Monday,14:00 - 17:00
18849,DECS-430-A,Business Analytics I,Monday,14:00 - 17:00
18851,DECS-430-A,Business Analytics I,Monday,14:00 - 17:00
18879,DECS-431-0,Business Analytics II,Monday,18:00 - 21:00
18652,FINC-442-0,Financial Decisions,Monday,18:00 - 21:00
19397,FINC-442-0,Financial Decisions,Monday,18:00 - 21:00
18663,FINC-461-0,Investment Banking,Monday,18:00 - 21:00
18446,KIEI-902-A,Entrepreneurial Selling,Monday,18:00 - 21:00
19011,MGMT-934-0,Managing Turnarounds,Monday,18:00 - 21:00
18656,MKTG-430-0,Marketing Management,Monday,18:00 - 21:00
19383,MKTG-940-0,C Suite and Boardroom Dynamics,Monday,18:00 - 21:00
18794,MORS-430-0,Leadership in Organizations,Monday,18:00 - 21:00
18825,MORS-452-0,Leading the Strategic Change Process,Monday,18:00 - 21:00
18788,MORS-476-0,Bargaining (formerly MORS-914-0),Monday,18:00 - 21:00
19399,MORS-476-0,Bargaining (formerly MORS-914-0),Monday,18:00 - 21:00
19295,OPNS-955-A,Scaling Operations: Linking Strategy and Execution,Monday,18:00 - 21:00
19237,REAL-916-0,Real Estate Lab,Monday,18:00 - 21:00
18747,ACCT-444-0,Financial Planning For Mergers and Acquisitions,Monday,18:30 - 21:30
18664,FINC-463-0,Securities Analysis,Monday,18:30 - 21:30
18450,KIEI-965-0,Global Governance of Private Companies,Monday,18:30 - 21:30
19013,MGMT-460-0,International Business Strategy,Monday,18:30 - 21:30
19014,MGMT-960-A,Strategy to Execution - A Capabilities Approach,Monday,18:30 - 21:30
19014,MGMT-960-A,Strategy to Execution - A Capabilities Approach,Monday,18:30 - 21:30
18795,MORS-460-0,Leading and Managing Teams,Monday,18:30 - 21:30
19392,MORS-460-0,Leading and Managing Teams,Monday,18:30 - 21:30
19051,MORS-934-0,Managing in Professional Service Firms,Monday,18:30 - 21:30
19393,MORS-934-0,Managing in Professional Service Firms,Monday,18:30 - 21:30
19346,MORS-952-A,"Entrepreneurship: Building Innovation, Teams, and Cultures",Monday,18:30 - 21:30
19396,MORS-952-A,"Entrepreneurship: Building Innovation, Teams, and Cultures",Monday,18:30 - 21:30
18616,ACCT-430-0,Accounting For Decision Making,Monday-Thursday,08:30 - 10:00
18895,DECS-431-0,Business Analytics II,Monday-Thursday,08:30 - 10:00
18630,FINC-440-0,Accelerated Corporate Finance,Monday-Thursday,08:30 - 10:00
18643,FINC-441-0,Finance II,Monday-Thursday,08:30 - 10:00
18618,MKTG-430-0,Marketing Management,Monday-Thursday,08:30 - 10:00
18692,MKTG-466-0,Marketing Strategy,Monday-Thursday,08:30 - 10:00
18812,MORS-452-0,Leading the Strategic Change Process,Monday-Thursday,08:30 - 10:00
18759,MORS-474-0,Cross-Cultural Negotiation,Monday-Thursday,08:30 - 10:00
18620,ACCT-430-0,Accounting For Decision Making,Monday-Thursday,10:30 - 12:00
18623,ACCT-430-0,Accounting For Decision Making,Monday-Thursday,10:30 - 12:00
18745,ACCT-451-0,Financial Reporting and Analysis,Monday-Thursday,10:30 - 12:00
18617,FINC-430-0,Finance I,Monday-Thursday,10:30 - 12:00
18634,FINC-440-0,Accelerated Corporate Finance,Monday-Thursday,10:30 - 12:00
18647,FINC-441-0,Finance II,Monday-Thursday,10:30 - 12:00
19060,MGMT-463-0,Technology and Innovation Strategy,Monday-Thursday,10:30 - 12:00
18631,MKTG-430-0,Marketing Management,Monday-Thursday,10:30 - 12:00
18693,MKTG-466-0,Marketing Strategy,Monday-Thursday,10:30 - 12:00
18821,MORS-452-0,Leading the Strategic Change Process,Monday-Thursday,10:30 - 12:00
18761,MORS-474-0,Cross-Cultural Negotiation,Monday-Thursday,10:30 - 12:00
18622,OPNS-455-0,Supply Chain Management,Monday-Thursday,10:30 - 12:00
18626,ACCT-430-0,Accounting For Decision Making,Monday-Thursday,13:30 - 15:00
18632,ACCT-430-0,Accounting For Decision Making,Monday-Thursday,13:30 - 15:00
18736,ACCT-434-0,Turbo Accounting,Monday-Thursday,13:30 - 15:00
18746,ACCT-444-0,Financial Planning For Mergers and Acquisitions,Monday-Thursday,13:30 - 15:00
18885,DECS-431-0,Business Analytics II,Monday-Thursday,13:30 - 15:00
18876,DECS-450-0,Decision Making & Modeling,Monday-Thursday,13:30 - 15:00
18667,FINC-470-0,International Finance,Monday-Thursday,13:30 - 15:00
18993,MGMT-452-0,Strategy & Organization,Monday-Thursday,13:30 - 15:00
18636,MKTG-430-0,Marketing Management,Monday-Thursday,13:30 - 15:00
18748,MKTG-450-0,Marketing Research,Monday-Thursday,13:30 - 15:00
18766,MKTG-462-0,"Retail Analytics, Pricing and Promotion",Monday-Thursday,13:30 - 15:00
18807,MORS-449-A,Writing in Organizations,Monday-Thursday,13:30 - 15:00
18628,ACCT-430-0,Accounting For Decision Making,Monday-Thursday,15:30 - 17:00
18635,ACCT-430-0,Accounting For Decision Making,Monday-Thursday,15:30 - 17:00
18738,ACCT-434-0,Turbo Accounting,Monday-Thursday,15:30 - 17:00
18892,DECS-431-0,Business Analytics II,Monday-Thursday,15:30 - 17:00
18877,DECS-450-0,Decision Making & Modeling,Monday-Thursday,15:30 - 17:00
18668,FINC-470-0,International Finance,Monday-Thursday,15:30 - 17:00
19009,MGMT-452-0,Strategy & Organization,Monday-Thursday,15:30 - 17:00
18639,MKTG-430-0,Marketing Management,Monday-Thursday,15:30 - 17:00
18749,MKTG-450-0,Marketing Research,Monday-Thursday,15:30 - 17:00
18615,OPNS-440-0,Designing and Managing Business Processes,Monday-Thursday,15:30 - 17:00
18535,KPPI-440-A,Leadership and Crisis Management,Monday-Friday,09:00 - 12:00
18539,KPPI-440-A,Leadership and Crisis Management,Monday-Friday,09:00 - 12:00
18540,KPPI-440-A,Leadership and Crisis Management,Monday-Friday,09:00 - 12:00
18543,KPPI-440-A,Leadership and Crisis Management,Monday-Friday,09:00 - 12:00
18544,KPPI-440-A,Leadership and Crisis Management,Monday-Friday,09:00 - 12:00
18627,MORS-430-0,Leadership in Organizations,Monday-Friday,09:30 - 12:30
18633,MORS-430-0,Leadership in Organizations,Monday-Friday,09:30 - 12:30
18638,MORS-430-0,Leadership in Organizations,Monday-Friday,09:30 - 12:30
18641,MORS-430-0,Leadership in Organizations,Monday-Friday,09:30 - 12:30
18633,MORS-430-0,Leadership in Organizations,Monday-Friday,09:30 - 12:30
18536,KPPI-440-A,Leadership and Crisis Management,Monday-Friday,13:30 - 16:30
18537,KPPI-440-A,Leadership and Crisis Management,Monday-Friday,13:30 - 16:30
18541,KPPI-440-A,Leadership and Crisis Management,Monday-Friday,13:30 - 16:30
18542,KPPI-440-A,Leadership and Crisis Management,Monday-Friday,13:30 - 16:30
18545,KPPI-440-A,Leadership and Crisis Management,Monday-Friday,13:30 - 16:30
18645,MORS-430-0,Leadership in Organizations,Monday-Friday,14:00 - 17:00
18648,MORS-430-0,Leadership in Organizations,Monday-Friday,14:00 - 17:00
18650,MORS-430-0,Leadership in Organizations,Monday-Friday,14:00 - 17:00
18654,MORS-430-0,Leadership in Organizations,Monday-Friday,14:00 - 17:00
18648,MORS-430-0,Leadership in Organizations,Monday-Friday,14:00 - 17:00
18845,DECS-430-A,Business Analytics I,Tuesday,09:30 - 12:30
18846,DECS-430-A,Business Analytics I,Tuesday,09:30 - 12:30
18848,DECS-430-A,Business Analytics I,Tuesday,09:30 - 12:30
18850,DECS-430-A,Business Analytics I,Tuesday,09:30 - 12:30
18633,MORS-430-0,Leadership in Organizations,Tuesday,09:30 - 12:30
18442,KIEI-462-0,New Venture Discovery,Tuesday,13:30 - 16:45
19081,KIEI-940-0,New Venture Development,Tuesday,13:30 - 16:45
18844,DECS-430-A,Business Analytics I,Tuesday,14:00 - 17:00
18847,DECS-430-A,Business Analytics I,Tuesday,14:00 - 17:00
18849,DECS-430-A,Business Analytics I,Tuesday,14:00 - 17:00
18851,DECS-430-A,Business Analytics I,Tuesday,14:00 - 17:00
18648,MORS-430-0,Leadership in Organizations,Tuesday,14:00 - 17:00
18653,ACCT-430-0,Accounting For Decision Making,Tuesday,18:00 - 21:00
18905,DECS-430-A,Business Analytics I,Tuesday,18:00 - 21:00
18625,FINC-430-0,Finance I,Tuesday,18:00 - 21:00
18669,FINC-470-0,International Finance,Tuesday,18:00 - 21:00
19012,MGMT-452-0,Strategy & Organization,Tuesday,18:00 - 21:00
18772,MKTG-454-0,Advertising Strategy,Tuesday,18:00 - 21:00
19269,MKTG-466-0,Marketing Strategy,Tuesday,18:00 - 21:00
18771,MORS-468-0,Managerial Leadership (formerly MGMT-468-0),Tuesday,18:00 - 21:00
19059,MORS-952-A,"Entrepreneurship: Building Innovation, Teams, and Cultures",Tuesday,18:00 - 21:00
18751,ACCT-452-0,Financial Reporting and Analysis II,Tuesday,18:30 - 21:30
19088,KIEI-462-0,New Venture Discovery,Tuesday,18:30 - 21:30
19388,KIEI-462-0,New Venture Discovery,Tuesday,18:30 - 21:30
19015,MGMT-931-A,Strategic Franchising,Tuesday,18:30 - 21:30
19067,MKTG-451-0,Marketing Channel Strategies,Tuesday,18:30 - 21:30
19389,MKTG-451-0,Marketing Channel Strategies,Tuesday,18:30 - 21:30
18754,MKTG-962-A,Entrepreneurial Selling: Business to Business,Tuesday,18:30 - 21:30
19391,MKTG-962-A,Entrepreneurial Selling: Business to Business,Tuesday,18:30 - 21:30
19052,MORS-950-0,Frameworks to Insights: Problem Solving in Fast Forward,Tuesday,18:30 - 21:30
19395,MORS-950-0,Frameworks to Insights: Problem Solving in Fast Forward,Tuesday,18:30 - 21:30
19242,REAL-443-A,Urban Economic Development and Real Estate Market Analysis,Tuesday,18:30 - 21:30
18675,FINC-944-B,Investment Banking Recruiting Prep,Tuesday,18:30 - 21:30
18604,HEMA-911-B,Healthcare Bootcamp,Tuesday,18:30 - 21:30
18753,MKTG-961-B,Entrepreneurial Tools for Digital Marketing,Tuesday,18:30 - 21:30
19390,MKTG-961-B,Entrepreneurial Tools for Digital Marketing,Tuesday,18:30 - 21:30
19241,REAL-444-B,Real Estate Development,Tuesday,18:30 - 21:30
18894,DECS-431-0,Business Analytics II,Tuesday-Friday,08:30 - 10:00
18619,FINC-430-0,Finance I,Tuesday-Friday,08:30 - 10:00
18659,FINC-442-0,Financial Decisions,Tuesday-Friday,08:30 - 10:00
18962,MGMT-431-0,Business Strategy,Tuesday-Friday,08:30 - 10:00
18965,MGMT-431-0,Business Strategy,Tuesday-Friday,08:30 - 10:00
18642,MKTG-430-0,Marketing Management,Tuesday-Friday,08:30 - 10:00
18755,MORS-470-0,Negotiations,Tuesday-Friday,08:30 - 10:00
18887,DECS-431-0,Business Analytics II,Tuesday-Friday,10:30 - 12:00
18621,FINC-430-0,Finance I,Tuesday-Friday,10:30 - 12:00
18660,FINC-442-0,Financial Decisions,Tuesday-Friday,10:30 - 12:00
18963,MGMT-431-0,Business Strategy,Tuesday-Friday,10:30 - 12:00
18966,MGMT-431-0,Business Strategy,Tuesday-Friday,10:30 - 12:00
19061,MGMT-466-0,International Business Strategy in Non-Market Environments,Tuesday-Friday,10:30 - 12:00
18646,MKTG-430-0,Marketing Management,Tuesday-Friday,10:30 - 12:00
19361,MORS-937-A,Leader as Coach,Tuesday-Friday,10:30 - 12:00
18629,OPNS-450-0,Analytical Decision Modeling,Tuesday-Friday,10:30 - 12:00
18888,DECS-431-0,Business Analytics II,Tuesday-Friday,13:30 - 15:00
18890,DECS-431-0,Business Analytics II,Tuesday-Friday,13:30 - 15:00
19040,FINC-454-0,Real Estate Finance and Investments,Tuesday-Friday,13:30 - 15:00
18666,FINC-465-0,Derivatives Markets I,Tuesday-Friday,13:30 - 15:00
19086,MECN-450-0,Macroeconomics,Tuesday-Friday,13:30 - 15:00
18967,MGMT-431-0,Business Strategy,Tuesday-Friday,13:30 - 15:00
18969,MGMT-431-0,Business Strategy,Tuesday-Friday,13:30 - 15:00
18971,MGMT-431-0,Business Strategy,Tuesday-Friday,13:30 - 15:00
18750,MKTG-454-0,Advertising Strategy,Tuesday-Friday,13:30 - 15:00
18897,DECS-431-0,Business Analytics II,Tuesday-Friday,15:30 - 17:00
18968,MGMT-431-0,Business Strategy,Tuesday-Friday,15:30 - 17:00
18970,MGMT-431-0,Business Strategy,Tuesday-Friday,15:30 - 17:00
19062,MGMT-466-0,International Business Strategy in Non-Market Environments,Tuesday-Friday,15:30 - 17:00
18674,FINC-939-0,Buyout Lab (B-Lab),Wednesday,08:00 - 09:30
18845,DECS-430-A,Business Analytics I,Wednesday,09:30 - 12:30
18846,DECS-430-A,Business Analytics I,Wednesday,09:30 - 12:30
18848,DECS-430-A,Business Analytics I,Wednesday,09:30 - 12:30
18850,DECS-430-A,Business Analytics I,Wednesday,09:30 - 12:30
18672,FINC-915-0,Venture Lab (V-Lab),Wednesday,10:30 - 12:00
19085,DECS-915-0,Analytical Consulting Lab (ACL),Wednesday,11:00 - 12:30
18898,DECS-920-0,Risk Lab,Wednesday,13:30 - 15:00
18844,DECS-430-A,Business Analytics I,Wednesday,14:00 - 17:00
18847,DECS-430-A,Business Analytics I,Wednesday,14:00 - 17:00
18849,DECS-430-A,Business Analytics I,Wednesday,14:00 - 17:00
18851,DECS-430-A,Business Analytics I,Wednesday,14:00 - 17:00
19227,BLAW-925-0,Mergers and Acquisitions: The Art of the Deal,Wednesday,15:15 - 18:15
18552,KPPI-471-0,Advanced Board Governance,Wednesday,16:15 - 18:15
18553,KPPI-471-C,Advanced Board Governance (Half-Credit),Wednesday,16:15 - 18:15
18554,KPPI-471-N,Advanced Board Governance (non-credit),Wednesday,16:15 - 18:15
18552,KPPI-471-0,Advanced Board Governance,Wednesday,16:15 - 18:15
18553,KPPI-471-C,Advanced Board Governance (Half-Credit),Wednesday,16:15 - 18:15
18554,KPPI-471-N,Advanced Board Governance (non-credit),Wednesday,16:15 - 18:15
18552,KPPI-471-0,Advanced Board Governance,Wednesday,16:15 - 18:15
18553,KPPI-471-C,Advanced Board Governance (Half-Credit),Wednesday,16:15 - 18:15
18554,KPPI-471-N,Advanced Board Governance (non-credit),Wednesday,16:15 - 18:15
18552,KPPI-471-0,Advanced Board Governance,Wednesday,16:15 - 18:15
18553,KPPI-471-C,Advanced Board Governance (Half-Credit),Wednesday,16:15 - 18:15
18554,KPPI-471-N,Advanced Board Governance (non-credit),Wednesday,16:15 - 18:15
19231,BLAW-435-0,Business Law I,Wednesday,18:00 - 21:00
18605,HEMA-915-0,NUvention: Medical  Innovation I,Wednesday,18:00 - 21:00
18444,KIEI-462-0,New Venture Discovery,Wednesday,18:00 - 21:00
18658,KPPI-455-A,Board Governance of Non-Profit Organizations,Wednesday,18:00 - 21:00
18658,KPPI-455-A,Board Governance of Non-Profit Organizations,Wednesday,18:00 - 21:00
18940,MECN-430-0,Microeconomic Analysis,Wednesday,18:00 - 21:00
18976,MGMT-431-0,Business Strategy,Wednesday,18:00 - 21:00
19063,MGMT-466-0,International Business Strategy in Non-Market Environments,Wednesday,18:00 - 21:00
18770,MKTG-450-0,Marketing Research,Wednesday,18:00 - 21:00
19236,REAL-447-0,Legal Issues In Real Estate,Wednesday,18:00 - 21:00
18673,FINC-934-0,Asset Management Practicum II,Wednesday,18:30 - 21:30
18551,KPPI-441-0,Strategic Management in Non-Market Environments,Wednesday,18:30 - 21:30
18757,MKTG-458-0,Consumer Insight for Brand Strategy,Wednesday,18:30 - 21:30
18780,MORS-468-0,Managerial Leadership (formerly MGMT-468-0),Wednesday,18:30 - 21:30
19299,MORS-942-B,"Leadership: Power, Politics, and Talk",Wednesday,18:30 - 21:30
19394,MORS-942-B,"Leadership: Power, Politics, and Talk",Wednesday,18:30 - 21:30
18845,DECS-430-A,Business Analytics I,Thursday,09:30 - 12:30
18846,DECS-430-A,Business Analytics I,Thursday,09:30 - 12:30
18848,DECS-430-A,Business Analytics I,Thursday,09:30 - 12:30
18850,DECS-430-A,Business Analytics I,Thursday,09:30 - 12:30
19054,MORS-935-C,Personal Leadership Insights,Thursday,10:10 - 12:00
18844,DECS-430-A,Business Analytics I,Thursday,14:00 - 17:00
18847,DECS-430-A,Business Analytics I,Thursday,14:00 - 17:00
18849,DECS-430-A,Business Analytics I,Thursday,14:00 - 17:00
18851,DECS-430-A,Business Analytics I,Thursday,14:00 - 17:00
18655,ACCT-430-0,Accounting For Decision Making,Thursday,18:00 - 21:00
18881,DECS-431-0,Business Analytics II,Thursday,18:00 - 21:00
18640,FINC-441-0,Finance II,Thursday,18:00 - 21:00
18665,FINC-465-0,Derivatives Markets I,Thursday,18:00 - 21:00
18451,KIEI-903-0,Corporate Innovation and New Ventures,Thursday,18:00 - 21:00
18451,KIEI-903-0,Corporate Innovation and New Ventures,Thursday,18:00 - 21:00
19398,KIEI-903-0,Corporate Innovation and New Ventures,Thursday,18:00 - 21:00
19398,KIEI-903-0,Corporate Innovation and New Ventures,Thursday,18:00 - 21:00
18550,KPPI-441-0,Strategic Management in Non-Market Environments,Thursday,18:00 - 21:00
19087,MECN-450-0,Macroeconomics,Thursday,18:00 - 21:00
18769,MKTG-462-0,"Retail Analytics, Pricing and Promotion",Thursday,18:00 - 21:00
18793,MORS-430-0,Leadership in Organizations,Thursday,18:00 - 21:00
18644,OPNS-430-0,Operations Management,Thursday,18:00 - 21:00
19228,BLAW-911-B,Business Law for Entrepreneurs,Thursday,18:00 - 21:00
19230,BLAW-435-0,Business Law I,Thursday,18:30 - 21:30
19229,BLAW-911-A,Business Law for Entrepreneurs,Thursday,18:30 - 21:30
19039,FINC-445-0,Entrepreneurial Finance and Venture Capital,Thursday,18:30 - 21:30
19387,FINC-445-0,Entrepreneurial Finance and Venture Capital,Thursday,18:30 - 21:30
18603,HEMA-484-A,Thought Leadership Seminar: Critical Decisions in International Healthcare Systems,Thursday,18:30 - 21:30
19317,KIEI-452-0,Social Entrepreneurship,Thursday,18:30 - 21:30
19368,MORS-454-0,Creating and Managing Strategic Alliances,Thursday,18:30 - 21:30
18612,HEMA-923-B,Topics in Health Policy: The Affordable Care Act,Thursday,18:30 - 21:30
18612,HEMA-923-B,Topics in Health Policy: The Affordable Care Act,Thursday,18:30 - 21:30
18845,DECS-430-A,Business Analytics I,Friday,09:30 - 12:30
18846,DECS-430-A,Business Analytics I,Friday,09:30 - 12:30
18848,DECS-430-A,Business Analytics I,Friday,09:30 - 12:30
18850,DECS-430-A,Business Analytics I,Friday,09:30 - 12:30
18443,KIEI-462-0,New Venture Discovery,Friday,13:30 - 16:45
19365,KIEI-924-A,Introduction to Software Development,Friday,13:30 - 16:45
18844,DECS-430-A,Business Analytics I,Friday,14:00 - 17:00
18847,DECS-430-A,Business Analytics I,Friday,14:00 - 17:00
18849,DECS-430-A,Business Analytics I,Friday,14:00 - 17:00
18851,DECS-430-A,Business Analytics I,Friday,14:00 - 17:00
18546,KPPI-440-A,Leadership and Crisis Management,Friday,18:00 - 21:00
18546,KPPI-440-A,Leadership and Crisis Management,Friday,18:00 - 21:00
18547,KPPI-440-A,Leadership and Crisis Management,Friday,18:00 - 21:00
18547,KPPI-440-A,Leadership and Crisis Management,Friday,18:00 - 21:00
18445,KIEI-462-0,New Venture Discovery,Saturday,09:00 - 12:00
18979,MGMT-431-0,Business Strategy,Saturday,09:00 - 12:00
19068,MKTG-953-0,Customer Analytics,Saturday,09:00 - 12:00
18651,OPNS-430-0,Operations Management,Saturday,09:00 - 12:00
18546,KPPI-440-A,Leadership and Crisis Management,Saturday,09:00 - 16:00
18546,KPPI-440-A,Leadership and Crisis Management,Saturday,09:00 - 12:00
18547,KPPI-440-A,Leadership and Crisis Management,Saturday,09:00 - 16:00
18547,KPPI-440-A,Leadership and Crisis Management,Saturday,09:00 - 12:00
18906,DECS-430-A,Business Analytics I,Saturday,13:30 - 16:30
18649,FINC-441-0,Finance II,Saturday,13:30 - 16:30
18843,MORS-454-0,Creating and Managing Strategic Alliances,Saturday,13:30 - 16:30
18548,KPPI-440-A,Leadership and Crisis Management,Sunday,08:30 - 17:00
18548,KPPI-440-A,Leadership and Crisis Management,Sunday,08:30 - 17:00';

//**********************************************************************************

$arr_new_classes = explode("\n", $new_classes);

echo 'Adding classes...<br>';

$school = 'kellogg.northwestern.edu';
$period = 'fall2014';
$debug_only = false;

if($debug_only == true) {
	$debug_iterator = 0;
	$debug_iterations = 4;
}

foreach($arr_new_classes as $index => $new_class) {
	if(($debug_only == false) || ($debug_iterator < $debug_iterations)) {
		//these should probably go in the spreadsheet eventually
		$prof_email = '';

		
		//from spreadsheet	
		$arr_new_class = explode(',', $new_class);
		$course_id = $arr_new_class[0];
		$course_name = $arr_new_class[1]; //note this is the kellogg course id (e.g. MGMT-466). In the future we may want to use $arr_new_class[2], the course name
		$course_days = $arr_new_class[3];
		$course_time = $arr_new_class[4];
		
		//so we know script is running...
		if($debug_only != true) {
			echo $new_class.'<br>';
			flush();
		}

		//**********
		//get period start and end dates from db
		//**********
		$spstdt = 0;
		$spendt = 0;

		$spscid = $school;
		$sppdid = $period;
		include('db_school_periods_selectStartAndEndDates.php');

		//**********
		//basic info (from string)
		//**********
		//sanitize abbreviated days
		$course_days = str_replace('Mon', 'Monday', $course_days);
		$course_days = str_replace('dayday', 'day', $course_days);
		
		$course_days = str_replace('Tue', 'Tuesday', $course_days);
		$course_days = str_replace('sdaysday', 'sday', $course_days);
		
		$course_days = str_replace('Wed', 'Wednesday', $course_days);
		$course_days = str_replace('nesdaynesday', 'nesday', $course_days);
		
		$course_days = str_replace('Thu', 'Thursday', $course_days);
		$course_days = str_replace('rsdayrsday', 'rsday', $course_days);
		
		$course_days = str_replace('Fri', 'Friday', $course_days);
		$course_days = str_replace('dayday', 'day', $course_days);
		
		$course_days = str_replace('Sat', 'Saturday', $course_days);
		$course_days = str_replace('urdayurday', 'urday', $course_days);
		
		$course_days = str_replace('Sun', 'Sunday', $course_days);
		$course_days = str_replace('dayday', 'day', $course_days);
		
		//split days into array(could be mutliple meetings);
		$arr_course_days = explode('-', $course_days);
		
		//sanitize time
		$course_time = substr($course_time,0,5);
		$course_time .= ':00';
		$course_time = str_replace(':', '', $course_time);
		
		//sanitize course name (this is only necessary for the when we use the kellogg course id. if we switch to title it will be unnecessary)
		$last_dash_pos = strrpos($course_name, '-');
		$course_name = substr($course_name, 0, $last_dash_pos);
		$course_name = str_replace('-', ' ', $course_name);

		//**********
		//course_info
		//**********
		//check if we have this course already
		//insert if we dont
		$valid_course = '';
		
		$ciscid = $school;
		$cipdid = $period;
		$cicrid = $course_id;
		include('db_course_info_selectValidateCourse.php');
		
		if($valid_course == '') {
			$ciscid = $school;
			$cipdid = $period;
			$cicrid = $course_id;
			$cicrnm = $course_name;
			$ciprem = $prof_email;
			$ciabal = 2;
			if($debug_only == true) {
				echo 'COURSE INFO: '.$ciscid.' - '.$cipdid.' - '.$cicrid.' - '.$cicrnm.' - '.$ciprem.' - '.$ciabal.'<br>';
			}
			else {
				include('db_course_info_insertCourse.php');	
			}
		}
		
		//**********
		//course_dates
		//**********
		$dateCCYYMMDD = $spstdt;
		while($dateCCYYMMDD <= $spendt) {
			$day = date('l', make_unix_date($dateCCYYMMDD));
			if(in_array($day, $arr_course_days)) {
				//check if we have this date already
				//insert if we dont

				$valid_course_date = '';
				
				$cdscid = $school;
				$cdpdid = $period;
				$cdcrid = $course_id;
				$cddate = $dateCCYYMMDD;
				include('db_course_dates_selectValidateCourseDate.php');
		
				if($valid_course_date == '') {
					$cdscid = $school;
					$cdpdid = $period;
					$cdcrid = $course_id;
					$cddate = $dateCCYYMMDD;
					$cdtime = $course_time;
					if($debug_only == true) {
						echo 'COURSE DATE: '.$cdscid.' - '.$cdpdid.' - '.$cdcrid.' - '.$cddate.' - '.$cdtime.'<br>';
					}
					else {
						include('db_course_dates_insertDate.php');	
					}
				}
			}
			
			//increment 1 day forward
			$dateCCYYMMDD = date('Ymd', mktime(12, 0, 0, substr($dateCCYYMMDD,4,2), substr($dateCCYYMMDD,6,2) + 1, substr($dateCCYYMMDD,0,4)));
		}
		

		//**********
		//student info
		//**********
		//get students
		$parm_cms_id = $course_id;
		include('fn_get_students_for_course.php');
		$arr_students = $parm_arr_students;
		foreach($arr_students as $student_name => $arr_student) {
			if($debug_only != true) {
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				echo ucwords($student_name).'<br>';
			}
			
			$img = '';
			$email = '';
			
			if(isset($arr_student['img'])) {		
				$img = $arr_student['img'];
			}
			else {
				if($debug_only != true) {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo 'No img<br>';
				}
			}
			
			if(isset($arr_student['email'])) {
				$email = $arr_student['email'];
			}
			else {
				if($debug_only != true) {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo 'No email<br>';
				}
			}
			
			flush();
			
			//**********
			//users
			//**********
			//check if we have this user_id already
			//insert if we dont
			$valid_user = '';

			$usemal = $email;
			include('db_users_selectValidateUser.php');

			if($valid_user == '') {
				$usemal = $email;
				$usnid = '';
				$uspw = '';
				$usscid = $school;
				$usname = ucwords($student_name);
				$usimg = $img;
				if($debug_only == true) {
					echo 'USER: '.$usemal.' - '.$usnid.' - '.$usnpw.' - '.$usscid.' - '.$usname.' - '.$usimg.'<br>';
				}
				else {
					include('db_users_insertUser.php');
				}
			}
			
				
			//**********
			//course_students
			//**********
			//check if we have student in this course already
			//insert if we dont
			$valid_course_student = '';

			$csscid = $school;
			$cspdid = $period;
			$cscrid = $course_id;
			$csemal = $email;
			include('db_course_students_selectValidateCourseStudent.php');

			if($valid_course_student == '') {
				$csscid = $school;
				$cspdid = $period;
				$cscrid = $course_id;
				$csemal = $email;
				if($debug_only == true) {
					echo 'COURSE STUDENT: '.$csscid.' - '.$cspdid.' - '.$cscrid.' - '.$csemal.'<br>';
				}
				else {
					include('db_course_students_insertStudent.php');
				}
			}
		}

		$debug_iterator++;
	}
}

//**********************************************************************************

include('zz_dbclose.php');

?>