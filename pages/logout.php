<?php
define("ALLOW_INCLUDE", "yes");
define("PATH", "/demo/my-notes");
session_start();
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/functions.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/autostart.php');

if (isset($_SESSION['user_login'])) {
	if (isset($_COOKIE['autologin_key'])) {
		parse_str($_COOKIE['autologin_key']); //user //key
		setcookie("autologin_key", $key, time() - 3600,"/");
		$con = connect_database();
		if ($con !== false) {
			$query = "DELETE FROM `ms_autologin` WHERE autologin_user='".$_SESSION["user_login"]."'";
			@mysqli_query($con,$query);
		}
	}
	unset($_SESSION['user_login']);
	$alert = alert('success','Wylogowanie zakończone.');
}

include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');
?>

	<div id="content">
<?php
if ($alert == '') {
	check_access(true);
} else {
	echo $alert;
}
?>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>