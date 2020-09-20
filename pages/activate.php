<?php
define("ALLOW_INCLUDE", "yes");
define("PATH", "/demo/my-notes");
session_start();
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/functions.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/autostart.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');

$show_form = true;
if (isset($_POST['user_login']) && isset($_POST['user_key']) && isset($_POST['user_pin'])) {
	if (!empty($_POST['user_login']) && !empty($_POST['user_key']) && !empty($_POST['user_pin'])) {
		$login = $_POST['user_login'];
		$key = $_POST['user_key'];
		$pin = $_POST['user_pin'];
		
		$result = check_PIN($pin);
		if ($result === true) {
			$con = connect_database();
			if ($con) {
				$query = "SELECT * FROM ms_users WHERE user_login='$login' AND user_activation_key='$key'";
				$result = mysqli_query($con,$query);
				$rowcount = mysqli_num_rows($result);
				$array = mysqli_fetch_assoc($result);
				
				if ($rowcount === 0) {
					$alert = alert('danger',__('Nieprawidłowe dane.'));
				} else {
					if ($array['user_status'] === 'actived user') {
						$alert = alert('danger',__('Konto zostało już aktywowane.'));
					} elseif ($array['user_status'] === 'non-actived user' && get_option('users_can_accepted') == 'false') {
						$query = "UPDATE ms_users SET user_status='actived user', user_PIN='$pin' WHERE user_login='$login' AND user_activation_key='$key'";
						$result = mysqli_query($con,$query);
						$alert = alert('success',__('Konto zostało aktywowane.<br> Za chwilę zostaniesz przeniesiony na stronę logowania.'));	
						header("Refresh: 5; URL=/signin");
					} elseif ($array['user_status'] === 'non-actived user' && get_option('users_can_accepted') == 'true') {
						$query = "UPDATE ms_users SET user_status='non-accepted user', user_PIN='$pin' WHERE user_login='$login' AND user_activation_key='$key'";
						$result = mysqli_query($con,$query);
						$alert = alert('success',__('Konto zostało aktywowane.<br> Teraz administrator strony musi akceptować Twoje konto.').'<br>'.__('Powiadomimy Cię o tym fakcie poprzez email.'));	
					} elseif ($array['user_status'] === 'non-accepted user') {
						$query = "UPDATE ms_users SET user_status='non-accepted user', user_PIN='$pin' WHERE user_login='$login' AND user_activation_key='$key'";
						$result = mysqli_query($con,$query);
						$alert = alert('warning',__('Konto nie zostało zaakceptowane przez administratora.').'<br>'.__('Kiedy to się stanie, powiadomimy Cię poprzez email.'));	
					} else {
						$alert = alert('danger',__('Konto nie mogło zostać aktywowane.'));	
					}
					$show_form = false;
				}
			} else {
				$alert = alert('danger',__('Błąd połączenia z bazą danych.'));	
			}
		} else {
			$alert = alert('danger',$result);	
		}
	}
}
?>

	<div id="content">
<?php
check_access(false); //DOSTĘP TYLKO DLA NIEZALOGOWANYCH
echo @$alert;
if ($show_form == true) {
?>
		<div class="window" id="window-home">
		<h2><?php echo __('Formularz aktywacyjny'); ?></h2>
	<form id="activation-form" method="post" action="" onsubmit="">
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row"><label for="user_login"><?php echo __('User Name'); ?></label></th>
			<td><input type="text" name="user_login" id="user_login" size="25" onkeyup="check_user_login(this.value)" value="<?php echo @$_REQUEST['user']; ?>">
		</tr>
		<tr>
			<th scope="row"><label for="user_key"><?php echo __('Klucz'); ?></label></th>
			<td><input type="text" name="user_key" id="user_key" size="25" maxlength="<?php echo get_option('activation_key_length');?>" value="<?php echo @$_REQUEST['key']; ?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="user_pin"><?php echo __('Ustaw PIN'); ?></label></th>
			<td><input type="number" name="user_pin" id="user_pin" size="25" maxlength="6">
			<p><?php echo __('Numer PIN musi składać się tylko ze znaków numerycznych oraz mieć 6 znaków długości.'); ?></p></td></td>
		</tr>
		</tbody>
	</table>
	<p class="sendformblock">
		<input type="submit" name="submit" id="submit" value="<?php echo __('Aktywuj'); ?>">
	</p>
	</form>
		
		</div>
<?php } ?>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>