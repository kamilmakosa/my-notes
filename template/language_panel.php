<?php
if(!defined("ALLOW_INCLUDE"))	die('Access forbidden');
?>
	<div id="lang_panel">
		<button type="button" id="lang_flag">
			<i class="ico-lang-<?php echo $_SESSION['language']; ?>"></i>
		</button>
		<button id="lang_selector" type="button" onClick="languageShow()">
			<span id="caret"></span>
		</button>
		<ul id="dropdown-lang" class="">
			<?php echo get_lang_list(); ?>
		</ul>
	</div>