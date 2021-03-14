<?php
//if(!defined("ALLOW_INCLUDE"))	die('Access forbidden'); //KOMENTARZ PONIEWAZ JAVASCRIPT MUSI MIEC DOSTEP
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');

$function = @$_REQUEST["function"];

switch($function) {
	case 'check_user_login': 
		$string = @$_REQUEST["string"];
		echo check_user_login($string);
		break;
	case 'check_user_password':
		$string = @$_REQUEST["string"];
		list($percent,$text) = password_strength($string);
		echo $percent;
		break;
	case 'hidden_cookies_panel':
		hidden_cookies_panel();
		break;
	case 'set_language_cookie':
		$lang = @$_REQUEST["lang"];
		setLanguageCookie($lang);
		break;
	case '':
		echo 'ERROR';
		break;
	default:
		echo 'ERROR';
		break;
}

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
