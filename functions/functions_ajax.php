<?php
if(!defined("ALLOW_INCLUDE"))	die('Access forbidden');

function check_user_login($string) {
	$con = connect_database();
	if (!$con) return 'Błąd połączenia z bazą danych.';
	$query = "SELECT COUNT(*) FROM `ms_users` WHERE user_login='$string'";
	$result = mysqli_query($con,$query);
	$ilosclogin  = mysqli_fetch_row($result);
	if ($ilosclogin[0]==0) {
		echo __('Login dostępny');
	}
	if ($ilosclogin[0]!=0) {
		echo __('Login zajęty');
	}
}

function hidden_cookies_panel() {
	$cookie_time = (3600 * 24 * 30 * 12); // 1 year
	setcookie ('cookies_panel_accept', 1, time() + $cookie_time, "/");
}

function setLanguageCookie($lang) {
	$cookie_time = (3600 * 24 * 30 * 12); // 1 year
	setcookie ('language', 'lang='.$lang, time() + $cookie_time,"/");
	$_SESSION['language'] = '';
	if (isset($_SESSION['user_login'])) {
		$query = "UPDATE ms_users SET user_language='$lang' WHERE user_login='".$_SESSION['user_login']."';";
		$con = @connect_database();
		mysqli_query($con,$query);
		echo $lang;
	}
}
?>
