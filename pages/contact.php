<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');
?>

	<div id="content">
<?php
if (isset($_POST['user_email']) && isset($_POST['message_text'])) {
	if (!empty($_POST['user_email']) && !empty($_POST['message_text'])) {
		$mail = $_POST['user_email'];
		$message = $_POST['message_text'];
		$subject = 'temat';
		$message = '<style>
#mail {
	background-color: #f1f1f1;
    color: #000;
}
#top, #bottom {
	display: block;
    color: #fff;
    background-color: #616161;
    text-align: center;
    z-index: 3;
    height: 44px;
    margin: 0 0 24px 0;
    box-shadow: 0 4px 10px 0 rgba(0,0,0,0.25), 0 4px 20px 0 rgba(0,0,0,0.5);
}
#bottom {
	margin: 24px 0 0 0;
}
.label {
	font-weight: bold;
}
.value {
	font-style: italic;
}
</style>

<div style="overflow: hidden;" id="mail">
	<div id="top"></div>
	<div id="content">
	<span class="label">Użytkownik:</span> <span="value">'.@$_SESSION['user_login'].'</span><br/>
	<span class="label">Email:</span> <span="value">'.$mail.'</span><br/>
	<span class="label">Data wysłania:</span> <span="value">'.get_datetime().'</span><br/>
	<span class="label">Temat:</span> <span="value">'.$subject.'</span><br/>
	<br/>
	<span class="label">Wiadomość:</span> <span="value">'.$message.'</span><br/>
	</div>
	<div id="bottom">Wiadomość wysłana przez formularz kontaktowy.</div>
</div>';
		$to = 'kamil_m97@o2.pl';
		$headers = 	'From: '.$mail."\r\n" .
					'Reply-To: '.$mail."\r\n" .
					'X-Mailer: PHP/'.phpversion(). "\r\n";
		$headers .= "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		$x = mail($to, $subject, $message, $headers);
		if($x) {
			echo alert('success',__('Wiadomość została wysłana.'));
		} else {
			echo alert('danger',__('Wysłanie wiadomości nie powiodło się. Prosimy spróbować później.'));
		}
	}
}

if(isset($_SESSION['user_login'])) {
	$mail = get_user_info('user_email');
}
?>
		<div class="window" id="window-contact">
		<form id="contact-form" method="post" action="" novalidate="novalidate">
		<table class="form-table">
			<tbody>
<?php if(isset($_SESSION['user_login'])) { ?>
			<tr>
				<th scope="row"><label for="user_login"><?php echo __('User Name'); ?></label></th>
				<td><input type="text" name="user_login" id="user_login" size="25" value="<?php echo @$_SESSION['user_login']; ?>"></td>
			</tr>
<?php } ?>
			<tr>
				<th scope="row"><label for="user_email"><?php echo __('Your Email'); ?></label></th>
				<td><input type="text" name="user_email" id="user_email" size="25" value="<?php echo @$mail; ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="message_text"><?php echo __('Message'); ?></label></th>
				<td><textarea name="message_text" id="message_text" rows="" cols=""></textarea></td>
			</tr>
			</tbody>
		</table>
		<p class="sendformblock">
			<input type="submit" name="submit" id="submit" value="<?php echo __('Send message'); ?>">
		</p>
		</form>
		</div>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>