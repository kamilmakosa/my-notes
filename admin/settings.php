<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu_ap.php');

if (!isset($_SESSION['user_login']) || get_user_info('user_name') != 'Administrator') {
	redirect('/signin');
	exit;
}

if(count($_POST) != 0) {
	foreach ($_POST as $key => $value) {
		if ($key != 'submit') {
			echo set_option($key, $value);
		}
	}
}
?>

	<div id="content">
		<div class="window-ap" id="window-ap-general">
			<h2><span><?php echo icon('arrow_drop_down');?></span> General Settings</h2>
			<form id="database-setting" class="folddiv" method="post" action="" novalidate="novalidate">
			<table class="form-table"><tbody>
			<tr>
				<th scope="row"><label for="sitename">Tytuł witryny</label></th>
				<td><input type="text" name="sitename" id="sitename" value="<?php echo get_option('sitename')?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="sitedescription">Opis</label></th>
				<td><input type="text" name="sitedescription" id="sitedescription" value="<?php echo get_option('sitedescription')?>">
			</tr>
			<tr>
				<th scope="row"><label for="siteurl">Adres strony (URL)</label></th>
				<td><input type="url" name="siteurl" id="siteurl" value="<?php echo get_option('siteurl')?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="homeurl">Adres witryny (URL)</label></th>
				<td><input type="url" name="homeurl" id="homeurl" value="<?php echo get_option('homeurl')?>">
				<p class="description" id="homeurl-description">Wprowadź adres, <a href="https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory">jeśli chcesz mieć WordPressa w innym katalogu, niż główny.</a></p></td>
			</tr>
			<tr>
				<th scope="row"><label for="admin_email">Adres email</label></th>
				<td><input type="email" name="admin_email" id="admin_email" value="<?php echo get_option('admin_email')?>">
				<p class="description" id="admin-email-description">Ten adres jest używany do celów administracyjnych, takich jak powiadomienia o nowych użytkownikach.</p></td>
			</tr>
			<tr>
				<th scope="row">Rejestracja</th>
				<td><input type="hidden" name="users_can_register" value="false" />
				<input name="users_can_register" type="checkbox" id="users_can_register" value="true"<?php if(get_option('users_can_register')=='true') { echo ' checked'; } ?>>
				<label for="users_can_register">włączona</label></td>
			</tr>
			<tr>
				<th scope="row">Akceptacja przez administratora</th>
				<td><input type="hidden" name="users_can_accepted" value="false" />
				<input name="users_can_accepted" type="checkbox" id="users_can_accepted" value="true"<?php if(get_option('users_can_accepted')=='true') { echo ' checked'; } ?> onClick="disabledOption()">
				<label for="users_can_accepted">włączona</label></td>
			</tr>
			<tr>
				<th scope="row"><label for="default_user_role">Domyślna rola nowych użytkowników</label></th>
				<td><select name="default_user_role" id="default_user_role">
					<option value="non-actived user"<?php if(get_option('default_user_role')=='non-actived user') { echo ' selected'; } ?>>Użytkownik nieaktywowany</option>
					<option value="non-accepted user"<?php if(get_option('default_user_role')=='non-accept user') { echo ' selected'; } ?>>Użytkownik nieakceptowany</option>
					<option value="user"<?php if(get_option('default_user_role')=='user') { echo ' selected'; } ?>>Użytkownik</option>
					<option value="contributor"<?php if(get_option('default_user_role')=='contributor') { echo ' selected'; } ?>>Współpracownik</option>
					<option value="author"<?php if(get_option('default_user_role')=='author') { echo ' selected'; } ?>>Autor</option>
					<option value="editor"<?php if(get_option('default_user_role')=='editor') { echo ' selected'; } ?>>Redaktor</option>
					<option value="administrator"<?php if(get_option('default_user_role')=='administrator') { echo ' selected'; } ?>>Administrator</option>
				</select></td>
			</tr>
			<tr>
				<th scope="row"><label for="default_language">Język witryny</label></th>
				<td><select name="default_language" id="default_language">
				<?php echo get_lang_select(); ?>
				</select></td>
			</tr>
			<tr>
				<th scope="row"><label for="default_timezone">Strefa czasowa</label></th>
				<td><select name="default_timezone" id="default_timezone">
					<?php echo get_timezone_select();?>
				</select>
				<p>Domyslna strefa czasowa: <?php echo get_option('default_timezone');?></p>
				<p>Czas dla domyślnej strefy czasowej: <?php echo get_datetime(get_option('default_timezone'));?></p></td>
			</tr>
			</tbody></table>
			<p class="sendformblock">
				<input type="submit" name="submit" id="submit" value="Save options">
			</p>
			</form>
		</div>
	
	<!--<div class="window-ap" id="window-ap-general">
	<h2></h2>
	</div>-->
	
	<!--<div class="window-ap" id="window-ap-database">
	<h2>Database Settings</h2>
	<form id="database-setting" method="post" action="" novalidate="novalidate">
	<table class="form-table"><tbody>
		<tr>
			<th scope="row"><label for="db_name">Database Name</label></th>
			<td><input type="text" name="db_name" id="db_name" size="25" value="<?php echo get_option('database_name')?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="db_user">Database User</label></th>
			<td><input type="text" name="db_user" id="db_user" size="25" value="<?php echo get_option('database_user')?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="db_password">Database Password</label></th>
			<td><input type="password" name="db_password" id="db_password" size="25" value="<?php echo get_option('database_pass')?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="db_host">Database Host</label></th>
			<td><input type="text" name="db_host" id="db_host" size="25" value="<?php echo get_option('database_host')?>"></td>
		</tr>
	</tbody></table>
	<p class="sendformblock">
		<input type="submit" name="submit-database" id="submit" value="Save options">
	</p>
	</form>
	</div>-->
	
		<div class="window-ap" id="window-ap-database">
		<h2><span><?php echo icon('arrow_drop_down');?></span>Mail Server Settings</h2>
		<form id="mail-setting" class="folddiv" method="post" action="" novalidate="novalidate">
		<table class="form-table"><tbody>
			<tr>
				<th scope="row"><label for="mail_server">Mail Server</label></th>
				<td><input type="text" name="mail_server" id="mail_server" size="25" value="<?php echo get_option('mail_server')?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="mail_port">Port</label></th>
				<td><input type="text" name="mail_port" id="mail_port" size="25" value="<?php echo get_option('mail_port')?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="mail_user">Login Name</label></th>
				<td><input type="text" name="mail_user" id="mail_user" size="25" value="<?php echo get_option('mail_user')?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="mail_password">Password</label></th>
				<td><input type="password" name="mail_password" id="mail_password" size="25" value="<?php echo get_option('mail_pass')?>"></td>
			</tr>
		</tbody></table>
		<p class="sendformblock">
			<input type="submit" name="submit" id="submit" value="Save options">
		</p>
		</form>
		</div>
	</div>
