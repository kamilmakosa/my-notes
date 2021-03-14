<?php
if(!defined("ALLOW_INCLUDE"))	die('Access forbidden');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/config_db.php');

function __($text, $lang = '', $type = '') {
	return translate($text, $lang, $type);
}

function alert($status,$text) {
	$alert = '<div class="alert" data-type="'.$status.'">';
	$alert .= '<span onclick="this.parentElement.style.display=\'none\'" class="alert_close">×</span>';
	$alert .= '<h3>'.ucfirst(__($status)).'!'.'</h3>';
	$alert .= '<p>'.$text.'</p>';
	$alert .= '</div>';
	return $alert;
}

function check_access($logged = true) {
	if ($logged == true) {
		if (!isset($_SESSION['user_login'])) {
			echo "\t"."\t".alert('danger','Brak dostępu');
			echo "\t".'</div>'."\n";
			include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
			exit;
		}
	}
	if ($logged == false) {
		if (isset($_SESSION['user_login'])) {
			echo "\t"."\t".alert('danger','Brak dostępu');
			echo "\t".'</div>'."\n";
			include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
			exit;
		}
	}
}

function check_PIN($pin) {
	$alert = '';

	if ($pin != NULL) {
		if (!preg_match("/^[0-9]*$/",$pin)) {
			$alert .= __('PIN może składać się tylko z cyfr.');
		}
		if (!(strlen($pin)==6)) {
			$alert .= __('PIN musi mieć 6 znaków.');
		}
	}

	$alert = str_replace(".",".<br>",$alert);
	if (!empty($alert)) {
		$alert = substr($alert, 0, strlen($alert)-4);
	}
	if (empty($alert)) {
		$alert = true;
	}
	return $alert;
}

