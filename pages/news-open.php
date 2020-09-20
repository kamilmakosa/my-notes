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
$con = connect_database();
if ($con === false) {
	echo alert('info','Przepraszamy, nie możemy pobrać zawartości strony. Spróbuj później.');
} else {
	$splitURL = explode('/', substr($_SERVER['REQUEST_URI'], strlen(PATH)));
	$idnews = @$splitURL[3];

	$query = "SELECT * FROM `ms_news` WHERE news_status='public' OR news_status='publish_only-user' ORDER BY news_ID DESC;";
	$result = mysqli_query($con,$query);
	$rowcount = mysqli_num_rows($result);

	$query = "SELECT * FROM `ms_news` WHERE news_ID='$idnews';";
	$result = mysqli_query($con,$query);
	$array = mysqli_fetch_assoc($result);

	if (mysqli_num_rows($result) == 0) {
		echo alert('warning', "Szukany news nie istnieje.");
	} else {
		if (!isset($_SESSION['user_login']) && $array['news_status'] != 'public') {
			echo alert('danger','News widoczny tylko dla zalogowanych użytkowników.');
		} else {
			$text = '';
			$news_date = date(get_option('news_date_format'),strtotime($array['news_date']));
			$text .= '<div class="window" id="window-news">';
			$text .= '<article>'."\n";
			$text .= "\t".'<h2><a href="news-'.$array['news_ID'].'/">'.$array['news_title'].'</a></h2>'."\n";
			$text .= "\t".'<span class="news-date">'.$array['news_day'].', '.$news_date.'</span>'."\n";
			$text .= "\t".'<div class="news-content">'.$array['news_content'].'</div>'."\n";
			$text .= '</article>'."\n";
			$text .= '</div>';
			echo $text;
		}
	}
}
?>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>
