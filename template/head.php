<?php
if(!defined("ALLOW_INCLUDE"))	die('Access forbidden');
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
<head>
	<title><?php echo get_title(); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="description" content="Mój schowek w Internecie">
	<meta name="keywords" content="bookmarks, notes">
	<meta name="author" content="Kamil Mąkosa">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="/demo/my-notes/">
	<link rel="shortcut icon" type="image/png" href="images/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="styles/css_reset.css">
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
	<link rel="stylesheet" type="text/css" href="styles/styles_form.css">
	<link rel="stylesheet" type="text/css" href="styles/styles_lang.css">
	<link rel="stylesheet" type="text/css" href="styles/styles_menu.css">
	<link rel="stylesheet" type="text/css" href="styles/styles_table.css">
	<link rel="stylesheet" type="text/css" href="styles/styles_text.css">
	<link rel="stylesheet" type="text/css" href="styles/styles_window.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type='text/javascript' src='scripts/cookies-panel.js'></script>
	<script type='text/javascript' src='scripts/language.js'></script>
	<script type='text/javascript' src='scripts/menu.js'></script>
	<script type='text/javascript' src='scripts/search.js'></script>
	<script type='text/javascript' src='scripts/slideToggle.js'></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
</head>