function connect_database() {
// This function returns an object representing the connection to the MySQL server
	global $db_host;
	global $db_user;
	global $db_pass;
	global $db_name;

	mysqli_report(MYSQLI_REPORT_STRICT);

	try {
		$con = new mysqli($db_host, $db_user, $db_pass, $db_name);
	} catch (Exception $exception) {
		echo 'Caught exception: [',$exception->getCode(), '] ',  $exception->getMessage(), "\n";
		exit;
	}

	mysqli_query($con,"SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
	return $con;
}

function convert_datetime($from, $to, $datetime) {
	if (strpos($to,'UTC') !== false && $to != 'UTC') {
		$to = preg_replace('/[A-Za-z+]/', '', $to);
		if ($to == 0) {
			// 0
			$to = 'UTC';
		} elseif (strpos($to,'-') !== false) {
			// -
			$to = str_replace('-', '', $to);
			$to = str_replace(array('.25','.5','.75'), array(':15',':30',':45'), $to);
			if(strpos($to,':') !== false) {
				//$to = explode(':',$to);
				list($h,$m) = explode(':',$to);
			} else {
				$h = preg_replace('/\-/', '', $to);
				$m = 0;
			}
			$newtime = -($h*60*60+$m*60);
			$dateTS = date_timestamp_get(date_create($datetime));
			$dateTS += $newtime;
			$date = date_create();
			date_timestamp_set($date, $dateTS);
			return date_format($date, 'Y-m-d H:i:s');
		} else {
			// +
			$to = str_replace(array('.25','.5','.75'), array(':15',':30',':45'), $to);
			if(strpos($to,':') !== false) {
				$to = explode(':',$to);
				list($h,$m) = $to;
			} else {
				$h = preg_replace('/\-/', '', $to);
				$m = 0;
			}
			$newtime = $h*60*60+$m*60;
			$dateTS = date_timestamp_get(date_create($datetime));
			$dateTS += $newtime;
			$date = date_create();
			date_timestamp_set($date, $dateTS);
			return date_format($date, 'Y-m-d H:i:s');
		}
	}

	$date = date_create($datetime, timezone_open($from));
	date_timezone_set($date, timezone_open($to));
	return date_format($date, 'Y-m-d H:i');
}

function get_datetime($timezone = '') {
	date_default_timezone_set('UTC');
	if ($timezone == '') {
		$timezone = $_SESSION['timezone'];
	}

	// Manual choice - UTC+-
	if (strpos($timezone,'UTC') !== false && $timezone != 'UTC') {
		$timezone = preg_replace('/[A-Za-z+]/', '', $timezone);
		if ($timezone == 0) {
			// 0
			$timezone = 'UTC';
		} elseif (strpos($timezone,'-') !== false) {
			// -
			$timezone = str_replace('-', '', $timezone);
			$timezone = str_replace(array('.25','.5','.75'), array(':15',':30',':45'), $timezone);
			if(strpos($timezone,':') !== false) {
				//$timezone = explode(':',$timezone);
				list($h,$m) = explode(':',$timezone);
			} else {
				$h = preg_replace('/\-/', '', $timezone);
				$m = 0;
			}
			$newtime = -($h*60*60+$m*60);
			$date = date_create(date('Y-m-d H:i:s',time()+$newtime), timezone_open('UTC'));
			return date_format($date, 'Y-m-d H:i:s');
		} else {
			// +
			$timezone = str_replace(array('.25','.5','.75'), array(':15',':30',':45'), $timezone);
			if(strpos($timezone,':') !== false) {
				$timezone = explode(':',$timezone);
				list($h,$m) = $timezone;
			} else {
				$h = preg_replace('/\-/', '', $timezone);
				$m = 0;
			}
			$newtime = $h*60*60+$m*60;
			$date = date_create(date('Y-m-d H:i:s',time()+$newtime), timezone_open('UTC'));
			return date_format($date, 'Y-m-d H:i:s');
		}
	}

	$date = date_create(date('Y-m-d H:i:s'), timezone_open('UTC'));
	date_timezone_set($date, timezone_open($timezone));
	return date_format($date, 'Y-m-d H:i:s');
}

function get_datetime_GMT() {
	return gmdate('Y-m-d H:i:s');
}

function get_day($year = '', $month = '', $day = '') {
	if(empty($year)) $year = date("Y");
	if(empty($month)) $month = date("m");
	if(empty($day)) $day = date("d");

	$date_N = date("N",mktime(0,0,0,$month,$day,$year));
	switch ($date_N) {
		case 1: $day = "poniedziałek"; break;
		case 2: $day = "wtorek"; break;
		case 3: $day = "środa"; break;
		case 4: $day = "czwartek"; break;
		case 5: $day = "piątek"; break;
		case 6: $day = "sobota"; break;
		case 7: $day = "niedziela"; break;
		default: $day = false; break;
	}
	return $day;
}

function get_domain($url) {
	if ($url == 'localhost') {
		return 'localhost';
	}
      $urlobj=parse_url($url);
      $domain=$urlobj['host'];
      if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
      }
      return false;
}

function get_lang_array() {
// This function returns an array of languages
	$con = connect_database();
	if ($con === false) {
		return false;
	}
	$query = "SELECT * FROM ms_language WHERE lang_active='1' ORDER BY lang_shortcut ASC";
	$result = mysqli_query($con,$query);
	$rowcount = mysqli_num_rows($result);
	$languageArray = array();

	while ($array = mysqli_fetch_assoc($result)) {
		$languageArray[$array['lang_shortcut']] = array();
			$languageArray[$array['lang_shortcut']]["lang_id"] = $array['lang_id'];
			$languageArray[$array['lang_shortcut']]['lang_name'] = $array['lang_name'];
			$languageArray[$array['lang_shortcut']]['lang_name_en'] = $array['lang_name_en'];
			$languageArray[$array['lang_shortcut']]['lang_shortcut'] = $array['lang_shortcut'];
			$languageArray[$array['lang_shortcut']]['lang_active'] = $array['lang_active'];
			$languageArray[$array['lang_shortcut']]['lang_timezone'] = $array['lang_timezone'];
			$languageArray[$array['lang_shortcut']]['lang_file'] = $array['lang_file'];
	}
	return $languageArray;
}

