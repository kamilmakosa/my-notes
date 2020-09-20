<?php
define("ALLOW_INCLUDE", "yes");
define("PATH", "/demo/my-notes");
if ($_SERVER['SCRIPT_NAME'] == '/template/mail_reset_password.php') {
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/functions.php');
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/autostart.php');
}
?><style>
/* latin-ext */
@font-face {
  font-family: 'Raleway';
  font-style: normal;
  font-weight: 400;
  src: local('Raleway'), local('Raleway-Regular'), url(https://fonts.gstatic.com/s/raleway/v12/yQiAaD56cjx1AooMTSghGfY6323mHUZFJMgTvxaG2iE.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Raleway';
  font-style: normal;
  font-weight: 400;
  src: local('Raleway'), local('Raleway-Regular'), url(https://fonts.gstatic.com/s/raleway/v12/0dTEPzkLWceF7z0koJaX1A.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2212, U+2215;
}

*/ CSS RESET http://meyerweb.com/eric/tools/css/reset/ | v2.0 | 20110126 | License: public domain */

html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section {
	display: block;
}
body {
	line-height: 1;
}

*/ CSS by Mynotes.pl */
html, body {
	margin:0;
	padding:0;
	height:100%;
}

html {
    overflow: -moz-scrollbars-vertical; 
    overflow-y: scroll;
}
	
body {
	background-color:#f1f1f1;
	font-family: 'Raleway', sans-serif;
	color:#000;
	animation: body-opac 0.8s;
}

p {
    line-height: 1.5;
	padding-bottom: 2px;
	margin-top: 16px;
}

h1#title {
	display: block;
	font-size: 36px;
	margin-top: 24px;
	margin-bottom: 16px;
	margin-left: 0;
	margin-right: 0;
	font-weight: bold;
	line-height: normal;
}

h2#description  {
	display: block;
	font-size: 16px;
	margin-top: 16px;
	margin-bottom: 24px;
	margin-left: 0;
	margin-right: 0;
	font-weight: normal;
	line-height: normal;
}

div#content {
	margin: 24px;
}

div#header {
    //max-width: 480px;
    margin: 24px auto;
    text-align: center;
}

h2 {
	border-bottom: 1px solid #ddd;
    clear: both;
    color: #666;
    font-size: 24px;
	padding: 0 0 7px;
	margin: 16px 0;
	position:relative;
	text-decoration: none;
}

.window {
	margin: 24px auto;
	background-color:#fff;
	box-shadow:0 4px 10px 0 rgba(0,0,0,0.2),0 4px 20px 0 rgba(0,0,0,0.19);
	max-width: 800px;
	padding: 20px;
}
</style>

<div id="content">
	<div id="header">
		<h1 id="title"><?php echo __('sitetitle');?></h1>
		<h2 id="description"><?php echo __('sitedescription');?></h2>
	</div>
	<div class="window" id="window-registration">
		<h2>Resetowanie hasła</h2>
		<p style="font-size: 20px; font-weight: bold; text-indent: 20px;">Witaj <?php echo mb_convert_case(@$name,MB_CASE_TITLE, "UTF-8");?>!</p>
		<p style="font-size: 16px;">Otrzymałeś tę wiadomość, ponieważ chcesz zresetować hasło do konta w serwisie <?php echo mb_convert_case(get_domain('http://'.$_SERVER['SERVER_NAME']),MB_CASE_TITLE, "UTF-8"); ?> (jeżeli nie chcesz zresetować hasła - zignoruj tę wiadomość).</p>
		<p style="font-size: 16px; font-weight: bold; ">Aby zresetować hasło wystarczy, że <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/reset-password/?token='.@$token; ?>">klikniesz tutaj</a>!</p>
		<p>Możesz także wypełnić formularz w celu zresetowania hasła, który jest dostępny <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/reset-password/'; ?>">pod tym linkiem</a>.</p>
		<p style="font-size: 16px;"><span style="font-weight: bold;">Token resetujący:</span> <span style="font-style: italic;"><?php echo @$token?></span></p>
		<p>--<br/>
		Pozdrawiamy,<br/>
		Zespół <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/'; ?>"><?php echo mb_convert_case(get_domain('http://'.$_SERVER['SERVER_NAME']),MB_CASE_TITLE, "UTF-8"); ?></a></p>
		<p style="font-size: 12px; font-style: italic;">Jeżeli otrzymałeś tę wiadomość mimo, że nie wypełniałeś formularza w serwisie <?php echo mb_convert_case(get_domain('http://'.$_SERVER['SERVER_NAME']),MB_CASE_TITLE, "UTF-8");?> - prawdopodobne, że ktoś pomylił się lub też z innych powodów bez Twojej wiedzy podał Twój adres e-mail. Zignoruj tę wiadomość i nie klikaj w żaden zawarty w niej link.</p>
		<p style="font-size: 12px; font-style: italic;">Ta wiadomość została wygenerowana automatycznie.</p>
	</div>
</div>