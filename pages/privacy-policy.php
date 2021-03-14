<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');

if(isset($_SESSION['user_login'])) {
	$mail = get_user_info('user_email');
}
?>

	<div id="content">
		<div class="window" id="window-contact">
			<h2><?php echo __('Polityka prywatności');?></h2>
		</div>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>