function get_lang_info($column_name, $shortcut) {
// This function returns an information of languages
	$con = connect_database();
	if ($con === false) {
		return false;
	}
	$query = "SELECT $column_name FROM ms_language WHERE lang_shortcut='$shortcut';";
	$result = mysqli_query($con,$query);
	$row = mysqli_fetch_assoc($result);
	$rowcount = mysqli_num_rows($result);
	if (!mysqli_error($con) && $rowcount === 1) {
		return $row[$column_name];
	}
	return false;
}

function get_lang_list() {
// This function returns a list with the available languages
	$languageArray = get_lang_array();
	while (list($key0,$key1) = each($languageArray)) {
		$active = '';
		if ($key0 == $_SESSION['language']) {
			$active = ' class="active"';
		}
		echo '<li'.$active.' onClick="setLanguage(\''.$key0.'\')"><a><i class="ico-lang-'.$key0.'"></i> '.$key1["lang_name"].'</a></li>'."\n";
	}
}

function get_lang_select() {
// This function returns a select with the available languages
	$languageArray = get_lang_array();
	while (list($key0,$key1) = each($languageArray)) {
		$active = '';
		if ($key0 == get_option('default_language')) {
			$active = " selected=\"selected\"";
		}
		echo '<option value="'.$key0.'" lang="'.$key0.'"'.$active.'>'.$key1["lang_name"].'</option>';
	}
}

function get_option($option) {
	$con = @connect_database();
	if ($con == false ) { return false; }

	$query = "SELECT option_value FROM ms_options WHERE option_name='$option'";
	$result = mysqli_query($con,$query);
	if (!mysqli_error($con)) {
		$array = mysqli_fetch_assoc($result);
		return $array['option_value'];
	}
	return false;
}

function get_timezone_select() {
	$continents = array( 'Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific');

	foreach (timezone_identifiers_list() as $zone) {
		$zone = explode('/', $zone);
		if ( !in_array( $zone[0], $continents ) ) {
			continue;
		}
		$zonen[] = array(
			'continent'   => ( isset($zone[0]) ? $zone[0] : '' ),
			'city'        => ( isset($zone[1]) ? $zone[1] : '' ),
			'subcity'     => ( isset($zone[2]) ? $zone[2] : '' ),
			't_continent' => ( isset($zone[0]) ? str_replace( '_', ' ', $zone[0] ) : '' ),
			't_city'      => ( isset($zone[1]) ? str_replace( '_', ' ', $zone[1] ) : '' ),
			't_subcity'   => ( isset($zone[2]) ? str_replace( '_', ' ', $zone[2] ) : '' )
		);
	}
	$selected_zone = $_SESSION['timezone'];

	foreach ( $zonen as $key => $zone ) {
		// Build value in an array to join later
		$value = array( $zone['continent'] );

		// Continent optgroup
		if ( !isset( $zonen[$key - 1] ) || $zonen[$key - 1]['continent'] !== $zone['continent'] ) {
			$label = $zone['t_continent'];
			$structure[] = '<optgroup label="'.$label.'">';
		}

		// Add the city to the value
		$value[] = $zone['city'];
		$display = $zone['t_city'];
		if ( !empty( $zone['subcity'] ) ) {
			// Add the subcity to the value
			$value[] = $zone['subcity'];
			$display .= ' - ' . $zone['t_subcity'];
		}

		// Build the value
		$value = join( '/', $value );
		$selected = '';
		if ( $value === $selected_zone ) {
			$selected = 'selected="selected" ';
		}
		$structure[] = '<option '.$selected.'value="'.$value.'">'.$display."</option>";

        // Close continent optgroup
        if ( !empty( $zone['city'] ) && ( !isset($zonen[$key + 1]) || (isset( $zonen[$key + 1] ) && $zonen[$key + 1]['continent'] !== $zone['continent']) ) ) {
            $structure[] = '</optgroup>';
        }
	}

	// Do UTC
	$structure[] = '<optgroup label="UTC">';
	$selected = '';
	if ( 'UTC' === $selected_zone )
		$selected = 'selected="selected" ';
	$structure[] = '<option '.$selected.'value="UTC">UTC</option>';
	$structure[] = '</optgroup>';

	// Do manual UTC offsets
	$structure[] = '<optgroup label="'.'Manual Offsets'.'">';
	$offset_range = array (-12, -11.5, -11, -10.5, -10, -9.5, -9, -8.5, -8, -7.5, -7, -6.5, -6, -5.5, -5, -4.5, -4, -3.5, -3, -2.5, -2, -1.5, -1, -0.5,
		0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 5.75, 6, 6.5, 7, 7.5, 8, 8.5, 8.75, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.75, 13, 13.75, 14);
	foreach ( $offset_range as $offset ) {
		if ( 0 <= $offset )
			$offset_name = '+' . $offset;
		else
			$offset_name = (string) $offset;

		$offset_value = $offset_name;
		$offset_name = str_replace(array('.25','.5','.75'), array(':15',':30',':45'), $offset_name);
		$offset_name = 'UTC' . $offset_name;
		$offset_value = 'UTC' . $offset_value;
		$selected = '';
		if ( $offset_value === $selected_zone )
			$selected = 'selected="selected" ';
		$structure[] = '<option '.$selected.'value="'.$offset_value.'">'.$offset_name."</option>";

	}
	$structure[] = '</optgroup>';

	return join("\n", $structure);
}

