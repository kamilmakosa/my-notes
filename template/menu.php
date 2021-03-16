<?php
if (!defined("ALLOW_INCLUDE"))	die('Access forbidden');
if (!@$page) {
	$splitURL = split_url();
	if ($splitURL[0]) {
		$page = $splitURL[0];
	}
}
if (@$page == '')	$page = 'home';
?>

<body>
<div id="page">
	<div id="opacity" onClick="menu()"></div>
	<div id="menu-mobile-panel">
		<ul>
			<li><a href="">MÓJ SCHOWEK</a></li>
			<li style="float: right;" onClick="menu()"><a class="mobile-menu-toggle"><span></span><span></span><span></span></a></li>
		</ul>
	</div>
	<div id="menu">
		<ul>
			<li><a <?php if($page=="home") { echo 'class="active" ';}?>href="home/"><?php echo __('Home');?></a></li>
			<li><a <?php if($page=="news") { echo 'class="active" ';} ?>href="news/"><?php echo __('News');?></a></li>
			<li><a <?php if($page=="contact") { echo 'class="active" ';} ?>href="contact/"><?php echo __('Contact');?></a></li>
			<li class="empty"></li>
<?php
if (!isset($_SESSION['user_login'])) {
?>
			<li><a <?php if($page=="signin") { echo 'class="active" ';}?>href="signin/"><?php echo __('Sign in');?></a></li>
			<li><a <?php if($page=="joinnow") { echo 'class="active" ';}?>href="joinnow/"><?php echo __('Join now');?></a></li>
<?php
}
if (isset($_SESSION['user_login'])) {
?>
			<li><a <?php if($page=="bookmarks") { echo 'class="active" ';}?>href="bookmarks/"><?php echo __('Bookmarks');?></a></li>
			<li><a <?php if($page=="notes") { echo 'class="active" ';}?>href="notes/"><?php echo __('Notes');?></a></li>
			<li><a <?php if($page=="calendar") { echo 'class="active" ';}?>href="calendar/"><?php echo __('Calendar');?></a></li>
			<li class="empty"></li>
			<li><a <?php if($page=="myaccount") { echo 'class="active" ';}?>href="myaccount/"><?php echo __('My account');?></a></li>
<?php if (isset($_SESSION['user_login']) && get_user_info('user_name') == 'Administrator' & 0==1) { ?>
			<li><a href="ap/"><?php echo __('Administration Panel');?></a></li>
<?php } ?>
			<li><a <?php if($page=="logout") { echo 'class="active"';}?>href="logout/"><?php echo __('Log out');?></a></li>
<?php
}
?>
		</ul>
	</div>
<?php if (isset($_SESSION['user_login']) && get_user_info('user_name') == 'Administrator' && 1 == 1) { ?>
	<a href="ap/"><div id="ap_entry"><?php echo __('AP Entry');?></div></a>
<?php } ?>
