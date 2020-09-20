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
		<div class="window" id="window-home">
		<p class="news-content"><?php echo __('Tekst na stronie głównej');?></p>
		</div>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>