function get_title() {
	$splitURL = explode('/', substr($_SERVER['REQUEST_URI'], strlen(PATH)));
	if ($splitURL[1] == '' || $splitURL[1] == 'home') {
		return 'MójSchowek.pl';
	} elseif ($splitURL[1] == 'ap') {
		return __('Administration Panel').' - '.__('sitename');
	} else {
		return __('sitename').' - '.__(ucfirst($splitURL[1]));
	}
}

function get_user_info($column_name) {
	$con = @connect_database();
	if (!$con) {
		return '#ERRDB';
	}
	$query = "SELECT $column_name FROM ms_users WHERE user_login='".$_SESSION['user_login']."';";
	$result = mysqli_query($con,$query);
	$array = mysqli_fetch_assoc($result);
	if (!mysqli_error($con)) {
		return $array[$column_name];
	} else {
		return '#ERROR';
	}
}

function get_user_stats($type, $public = false, $user = '') {
	if ($user == '') {
		$user = $_SESSION['user_login'];
	}
	$con = @connect_database();
	if (!$con) {
		return '#ERROR-DB';
	} elseif ($type == 'bookmarks' && $public == false) {
		$query = "SELECT * FROM ms_bookmarks WHERE bookmark_owner='".$user."';";
	} elseif ($type == 'bookmarks' && $public == true) {
		$query = "SELECT * FROM ms_bookmarks WHERE bookmark_owner='".$user."' AND notes_public='public';";
	} elseif ($type == 'notes' && $public == false) {
		$query = "SELECT * FROM ms_notes WHERE notes_owner='".$user."';";
	} elseif ($type == 'notes' && $public == true) {
		$query = "SELECT * FROM ms_notes WHERE notes_owner='".$user."' AND notes_public='public';";
	} else {
		$query = '';
	}
	$result = mysqli_query($con,$query);
	if (!mysqli_error($con)) {
		return mysqli_num_rows($result);
	} else {
		return '#ERROR';
	}
}

function icon($name,$link = '') {
	$src = $_SERVER['DOCUMENT_ROOT'].'/icons/'.$name.'.png';
	$src = '/icons/'.$name.'.png';
	if (!empty($link)) {
		return '<a href="'.$link.'"><i class="material-icons">'.$name.'</i></a>';
	}
	return '<i class="material-icons">'.$name.'</i>';
}

