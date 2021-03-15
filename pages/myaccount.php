<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');

if (isset($_POST['datetime_zone']) && !empty($_POST['datetime_zone'])) {
	$query = "UPDATE ms_users SET user_timezone='".$_POST['datetime_zone']."' WHERE user_login='".$_SESSION['user_login']."';";
	$con = @connect_database();
	if (!$con) {
		$alert = alert('danger','Błąd połączenia z bazą danych.');
	} else {
		mysqli_query($con,$query);
		if (mysqli_error($con)) {
			$alert = alert('danger','Błąd wykonania zmian.');
		}
		else {
			$alert = alert('success','Zapisano zmiany.');
			$_SESSION['timezone'] = $_POST['datetime_zone'];
		}
	}
}

include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/autostart.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');

if (isset($_POST['user_name']) && isset($_POST['user_pass']) && isset($_POST['user_email'])) {
	if (!empty($_POST["user_name"]) || !empty($_POST["user_pass"]) || !empty($_POST["user_email"])){
		$name = $_POST['user_name'];
		$pass = $_POST["user_pass"];
		$email = $_POST["user_email"];

		unset($_SESSION['change_user_name']);

		unset($_SESSION['change_user_pass']);

		unset($_SESSION['change_user_email']);

		if (empty($_POST["user_name"])) {
			$name = NULL;
		}
		if (empty($_POST["user_pass"])) {
			$pass = NULL;
		}
		if (empty($_POST["user_email"])) {
			$email = NULL;
		}

		$result = pre_registration($name, NULL, $pass, $email);
		if($result === true) {
			if($name != NULL && !isset($_SESSION['change_user_name'])) {
				$_SESSION['change_user_name'] = $name;
			}
			else {
				$_SESSION['change_user_name'] = NULL;
			}
			if($pass != NULL && !isset($_SESSION['change_user_pass'])) {
				$_SESSION['change_user_pass'] = sha1($pass);
			}
			else {
				$_SESSION['change_user_pass'] = NULL;
			}
			if($email != NULL && !isset($_SESSION['change_user_email'])) {
				$_SESSION['change_user_email'] = $email;
			}
			else {
				$_SESSION['change_user_email'] = NULL;
			}
		}
		else {
			$alert = alert('danger',$result);
		}
	}
}

if (isset($_POST['user_PIN'])) {
	if (!empty($_POST["user_PIN"])) {
		if ($_POST['user_PIN'] == get_user_info('user_PIN')) {
			if (isset($_SESSION['change_user_name']) || isset($_SESSION['change_user_pass']) || isset($_SESSION['change_user_email'])) {
				$name = $_SESSION['change_user_name'];
				unset($_SESSION['change_user_name']);
				$pass = $_SESSION['change_user_pass'];
				unset($_SESSION['change_user_pass']);
				$email = $_SESSION['change_user_email'];
				unset($_SESSION['change_user_email']);

				$query = "";
				if ($name != NULL) {
					$query .= "UPDATE ms_users SET user_name='$name' WHERE user_login='".$_SESSION['user_login']."'; ";
				}
				if ($pass != NULL) {
					$query .= "UPDATE ms_users SET user_pass='$pass' WHERE user_login='".$_SESSION['user_login']."'; ";
				}
				if ($email != NULL) {
					$query .= "UPDATE ms_users SET user_email='$email' WHERE user_login='".$_SESSION['user_login']."';";
				}

				$con = @connect_database();
				if (!$con) {
					echo '#ERRDB';
				}
				$result = mysqli_multi_query($con,$query);
				if (mysqli_error($con)) {
					//echo '#ERROR';
					//echo mysqli_error($con);
					$alert = alert('danger','Dane nie zostały wprowadzone. BŁĄD WEWNĘTRZNY');
				}
				else {
					$alert = alert('success','Twoje dane zostały poprawione.');
				}
			}
			else {
				$alert = alert('danger','Dane nie zostały przekazane lub wygasła ich ważność.');
			}
		}
		else {
			$alert = alert('danger','Nieprawidłowy kod PIN.');
		}
	}
	else {
		$alert = alert('warning','Niewprowadzono kodu PIN.');
	}
}

