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
if (isset($_POST['user_name']) && isset($_POST['user_login']) && isset($_POST['user_pass']) && isset($_POST['user_email'])) {
	if(!empty($_POST["user_name"]) && !empty($_POST["user_login"]) && !empty($_POST["user_pass"]) && !empty($_POST["user_email"]) && !empty($_POST['user_rules'])) {
		$name = $_POST["user_name"];
		$login = $_POST["user_login"];
		$pass = $_POST["user_pass"];
		$email = $_POST["user_email"];

		$result = pre_registration($name, $login, $pass, $email);
		if($result === true) {
			$result = registration($name, $login, $pass, $email);
			if($result === true) {
				$alert = alert('success',__('Zostałeś zarejestrowany. Klucz aktywacyjny został wysłany na adres mailowy wskazany podczas rejestracji.'));
				$show_form = false;
			}
			else {
				$alert = alert('danger',$result);
			}
		}
		else {
			$alert = alert('danger',$result);
		}
	}
	else {
		$alert = alert('danger',__('Wymagane wszystkie pola oraz akceptacja regulaminu.'));
	}
}
?>



<div id="content">
	<?php
	if(isset($_SESSION['user_login'])) {
		echo alert('danger','Brak dostępu');
		echo '</div>';
		include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
		exit;
	}
	if(get_option('users_can_register') == 'false') {
		echo alert('info',__('Rejestracja jest wyłączona.'));
		echo '</div>';
		include($_SERVER['DOCUMENT_ROOT'].PATH.'/section/footer.php');
		exit;
	}
	echo @$alert;