function password_strength($string) {
	$strength = 0;
	//number of characters
	$length = strlen($string);
	$strength += 4*$length;
	//uppercase letters
	$strength += 2*strlen(preg_replace('/[^A-Z]+/', '', $string));
	//lowercase letters
	$strength += 2*strlen(preg_replace('/[^a-z]+/', '', $string));
	//numbers
	if (strlen(preg_replace('/[^0-9]+/', '', $string)) != $length) {
		$strength += 4*strlen(preg_replace('/[^0-9]+/', '', $string));
	}
	//symbols
	if (strlen(preg_replace('/[^!@#$%^&*()?]+/', '', $string)) != $length) {
		$strength += 6*strlen(preg_replace('/[^!@#$%^&*()?]+/', '', $string));
	}
	//middle numbers or symbols
	$string_temp = substr($string, 1);
	$string_temp = substr($string_temp, 0, -1);
	$strength += 2*strlen(preg_replace('/[^0-9!@#$%^&*()?]+/', '', $string_temp));
	//requirements
		$points_requirements = 0;
		//minimum 8 characters
		if ($length >= 8) {
			$points_requirements++;
		}
		//minimum 1 uppercase letter
		if (preg_match('/[A-Z]{1}/',$string)) {
			$points_requirements++;
		}
		//minimum 1 lowercase letter
		if (preg_match('/[a-z]{1}/',$string)) {
			$points_requirements++;
		}
		//minimum 1 number
		if (preg_match('/[0-9]{1}/',$string)) {
			$points_requirements++;
		}
		//minimum 1 symbol
		if (preg_match('/[!@#$%^&*()?]{1}/',$string)) {
			$points_requirements++;
		}
	$strength += 2*$points_requirements;
	//letters only
	if (preg_match('/^[A-Za-z]*$/',$string)) {
		$strength -= $length;
	}
	//numbers only
	if (preg_match('/^[0-9]*$/',$string)) {
		$strength -= $length;
	}
	//repeat characters (case insensitive)
	$nRepInc = 0;
	$nRepChar = 0;
	$nUnqChar = 0;
	for ($m=0;$m<$length;$m++) {
		$bCharExists = false;
		for ($n=0;$n<$length;$n++) {
			if ($string[$m] == $string[$n] && $m != $n) {
				$bCharExists = true;
				/*
					Calculate icrement deduction based on proximity to identical characters
					Deduction is incremented each time a new match is discovered
					Deduction amount is based on total password length divided by the
					difference of distance between currently selected match
				*/
				$nRepInc += abs($length/($n-$m));
			}
		}
		if ($bCharExists) {
				$nRepChar++;
				$nUnqChar = $length-$nRepChar;
				$nRepInc = ($nUnqChar) ? ceil($nRepInc/$nUnqChar) : ceil($nRepInc);
			}
	}
	$strength -= $nRepInc;


	$nTmpAlphaUC = "";
	$nTmpAlphaLC = "";
	$nTmpNumber="";
	$nTmpSymbol="";
	$nAlphaUC = 0;
	$nAlphaLC = 0;
	$nNumber = 0;
	$nSymbol = 0;
	$nConsecAlphaUC = 0;
	$nConsecAlphaLC = 0;
	$nConsecNumber = 0;
	$nConsecSymbol = 0;
	$nConsecCharType=0;
	$nMidChar=0;
	for ($a=0;$a<$length;$a++) {
		//consecutive uppercase letters
		if (preg_match('/[A-Z]/',$string[$a])) {
			if ($nTmpAlphaUC !== "") {
				if ($nTmpAlphaUC+1 == $a) {
					$nConsecAlphaUC++;
					$nConsecCharType++;
				}
			}
			$nTmpAlphaUC = $a;
			$nAlphaUC++;
		//consecutive lowercase letters
		} else if (preg_match('/[a-z]/',$string[$a])) {
			if ($nTmpAlphaLC !== "") {
				if ($nTmpAlphaLC+1 == $a) {
					$nConsecAlphaLC++;
					$nConsecCharType++;
				}
			}
			$nTmpAlphaLC = $a;
			$nAlphaLC++;
		}
		//consecutive numbers
		else if (preg_match('/[0-9]/',$string[$a])) {
			if ($nTmpNumber !== "") {
				if ($nTmpNumber+1 == $a) {
					$nConsecNumber++;
					$nConsecCharType++;
				}
			}
			$nTmpNumber = $a;
			$nNumber++;
		}
		else if (preg_match('/[!@#$%^&*()?]/',$string[$a])) {
			if ($nTmpSymbol !== "") {
				if (($nTmpSymbol + 1) == $a) {
					$nConsecSymbol++;
					$nConsecCharType++;
				}
			}
			$nTmpSymbol = $a;
			$nSymbol++;
		}
	}
	$strength -= ($nConsecAlphaUC*2);
	$strength -= ($nConsecAlphaLC*2);
	$strength -= ($nConsecNumber*2);
	//$strength -= $nSymbol*2;

	//sequential letters (3+)
	//sequential numbers (3+)
	//sequential symbols (3+)

	$sAlphas = "abcdefghijklmnopqrstuvwxyz";
	$sNumerics = "01234567890";
	$sSymbols = ")!@#$%^&*()";
	$nSeqAlpha=0;
	$nSeqNumber=0;
	$nSeqSymbol=0;
	$nSeqChar=0;
	$nReqChar=0;
		/* Check for sequential alpha string patterns (forward and reverse) */
		for ($s=0; $s < 23; $s++) {
			$sFwd = substr($sAlphas,$s,3);
			$sRev = strrev($sFwd);
			if (strpos(strtolower($string),$sFwd) != FALSE || strpos(strtolower($string),$sRev) != FALSE) { $nSeqAlpha++; $nSeqChar++;}
		}

		/* Check for sequential numeric string patterns (forward and reverse) */
		for ($s=0; $s < 8; $s++) {
			$sFwd = substr($sAlphas,$s,3);
			$sRev = strrev($sFwd);
			if (strpos(strtolower($string),$sFwd) != FALSE || strpos(strtolower($string),$sRev) != FALSE) { $nSeqNumber++; $nSeqChar++;}
		}

		/* Check for sequential symbol string patterns (forward and reverse) */
		for ($s=0; $s < 8; $s++) {
			$sFwd = substr($sAlphas,$s,3);
			$sRev = strrev($sFwd);
			if (strpos(strtolower($string),$sFwd) != FALSE || strpos(strtolower($string),$sRev) != FALSE) { $nSeqSymbol++; $nSeqChar++;}
		}
	$strength -= $nSeqAlpha*3;
	$strength -= $nSeqNumber*3;
	$strength -= $nSeqSymbol*3;

	if ($strength > 100) { $strength = 100; }
	else if ($strength < 0) { $strength = 0; }

	if ($strength >= 0 && $strength < 20) { return array($strength, "Very Weak"); }
	else if ($strength >= 20 && $strength < 40) { return array($strength, "Weak"); }
	else if ($strength >= 40 && $strength < 60) { return array($strength, "Good"); }
	else if ($strength >= 60 && $strength < 80) { return array($strength, "Strong"); }
	else if ($strength >= 80 && $strength <= 100) { return array($strength, "Very Strong"); }
}