if (isset($_POST['user_news_ps']) && isset($_POST['user_bookmarks_ps']) && isset($_POST['user_notes_ps'])) {
	if (!empty($_POST["user_news_ps"]) || !empty($_POST["user_bookmarks_ps"]) || !empty($_POST["user_notes_ps"])){
		$news = $_POST['user_news_ps'];
		$bookmarks = $_POST["user_bookmarks_ps"];
		$notes = $_POST["user_notes_ps"];

		$query = "UPDATE ms_users SET user_news_ps='$news', user_bookmarks_ps='$bookmarks', user_notes_ps='$notes' WHERE user_login='".$_SESSION['user_login']."';";
		$con = @connect_database();
		if (!$con) {
			$alert = alert('danger','Błąd połączenia z bazą danych.');
		} else {
			mysqli_query($con,$query);
			if (mysqli_error($con)) {
				$alert = alert('danger','Błąd wykonania zmian.');
			}
			else {
				$alert = alert('success','Zapisano zmiany.');
			}
		}
	}
}
?>

	<div id="content">
<?php
if (isset($_SESSION['change_user_name']) || isset($_SESSION['change_user_pass']) || isset($_SESSION['change_user_email'])) {
	echo '<div class="window" id="window-pin">
	<h2 style="text-align: center">Wprowadź PIN</h2>
	<form method="POST" action="" class="form-table" style="text-align:center">
	<input style="margin: 0 auto; text-align: center; line-height: 50px; font-size: 50px;" type="password" name="user_PIN" maxlength="6">
	<p class="sendformblock" style="text-align:center;     margin: 20px 0 15px 0;">
		<input type="submit" name="submit" id="submit" value="Save settings">
	</p>
	</form>
	</div>';
}
check_access(true); //DOSTĘP TYLKO DLA ZALOGOWANYCH
echo @$alert;
?>
		<div class="window" id="window-myaccount">
		<h2>Moje konto</h2>
		<table class="form-table">
			<tr>
				<th scope="row">User Name</th>
				<td><?php echo get_user_info('user_name'); ?></td>
			</tr>
			<tr>
				<th scope="row">User Login</th>
				<td><?php echo get_user_info('user_login'); ?></td>
			</tr>
			<tr>
				<th scope="row">User Email</th>
				<td><?php echo get_user_info('user_email'); ?></td>
			</tr>
			<tr>
				<th scope="row">User Status</th>
				<td><?php echo get_user_info('user_status'); ?></td>
			</tr>
			<tr>
				<th scope="row">Service</th>
				<td><?php echo get_user_info('user_service'); ?> <a href="order">Zmień</a></td>
			</tr>
		</table>

		<h2>Statystyki</h2>
		<table class="form-table">
			<tr>
				<th scope="row">Last activity</th>
				<td><?php echo get_user_info('user_secondlast_login') ?></td>
			</tr>
			<tr>
				<th scope="row">Bookmarks</th>
				<td><?php echo get_user_stats('bookmarks', false) ?></td>
			</tr>
			<!--<tr>
				<th scope="row">Public Bookmarks</th>
				<td><?php //echo get_user_stats('bookmarks', true) ?></td>
			</tr>-->
			<tr>
				<th scope="row">Notes</th>
				<td><?php echo get_user_stats('notes', false) ?></td>
			</tr>
			<tr>
				<th scope="row">Public Notes</th>
				<td><?php echo get_user_stats('notes', true) ?></td>
			</tr>
		</table>

		<h2>Ustawienia konta</h2>
		<p>Tutaj zmienisz dane swojego konta. Uzupełnij tylko te pola, które chcesz zmienić.</p>
		<form id="myaccount-setting-form" method="post" action="" onsubmit="return validateregistrationform()">
		<table class="form-table">
			<tr>
				<th scope="row"><label for="user_name">User Name</label></th>
				<td><input type="text" name="user_name" id="user_name" value="<?php echo @$_POST["user_name"]; ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_pass"></label>Password</th>
				<td><input type="password" name="user_pass" id="user_pass" value="<?php echo @$_POST["user_pass"]; ?>">
				<p>Double-check your new password before continuing.</p></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_email">Your Email</label></th>
				<td><input type="text" name="user_email" id="user_email" value="<?php echo @$_POST["user_email"]; ?>">
				<p>Double-check your new email address before continuing.</p></td>
			</tr>
		</table>
		<p class="sendformblock">
			<input type="submit" name="submit" id="submit" value="Save settings">
		</p>
		</form>

		<h2>Ustawienia wyświetlania</h2>
		<form id="myaccount-setting-display-form" method="post" action="" onsubmit="return validateregistrationform()">
		<table class="form-table">
			<tr>
				<th scope="row"><label for="user_news_ps">News</label></th>
				<td><select name="user_news_ps" id="user_news_ps">
					<option value="default"<?php if(get_user_info('user_news_ps')=='default') { echo ' selected'; } ?>>domyślnie (<?php echo get_option('news_per_site'); ?>)</option>
					<option value="3"<?php if(get_user_info('user_news_ps')=='3') { echo ' selected'; } ?>>3</option>
					<option value="4"<?php if(get_user_info('user_news_ps')=='4') { echo ' selected'; } ?>>4</option>
					<option value="5"<?php if(get_user_info('user_news_ps')=='5') { echo ' selected'; } ?>>5</option>
				</select><p>News per page.</p></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_bookmarks_ps">Bookmarks</label></th>
				<td><select name="user_bookmarks_ps" id="user_bookmarks_ps">
					<option value="5"<?php if(get_user_info('user_bookmarks_ps')=='5') { echo ' selected'; } ?>>5</option>
					<option value="10"<?php if(get_user_info('user_bookmarks_ps')=='10') { echo ' selected'; } ?>>10</option>
					<option value="15"<?php if(get_user_info('user_bookmarks_ps')=='15') { echo ' selected'; } ?>>15</option>
					<option value="20"<?php if(get_user_info('user_bookmarks_ps')=='20') { echo ' selected'; } ?>>20</option>
					<option value="25"<?php if(get_user_info('user_bookmarks_ps')=='25') { echo ' selected'; } ?>>25</option>
					<option value="all"<?php if(get_user_info('user_bookmarks_ps')=='all') { echo ' selected'; } ?>>all</option>
				</select><p>Bookmarks per page.</p></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_notes_ps"></label>Notes</th>
				<td><select name="user_notes_ps" id="user_notes_ps">
					<option value="5"<?php if(get_user_info('user_notes_ps')=='5') { echo ' selected'; } ?>>5</option>
					<option value="10"<?php if(get_user_info('user_notes_ps')=='10') { echo ' selected'; } ?>>10</option>
					<option value="15"<?php if(get_user_info('user_notes_ps')=='15') { echo ' selected'; } ?>>15</option>
					<option value="20"<?php if(get_user_info('user_notes_ps')=='20') { echo ' selected'; } ?>>20</option>
					<option value="25"<?php if(get_user_info('user_notes_ps')=='25') { echo ' selected'; } ?>>25</option>
					<option value="all"<?php if(get_user_info('user_notes_ps')=='all') { echo ' selected'; } ?>>all</option>
				</select><p>Notes per page.</p></td>
			</tr>
		</table>
		<p class="sendformblock">
			<input type="submit" name="submit" id="submit" value="Save settings">
		</p>
		</form>

		<h2>Ustawienia daty i czasu</h2>
		<form id="myaccount-setting-display-form" method="post" action="" onsubmit="return validateregistrationform()">
		<table class="form-table">
			<tr>
				<th scope="row">Czas lokalny</th>
				<td><?php echo get_datetime(); ?></td>
			</tr>
			<tr>
				<th scope="row">Czas uniwersalny (UTC)</th>
				<td><?php echo get_datetime_GMT(); ?></td>
			</tr>
			<tr>
				<th scope="row"><label for="datetime_zone"></label>Strefa czasowa</th>
				<td><select name="datetime_zone" id="datetime_zone">
				<?php echo get_timezone_select(); ?>
				</select><p class="description" id="timezone-description">Wybierz miasto w twojej strefie czasowej lub przesunięcie twojej strefy czasowej względem UTC.</p></td>
			</tr>
		</table>
		<p class="sendformblock">
			<input type="submit" name="submit" id="submit" value="Save settings">
		</p>
		</form>
		</div>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>
