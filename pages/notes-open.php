<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');

$splitURL = split_url();
$page = 'notes';
$notes_ID = @$splitURL[2];
$notes_ID = str_replace(array('/','\\'), '', $notes_ID);
if($notes_ID < 1) {
	return include($_SERVER['DOCUMENT_ROOT'].PATH.'/404.php');
}
$user = @$_SESSION['user_login'];

$con = connect_database();
if ($con == false ) return false;
?>

<div id="content">
<?php
//check_access(true); //DOSTĘP TYLKO DLA ZALOGOWANYCH
//echo @$alert;

$query = "SELECT * FROM `ms_notes` WHERE notes_ID='$notes_ID';";
$result = mysqli_query($con,$query);
$array = mysqli_fetch_assoc($result);

if ($array['notes_owner'] != $user && $array['notes_public'] != 'public') {
	echo alert('danger','Brak dostępu');
	echo '</div>';
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/section/footer.php');
	exit;
}
else {
	echo '<div class="window" id="window-news">';
}


if ($array['notes_public'] == 'public') {
	$public = 'publiczna';
}
else {
	$public = 'niepubliczna';
}
$notes_date = date(get_option('news_date_format'),strtotime($array['notes_date']));
echo '<article>'."\n";
echo "\t".'<h2><a href="note/'.$array['notes_ID'].'">'.$array['notes_name'].'</a></h2>'."\n";
echo "\t".'<span class="news-date">'.$public.', '.$notes_date.'</span>'."\n";
echo "\t".'<div class="notes-content">'.nl2br($array['notes_content']).'</div>'."\n";
echo '</article>'."\n";
?>
	</div>
</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>