function random_string($length) {
	$lowercase = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
	$uppercase = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
	$numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
	$symbols = [']', '[', '?', '/', '~', '#', '`', '!', '@', '$', '^', '&', '*', '+', '=', '|', ':', ';', '>'];

	$signs = array_merge($lowercase,$uppercase,$numbers);
	$random_string = '';

	for ($i=0;$i<$length;$i++) {
		$random_string .= $signs[rand(0,count($signs)-1)];
	}
	return $random_string;
}

function send_confirmation_email($name, $mail, $user_activation_key) {
// This function send confirmation mail

	$subject = 'Potwierdzenie rejestracji'.' - '.mb_convert_case(get_domain('http://'.$_SERVER['SERVER_NAME']),MB_CASE_TITLE, "UTF-8");
	ob_start();
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/mail_activation.php');
	$message = ob_get_contents();
	ob_end_clean();
	$headers = 	'Reply-To: '.get_option('admin_email')."\r\n" .
				'From: '.'Administracja'."\r\n" .
				'X-Mailer: PHP/'.phpversion(). "\r\n";
	$headers .= "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	return mail($mail, $subject, $message, $headers);
}

function send_reset_password_email($mail, $token) {
	// This function send confirmation mail

	$subject = 'Resetowanie hasła'.' - '.mb_convert_case(get_domain('http://'.$_SERVER['SERVER_NAME']),MB_CASE_TITLE, "UTF-8");
	ob_start();
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/mail_reset_password.php');
	$message = ob_get_contents();
	ob_end_clean();
	$headers = 	'Reply-To: '.get_option('admin_email')."\r\n" .
				'From: '.'Administracja'."\r\n" .
				'X-Mailer: PHP/'.phpversion(). "\r\n";
	$headers .= "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	return mail($mail, $subject, $message, $headers);
}

