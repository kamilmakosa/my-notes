<?php
if (!defined("ALLOW_INCLUDE"))	die('Access forbidden');
if (!@$page) {
	$splitURL = explode('/', substr($_SERVER['REQUEST_URI'], strlen(PATH)));
	$page = $splitURL[2];
}
if ($page == "")	$page = "start";
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
			<li><a <?php if($page=='start') { echo 'class="active" ';} ?>href="ap/">Start</a></li>
			<li><a <?php if($page=='settings') { echo 'class="active" ';} ?>href="ap/settings/">Settings</a></li>
			<li><a <?php if($page=='news') { echo 'class="active" ';} ?>href="ap/news/">News</a></li>
			<li><a <?php if($page=='users') { echo 'class="active" ';} ?>href="ap/users/">Users</a></li>
			<li><a <?php if($page=='message') { echo 'class="active" ';} ?>href="ap/message/">Message</a></li>
			<li><a <?php if($page=='logs') { echo 'class="active" ';} ?>href="ap/logs/">Logs</a></li>
			<li><a href="">AP Exit</a></li>
		</ul>
	</div>
