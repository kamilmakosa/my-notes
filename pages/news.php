<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');
// include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/functions.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/autostart.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');
?>

	<div id="content">
<?php
$con = connect_database();
if ($con === false) {
	echo alert('info',__('Przepraszamy, nie możemy pobrać zawartości strony.'));
} else {
	$query = "SELECT * FROM `ms_news` WHERE news_status='public' ORDER BY news_ID DESC;";
	if (isset($_SESSION['user_login'])) {
		$query = "SELECT * FROM `ms_news` WHERE news_status='public' OR news_status='publish_only_user' ORDER BY news_ID DESC;";
	}
	$result = mysqli_query($con,$query);
	$rowcount = mysqli_num_rows($result);
	if ($rowcount == 0) {
		echo alert('info',__('Brak newsów do wyświetlenia.'));
	} else {
		$news_per_site = get_option('news_per_site');
		if (isset($_SESSION['user_login']) && get_user_info('user_news_ps')!='default') {
			$news_per_site = get_user_info('user_news_ps');
		}

		$splitURL = explode('/', substr($_SERVER['REQUEST_URI'], strlen(PATH)));
		$page = @$splitURL[2];
		if ($page == "") { $page = 1; }

		if ($page-1 >= ceil($rowcount/$news_per_site)) {
			header("Location: /news");
			exit;
		}

		$news_start = 1+($page-1)*$news_per_site;
		$news_end = $news_per_site+($page-1)*$news_per_site;
?>
		<div class="window" id="window-news">
<?php
		$counter = 1;
		while ($array = mysqli_fetch_assoc($result)) {
			if ($counter>=$news_start &&$counter<=$news_end) {
				$news_date_GMT = date(get_option('news_date_format'),strtotime($array['news_date_gmt']));
				$news_date = convert_datetime('UTC', $_SESSION['timezone'], $news_date_GMT);
?>
			<article>
				<h2><a href="news/id/<?php echo $array['news_ID']; ?>"><?php echo $array['news_title']; ?></a></h2>
				<span class="news-date"><?php echo __($array['news_day']).', '.$news_date; ?></span>
				<div class="news-content"><?php echo $array['news_excerpt']; ?></div>
				<a class="newslink" id="newslink" href="news/id/<?php echo $array['news_ID']; ?>"><?php echo __('Czytaj dalej'); ?></a>
			</article>
<?php
			}
			$counter++;
		}

		$minpage = $page - 3;
		$activepage = $page;
		$maxpage = $page + 3;
		$page = $minpage;

		if($rowcount/$news_per_site > 1) {
?>
			<div class="pagination-section">
				<div class="pagination">
<?php
		if ($activepage-1 >0) {
?>
					<a href="news/<?php echo ($activepage-1); ?>" title="<?php echo __('To first page'); ?>">«</a>
<?php
		}
		while ($page <= $maxpage) {
			if ($page >0 && $page<=ceil($rowcount/$news_per_site)) {
				$activeclass = '';
				if ($activepage == $page) {
					$activeclass = 'active';
				}
?>
					<a href="news/<?php echo $page; ?>" class="<?php echo $activeclass; ?>"><?php echo $page; ?></a>
<?php
			}
			$page++;
		}
		if ($activepage+1<=ceil($rowcount/$news_per_site)) {
?>
					<a href="news/<?php echo ($activepage+1); ?>" title="<?php echo __('To last page'); ?>">»</a>
<?php
		}
?>
				</div>
			</div>
<?php
		}
?>
		</div>
<?php
	}
}
?>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>