function set_active_lang() {
// This function set user active language

	$cookie_time = (3600 * 24 * 30 * 12); // 1 year
	if (isset($_SESSION['user_login']) && in_array(get_user_info('user_language'), array_keys(get_lang_array()))) {
		setcookie ('language', 'lang='.get_user_info('user_language'), time() + $cookie_time,"/");
		$_SESSION['language'] = get_user_info('user_language');
	}
	elseif (isset($_COOKIE['language'])) {
		parse_str($_COOKIE['language']); //lang
		if (in_array($lang, array_keys(get_lang_array()))) {
			setcookie ('language', 'lang='.$lang, time() + $cookie_time,"/");
			$_SESSION['language'] = $lang;
		}
	}
	elseif (get_option('default_language') === false) {
		setcookie ('language', 'lang=en', time() + $cookie_time,"/");
		$_SESSION['language'] = 'en';
	}
	else {
		setcookie ('language', 'lang='.get_option('default_language'), time() + $cookie_time,"/");
		$_SESSION['language'] = get_option('default_language');
	}
}

function set_option($option, $value) {
	$con = @connect_database();
	if ($con == false ) {
		return alert('danger', "Przepraszamy, nie możemy pobrać zawartości strony. Spróbuj później");
	}

	$query = "SELECT * FROM ms_options WHERE option_name='$option'";
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);

	if ($rows == 0) {
		$query = "INSERT INTO ms_options (option_name, option_value) VALUES ('$option', '$value');";
		if (!mysqli_query($con,$query)) {
			return alert('danger', "Przepraszamy, nie możemy dodać opcji.<br>".mysqli_error($con));
		} else {
			return alert('success', "Dodana nowa opcja.");
		}
	} elseif ($rows == 1) {
		$query = "SELECT * FROM ms_options WHERE option_name='$option' AND option_value='$value'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_num_rows($result);
		if ($rows == 0) {
			$query = "UPDATE ms_options SET option_value='$value' WHERE option_name='$option'";
			if (!mysqli_query($con,$query)) {
				return alert('danger', "Przepraszamy, nie możemy pobrać opcji.<br>".mysqli_error($con));
			} else {
				return alert('success', "Opcja zaktualizowana.");
			}
		}
	} else {
		return alert('danger', "Nieprawidłowe użycie funckji set_option()");
	}
}

function set_user_last_login($login) {
	$query = "UPDATE ms_users SET user_secondlast_login = ms_users.user_last_login, user_last_login = NOW() WHERE user_login='$login';";
	$con = connect_database();
	if (!$con) return false;
	$result = mysqli_query($con,$query);
	return true;
}

function translate($text, $lang = '', $type = '') {
	//Tłumaczenie na język użytkownika zapamiętany w sesji
	if ($lang == '') {
		$lang = $_SESSION['language'];
	}

	//Prefiks zmiennych językowych
	$f = '_text';

	//Zadeklarownie dostępu globalnego do tłumaczeń
	global ${$f};

	//Jeśli zmienna z tłumaczeniem nie istnieje zwracany jest żądany tekst do przetłumaczenia
	if (!isset(${$f}[$text])) {
		return $text;
	} else {
		return @${$f}[$text];
	}
}

