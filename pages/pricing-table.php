<?php
define("ALLOW_INCLUDE", "yes");
define("PATH", "/demo/my-notes");
session_start();
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/functions.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/autostart.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');
?>

	<div id="content">
<?php
//check_access(); //DOSTĘP TYLKO DLA ZALOGOWANYCH
if (get_option('default_user_role') != 'non-actived user') {
	echo alert('info','Aktywacja konta nie jest wymagana.');	
	echo '</div>';
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
	exit;
}
echo @$alert;
?>
		<div id="pricing-table" class="window">
			<div class="columns">
				<ul class="price">
					<li class="header">Basic</li>
					<li class="grey">free</li>
					<li>20 Bookmarks</li>
					<li>20 Notes</li>
					<li></li>
					<li class="grey"><a href="#" class="button">Sign Up</a></li>
				</ul>
			</div>
			<div class="columns">
				<ul class="price">
					<li class="header" style="background-color:#4CAF50">Pro</li>
					<li class="grey">$ 4.99 / year</li>
					<li>50 Bookmarks</li>
					<li>50 Notes</li>
					<li>No ads</li>
					<li class="grey"><a href="#" class="button">Sign Up</a></li>
				</ul>
			</div>
			<div class="columns">
				<ul class="price">
					<li class="header">Premium</li>
					<li class="grey">$ 9.99 / year</li>
					<li>Unlimited Bookmarks</li>
					<li>Unlimited Notes</li>
					<li>No ads</li>
					<li class="grey"><a href="#" class="button">Sign Up</a></li>
				</ul>
			</div>
		</div>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>