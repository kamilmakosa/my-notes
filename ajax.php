<?php
define("ALLOW_INCLUDE", "yes");
include('bootstrapper.php');
include('functions/functions_ajax.php');

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