</div>

<div class="sidepanel">
	<div id="title" onclick="tooglePanel(this)">Download settings <?php echo icon('settings'); ?></div>
	<div id="content">
		<span>Download settings</span>
<?php echo icon('file_download'); ?>

	</div>
</div>

<script>
function filter() {
	textsearch = document.getElementById("filter_searcher").value;
	value1 = document.getElementById("filter_status").value;
	value2 = document.getElementById("filter_service").value;
	value3 = Date.parse(document.getElementById("date_lastlogin_from").value);
	value4 = Date.parse(document.getElementById("date_lastlogin_to").value);
	value5 = Date.parse(document.getElementById("date_registration_from").value);
	value6 = Date.parse(document.getElementById("date_registration_to").value);
	
	licznik=0;
	tagsrow = document.querySelectorAll("#table-user-list tbody tr");
	tagsrow = tagsrow.length;
	for (i=1;i<tagsrow+1;i++) {
		wiersz = "tr"+i;
		
		pole1 = document.getElementById("status"+i).innerHTML;
		pole2 = document.getElementById("service"+i).innerHTML;
		
		pole3 = document.getElementById("last-login"+i).innerHTML;
		pole4 = document.getElementById("registered"+i).innerHTML;
		reg = /[0-9]{4}-[0-9]{2}-[0-9]{2}/g;
		pole3 = Date.parse(pole3.match(reg));
		pole4 = Date.parse(pole4.match(reg));
		
		pole5 = document.getElementById("login"+i).innerHTML;
		pole6 = document.getElementById("name"+i).innerHTML;
		pole7 = document.getElementById("email"+i).innerHTML;
		var wzor = new RegExp("^.*("+textsearch+").*$","i");
		
		
		if((pole1 == value1 || value1 == "all") &&
		(pole2 == value2 || value2 == "all") &&
		(pole3>=value3 || isNaN(value3)) &&
		(pole3<=value4 || isNaN(value4)) &&
		(pole4>=value5 || isNaN(value5)) &&
		(pole4<=value6 || isNaN(value6)) &&
		(wzor.test(pole5) || wzor.test(pole6) || wzor.test(pole7))) {
			document.getElementById(wiersz).style.display = "table-row";
			licznik++;
		}
		else {
			document.getElementById(wiersz).style.display = "none";
		}
	}
	document.getElementById("suma").innerHTML = licznik;
}
</script>
</body>
</html>

<script>
function disabledOption() {
	var x = document.getElementById("users_can_accepted").checked;
	if (x == false) {
		document.getElementById("default_user_role").options[1].disabled = true;
	}
	if (x == true) {
		if (document.getElementById("default_user_role").selectedIndex == 1) {
			document.getElementById("default_user_role").selectedIndex = 2;
		}
		document.getElementById("default_user_role").options[1].disabled = false;
	}
}
disabledOption();

function tooglePanel(object) {
	var x = object.parentElement;
	if (x.className.indexOf("panel-on") == -1) {
        x.className += " panel-on";
    } else { 
        x.className = x.className.replace(" panel-on", "");
    }
}
</script>