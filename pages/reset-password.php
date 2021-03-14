<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');

if (isset($_POST['user_email']) && !isset($_POST['reset_token']) && !isset($_POST['user_password']) && !isset($_POST['user_password_confirm'])) {
	if (!empty($_POST["user_email"])) {
		$reset_email = $_POST["user_email"];
		$reset_token = random_string(25);
		$reset_deadline = time()+86400;
		
		$con = connect_database();
		if ($con === false) {
			$alert = alert('danger',__('Błąd połączenia z bazą danych.'));
		} else {
			$query = "SELECT * FROM ms_users WHERE user_email='$reset_email';";
			$result = mysqli_query($con,$query);
			$rowcount = mysqli_num_rows($result);
			$array = mysqli_fetch_assoc($result);
			if ($rowcount == 1) {
				$query = "INSERT INTO ms_resetpassword (reset_token,reset_email,reset_deadline,reset_status) VALUES ('$reset_token' ,'$reset_email', '$reset_deadline', 'notexecute')";
				$result = mysqli_query($con,$query);
				if (!send_reset_password_email($reset_email, $reset_token)) {
					$query = "DELETE FROM ms_resetpassword WHERE reset_email='$reset_email' AND reset_token='$reset_token'";
					$result = mysqli_query($con,$query);
					$alert = alert('warning','Nie możemy zresetować hasła. Spróbuj później');
				} else {
					$alert = alert('success',__('Hasło zresetowane.').'<br>'.__('Na twój adres email wyslaliśmy link, aby ustawić nowe hasło'));
				}
			} else {
				$alert = alert('warning','Zły adres email');
				echo mysqli_error($con);
			}
		}
	}
}

$show_form = true;
if (isset($_POST['user_email']) && isset($_POST['reset_token']) && isset($_POST['user_password']) && isset($_POST['user_password_confirm'])) {
	if(!empty($_POST['user_email']) && !empty($_POST['reset_token']) && !empty($_POST['user_password']) && !empty($_POST['user_password_confirm'])) {
		$email = $_POST['user_email'];
		$token = $_POST['reset_token'];
		$password = $_POST['user_password'];
		$password_confirm = $_POST['user_password_confirm'];
		
		if ($password == $password_confirm) {
			$con = connect_database();
			if ($con) {
				$query = "SELECT * FROM ms_resetpassword WHERE reset_email='$email' AND reset_token='$token' AND reset_deadline>".time()." AND reset_status='notexecute'";
				$result = mysqli_query($con,$query);
				$rowcount = mysqli_num_rows($result);
				if ($rowcount === 1) {
					$result = pre_registration(NULL, NULL, $pass, NULL);
					if($result === true) {
						if ($con) {
							$query = "UPDATE ms_users SET user_pass='".sha1($password)."' WHERE user_email='$email'";
							if (mysqli_query($con,$query)) {
								$alert = alert('success','Hasło zostało zmienione.');
								$query = "UPDATE ms_resetpassword SET reset_status='execute' WHERE reset_email='$email' AND reset_token='$token'";
								mysqli_query($con,$query);
							} else {
								$alert = alert('danger','Nie możemy zapisać zmiany hasła. Spróbuj później.');
							}
						} 
					} else {
					$alert = alert('danger',$result);
					}
				} else {
					$alert = alert('danger',__('Nieprawidłowy token lub wygasł token.'));
				}
			} else {
						$alert = alert('danger',__('Błąd połączenia z bazą danych.'));
					}
		} else {
			$alert = alert('danger',__('Źle wprowadzone potwierdzenie hasła.'));
		}
	}
	else {
		$alert = alert('danger',__('Wymagane wszystkie pola oraz akceptacja regulaminu.'));
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

if(!isset($_REQUEST['token'])) {
?>
	<div class="window" id="window-reminder-password">
		<h2><?php echo __('Reminder password');?></h2>
		<form id="reminder-password-form" method="post" action="" novalidate="novalidate">
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><label for="user_email"><?php echo __('Your Email'); ?></label></th>
				<td><input type="text" name="user_email" id="user_email" size="25"></td>
			</tr>
			</tbody>
		</table>
		<p class="sendformblock">
			<input type="submit" name="Submit" id="submit" value="<?php echo __('Reset password'); ?>">
		</p>
		</form>
	</div>
<?php
}

if(isset($_REQUEST['token'])) {
?>
	<div class="window" id="window-reset-password">
		<h2><?php echo __('Reset password');?></h2>
		<form id="reset-password-form" method="post" action="" novalidate="novalidate">
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row"><label for="user_email"><?php echo __('Your Email'); ?></label></th>
				<td><input type="text" name="user_email" id="user_email" size="25"></td>
			</tr>
			<tr>
				<th scope="row"><label for="reset_token"><?php echo __('Token'); ?></label></th>
				<td><input type="text" name="reset_token" id="reset_token" size="25" value="<?php echo @$_REQUEST['token']?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_password"><?php echo __('New password'); ?></label></th>
				<td><input type="password" name="user_password" id="user_password" size="25"></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_password_confirm"><?php echo __('Confirm new password'); ?></label></th>
				<td><input type="password" name="user_password_confirm" id="user_password_confirm" size="25"></td>
			</tr>
			</tbody>
		</table>
		<p class="sendformblock">
			<input type="submit" name="Submit" id="submit" value="<?php echo __('Save new password'); ?>">
		</p>
		</form>
	</div>
<?php } ?>
</div>

<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>