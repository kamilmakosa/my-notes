<?php
if(!defined("ALLOW_INCLUDE"))	die('Access forbidden');
?>
	<div id="footer">
		<footer id="footergrey">
			<ul>
				<li><a href="regulations/"><?php echo __('Regulations');?></a></li>
				<li><a href="privacy-policy/"><?php echo __('Polityka prywatności');?></a></li>
				<li><a href="report-mistake/"><?php echo __('Zgłoś błąd');?></a></li>
			</ul>
		</footer>
		<footer id="footerblack">© <!--2017- --><?php echo date("Y");?> Copyright <a href=""><?php echo 'mynotes.pl';?></a></footer>
	</div>
</div> <!-- END OF DIV id='page'-->

<?php if(!isset($_COOKIE['cookies_panel_accept'])) { ?>
<div id="cookies-panel">
	<span><?php echo __('Polityka cookies');?></span>
	<span id="cookies-panel-close" onclick="cookiesInfoClose();">×</span>
</div>
<?php } ?>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/language_panel.php');
?>

</body>
</html>