if ($show_form == true) {
?>
	<div class="window" id="window-registration">
	<h2>Registration form</h2>
	<form id="registration-form" method="post" action="" onsubmit="return validateregistrationform()">
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row"><label for="user_name"><?php echo __('Your Name'); ?></label></th>
			<td><input type="text" name="user_name" id="user_name" size="25" value="<?php echo @$_POST["user_name"]; ?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="user_login"><?php echo __('User Name'); ?></label></th>
			<td><input type="text" name="user_login" id="user_login" size="25" onkeyup="check_user_login(this.value)" value="<?php echo @$_POST["user_login"]; ?>">
			<p style="font-weight: bold;" id="login_reserved_status"></p>
			<p><?php echo __('Usernames can have only alphanumeric characters, spaces, underscores, hyphens, periods, and the @ symbol.'); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><label for="user_pass"><?php echo __('Password'); ?></label></th>
			<td><input type="password" name="user_pass" id="user_pass" size="25" onkeyup="check_user_password(this.value)">
			<p><span class="description important"><strong><?php echo __('Password strength'); ?>:</strong><span id="password_strength"></span></span></p>
			<p><span class="description important"><strong><?php echo __('Important'); ?>:</strong>
			<?php echo __('You will need this password to log in. Please store it in a secure location.'); ?></span></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="user_email"><?php echo __('Your Email'); ?></label></th>
			<td><input type="text" name="user_email" id="user_email" size="25" value="<?php echo @$_POST["user_email"]; ?>">
			<p><?php echo __('Double-check your email address before continuing.'); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><label for="user_rules"><?php echo __('Accept Rules'); ?></label></th>
			<td><input type="checkbox" name="user_rules" id="user_rules" value="rules" size="25">
			<p><span class="description important"><strong><?php echo __('Rules'); ?>:</strong>
			<a target="blank" href="regulations/"><?php echo __('tutaj'); ?></a></span></p>
			</td>
		</tr>
		</tbody>
	</table>
	<p class="sendformblock">
		<input type="submit" name="submit" id="submit" value="<?php echo __('Register'); ?>">
	</p>
	</form>
	</div>

	<div class="alert" id="warning_alert" data-type="warning" style="display: none;">
		<span onclick="this.parentElement.style.display='none'" class="alert_close">&times;</span>
		<h3>Warning!</h3>
		<p id="warnign_alert_registration"></p>
	</div>
<?php } ?>
</div>
<?php
if ($show_form == true) {
?>
<script>
function validateregistrationform() {
	var alert_text = '';
	var alert_status = true;
	var name = document.getElementById("user_name");
	var login = document.getElementById("user_login");
	var pass = document.getElementById("user_pass");
	var email = document.getElementById("user_email");
	var rules = document.getElementById("user_rules");

	if (name.value.length == 0 || login.value.length == 0 || pass.value.length == 0 || email.value.length == 0) {
		document.getElementById("warnign_alert_registration").innerHTML = '<?php echo __('Wymagane wszystkie pola'); ?>';
		warning_alert();
		return false;
	}

	if(name.value.match(/^[A-Za-z0-9 _-]*$/) == null) {
		alert_text += '<?php echo __('Nazwa imienia może składać się z małych i dużych liter, cyfr oraz spacji, podkreślnika i myślnika.'); ?>';
	}

	if(name.value.length < 2) {
		alert_text += '<?php echo __('Nazwa imienia powinna skladać się z minimum 2 znaków.'); ?>';
	}

	if(name.value.length > 24) {
		alert_text += '<?php echo __('Nazwa imienia powinna skladać się z maksimum 24 znaków.'); ?>';
	}

	if(login.value.match(/^[A-Za-z0-9_-]*$/) == null) {
		alert_text += '<?php echo __('Nazwa użytkownika może składać się z małych i dużych liter, cyfr oraz podkreślnika i myślnika.'); ?>';
	}

	if(login.value.length < 6) {
		alert_text += '<?php echo __('Nazwa użytkownika powinien skladać się z minimum 6 znaków.'); ?>';
	}

	if (login.value.length > 24) {
		alert_text += '<?php echo __('Nazwa użytkownika powinien skladać się z maksimum 24 znaków.'); ?>';
	}

	if (pass.value.match(/^[A-Za-z0-9!@#$%^&*()?]*$/) == null) {
		alert_text += '<?php echo __('Hasło może się składać z małych i dużych liter, cyfr oraz znaków:'); ?>'+' '+" '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '?'.";
	}

	if (pass.value.length < 8) {
		alert_text += '<?php echo __('Hasło użytkownika powinno skladać się z minimum 8 znaków.'); ?>';
	}

	if (pass.value.length > 24) {
		alert_text += '<?php echo __('Hasło użytkownika powinna skladać się z maksimum 24 znaków.'); ?>';
	}

	if (email.value.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/) == null) {
		alert_text += '<?php echo __('Niepoprawny adres email.'); ?>';
	}

	if (!rules.checked) {
		alert_text += '<?php echo __('Wymagana akceptacja regulaminu.'); ?>';
	}

	if (alert_text != '') {
		alert_text = alert_text.replace(/[\.]/g,'\.<br>');
		alert_text = alert_text.substr(0,alert_text.length-4);
		document.getElementById("warnign_alert_registration").innerHTML = alert_text;
		warning_alert();
		return false;
	}
	else {
		document.getElementById("warnign_alert_registration").innerHTML = '';
		warning_alert();
		return true;
	}
}

function check_user_login(string) {
	if (string.length == 0) {
		document.getElementById("login_reserved_status").innerHTML = '';
        return false;
    }
	else {
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				document.getElementById("login_reserved_status").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "/demo/my-notes/functions/functions_ajax.php?function=check_user_login&string=" + string, true);
        xmlhttp.send();
	}
}

function check_user_password(string) {
	if (string.length == 0) {
		document.getElementById("password_strength").innerHTML = '';
        return false;
    }
	else {
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
				document.getElementById("password_strength").innerHTML = this.responseText+"%";
            }
        };
        xmlhttp.open("GET", "/demo/my-notes/functions/functions_ajax.php?function=check_user_password&string=" + string, true);
        xmlhttp.send();
	}
}

function warning_alert() {
	alert_text = document.getElementById("warnign_alert_registration").innerHTML;
	if (alert_text == '') {
		document.getElementById("warning_alert").style.display = 'none';
	}
	if (alert_text != '') {
		document.getElementById("warning_alert").style.display = 'block';
	}
}
</script>

<?php
}
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>
