<?php
define("ALLOW_INCLUDE", "yes");
define("PATH", "/demo/my-notes");
session_start();
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/functions.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/autostart.php');

if (isset($_POST['user_login']) && isset($_POST['user_pass'])) {
	if (!empty($_POST["user_login"]) && !empty($_POST["user_pass"])) {
		$login = $_POST["user_login"];
		$pass = sha1($_POST["user_pass"]);

		$con = connect_database();
		if ($con === false) {
			$alert = alert('danger',__('Błąd połączenia z bazą danych.'));
		} else {
			$query = "SELECT * FROM ms_users WHERE user_login='$login' AND user_pass='$pass'";
			$result = mysqli_query($con,$query);
			$rowcount = mysqli_num_rows($result);
			$array = mysqli_fetch_assoc($result);
			if ($rowcount == 1) {
				if ($array['user_status'] == 'non-actived user') {
					$alert = alert('danger',__('Konto nie zostało aktywowane. Musisz aktywować konto.'));
				} elseif ($array['user_status'] == 'non-accepted user') {
					$alert = alert('danger',__('Konto nie zostało aktywowane przez Administratora.').__('Powiadomimy Cię o tym fakcie poprzez email.'));
				} elseif (!isset($_SESSION['user_login'])) {
					$_SESSION['user_login'] = $login;
					set_user_last_login($login);
					if (isset($_POST['permanent-log'])) {
						$con = connect_database();
						$key = random_string(10);
						$query = "DELETE FROM `ms_autologin` WHERE autologin_user='".$_POST["user_login"]."'";
						mysqli_query($con,$query);
						$query = "INSERT INTO `ms_autologin` (autologin_user,autologin_key) VALUES ('".$_POST["user_login"]."','$key');";
						mysqli_query($con,$query);
						$cookie_time = (3600 * 24 * 30); // 30 days
						setcookie ('autologin_key', 'user='.$_POST["user_login"].'&key='.$key, time() + $cookie_time,"/");
					}
					set_active_lang();
					$alert = alert('success',__('Logowanie zakończone.').'<br>'.__('Za chwilę zostaniesz przeniesiony na stronę główną.'));
					header("Refresh: 4; URL=/home");
				}
			} else {
				$alert = alert('danger',__('Logowanie zakończone niepowodzeniem.'));
			}
		}
	}
}

include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');
?>

<div id="content">
<?php
if (@$alert == '') {
	check_access(false); //DOSTĘP TYLKO DLA ZALOGOWANYCH
} else {
	echo @$alert;
	echo '</div>';
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
	exit;
}
?>
	<div class="window" id="window-login">
	<form id="login-form" method="post" action="" novalidate="novalidate">
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row"><label for="user_login"><?php echo __('User Name'); ?></label></th>
			<td><input type="text" name="user_login" id="user_login" size="25"></td>
		</tr>
		<tr>
			<th scope="row"><label for="user_password"><?php echo __('Password'); ?></label></th>
			<td><input type="password" name="user_pass" id="user_pass" size="25"></td>
		</tr>
		<tr>
			<th scope="row"><label for="permanent-log"><?php echo __('Log in permanently'); ?></label></th>
			<td><input type="checkbox" name="permanent-log" id="permanent-log" size="25"></td>
		</tr>
		</tbody>
	</table>
	<p class="sendformblock">
		<input type="submit" name="Submit" id="submit" value="<?php echo __('Log In'); ?>">
		<button type="button" onclick="window.location='reset-password'"><?php echo __('Forgot password?'); ?></button>
	</p>
	</form>
	</div>
</div>

<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>
