<?php
if (!defined("ALLOW_INCLUDE"))	die('Access forbidden');

//AUTOLOGIN
if (!isset($_SESSION['user_login'])) {
	if(isset($_COOKIE['autologin_key'])) {
		parse_str($_COOKIE['autologin_key']); //user //key
		$con = connect_database();
		if ($con != false) {
			$query = "SELECT COUNT(*) FROM `ms_autologin` WHERE autologin_user='$user' AND autologin_key='$key'";
			$result = mysqli_query($con,$query);
			$rowcount = mysqli_num_rows($result);
			if ($rowcount == 1) {
				$_SESSION['user_login'] = $user;
				set_user_last_login($_SESSION['user_login']);
			}
		}
	}
}

//SET THE LANGUAGES
if (!isset($_SESSION['language']) || @$_SESSION['language'] == '' || !in_array(@$_SESSION['language'], array_keys(get_lang_array()))) {
	set_active_lang();
}

//SET THE TIMEZONE
date_default_timezone_set('UTC'); 
$_SESSION['timezone'] = get_lang_info('lang_timezone',$_SESSION['language']);
if (isset($_SESSION['user_login']) && get_user_info('user_timezone') != 'default') {
	$_SESSION['timezone'] = get_user_info('user_timezone');
}

//LOAD LANGUAGE FILE
include($_SERVER['DOCUMENT_ROOT'].PATH.'/languages/'.get_lang_info('lang_file',$_SESSION['language']));
?>