function pre_registration($name, $login, $pass, $email) {
	$alert = '';

	if ($name != NULL) {
		if (!preg_match("/^[A-Za-z0-9 _-]*$/",$name)) {
			$alert .= __('Nazwa użytkownika może składać się z małych i dużych liter, cyfr oraz spacji, podkreślnika i myślnika.');
		}
		if (!(strlen($name)>=2)) {
			$alert .= __('Nazwa użytkownika powinna skladać się z minimum 2 znaków.');
		}
		if (!(strlen($name)<=24)) {
			$alert .= __('Nazwa użytkownika powinna skladać się z maksimum 24 znaków.');
		}
	}

	if ($login != NULL) {
		if (!preg_match("/^[A-Za-z0-9_-]*$/",$login)) {
			$alert .= __('Login użytkownika może składać się z małych i dużych liter, cyfr oraz podkreślnika i myślnika.');
		}
		if (!(strlen($login)>=6)) {
			$alert .= __('Login użytkownika powinien skladać się z minimum 6 znaków.');
		}
		if (!(strlen($login)<=24)) {
			$alert .= __('Login użytkownika powinien skladać się z maksimum 24 znaków.');
		}
	}

	if ($pass != NULL) {
		if (!preg_match("/^[A-Za-z0-9!@#$%^&*()?]*$/",$pass)) {
			$alert .= __('Hasło może się składać z małych i dużych liter, cyfr oraz znaków:').' '."'!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '?'.";
		}
		if (!(strlen($pass)>=8)) {
			$alert .= __('Hasło użytkownika powinno skladać się z minimum 8 znaków.');
		}
		if (!(strlen($pass)<=24)) {
			$alert .= __('Login użytkownika powinna skladać się z maksimum 24 znaków.');
		}
	}
	if ($email != NULL) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$alert .= __('Niepoprawny adres email.');
		}
	}

	$alert = str_replace(".",".<br>",$alert);
	if (!empty($alert)) {
		$alert = substr($alert, 0, strlen($alert)-4);
	}
	if (empty($alert)) {
		$alert = true;
	}
	echo "<script>console.log('".$alert."');</script>";
	return $alert;
}

function registration($name, $login, $pass, $email) {
	$con = connect_database();
	if (!$con) return __('Błąd połączenia z bazą danych.');

	$query = "SELECT COUNT(*) FROM `ms_users` WHERE user_login='$login'";
	$result = mysqli_query($con,$query);
	$ilosclogin  = mysqli_fetch_row($result);
	$query = "SELECT COUNT(*) FROM `ms_users` WHERE user_email='$email'";
	$result = mysqli_query($con,$query);
	$iloscmail = mysqli_fetch_row($result);

	$user_activation_key = random_string(get_option('activation_key_length'));
	$user_status = get_option('default_user_role');
	$pass = sha1($pass);
	$lang = $_SESSION['language'];

	if ($ilosclogin[0]==0 && $iloscmail[0]==0) {
		$query = "INSERT INTO `ms_users` (user_login,user_pass,user_name,user_email,user_registered,user_activation_key,user_status,user_service,user_timezone,user_language) VALUES ('$login','$pass','$name','$email',(SELECT NOW()),'$user_activation_key','$user_status','basic', 'default','$lang');";
		$query = "INSERT INTO `ms_users` (user_login,user_pass,user_name,user_email,user_activation_key,user_status,user_service,user_timezone,user_language) VALUES ('$login','$pass','$name','$email','$user_activation_key','$user_status','basic', 'default','$lang');";

		if (!mysqli_query($con,$query)) {
			echo "<script>console.log('error line 824');</script>";
			return __('Nie udało się zarejestrować użytkownika, spróbuj później.');
		}

		echo "AKTYWUJ <a href=\"activate\">TUTAJ</a> KLUCZEM ".$user_activation_key; //nie potrzebne na ogol
		//if (!send_confirmation_email($name, $email, $user_activation_key)) {
		//	$query = "DELETE FROM ms_users WHERE user_login='$login' AND user_email='$email'";
		//	$result = mysqli_query($con,$query);
		//	return __('Nie udało się zarejestrować użytkownika, spróbuj później.');
		//}
	}
	else {
		return __('Login użytkownika lub adres mailowy zajęty.');
	}
	return true;
}
?>
