<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');

if (isset($_SESSION['user_login'])) {
	$con = connect_database();
	if ($con == false ) {
		$alert = alert('info',__('Przepraszamy, nie możemy pobrać zawartości strony.'));
	} else {
		$user_login = @$_SESSION['user_login'];
		$query = "SELECT * FROM ms_bookmarks WHERE bookmark_owner='$user_login'";
		$result = mysqli_query($con,$query);
		$rowcount = mysqli_num_rows($result);

		$splitURL = split_url();
		if (@$splitURL[2] == '') {
			$page = 1;
		} elseif (@$splitURL[2] > 0) {
			$page = $splitURL[2];
		} else {
			$operation = $splitURL[2];
			$position = @$splitURL[3];
			$page = 1;
		}

		$bookmarks_per_site = get_user_info('user_bookmarks_ps');
		if ($bookmarks_per_site == 'all') {
			$position_start = 1;
			$position_end = $rowcount;
		} else {
			$position_start = ($page-1)*$bookmarks_per_site+1;
			$position_end = ($page-1)*$bookmarks_per_site+$bookmarks_per_site;
		}
	}
}

if (isset($operation)) {
	if ($operation == 'up' && $position != 1) {
		$position2 = $position-1;
		$query = "UPDATE `ms_bookmarks` SET bookmark_position='0' WHERE bookmark_owner='$user_login' AND bookmark_position='$position'";
		$result = mysqli_query($con,$query);
		$query = "UPDATE `ms_bookmarks` SET bookmark_position='$position' WHERE bookmark_owner='$user_login' AND bookmark_position='$position2'";
		$result = mysqli_query($con,$query);
		$query = "UPDATE `ms_bookmarks` SET bookmark_position='$position2' WHERE bookmark_owner='$user_login' AND bookmark_position='0'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','Przesunięto notatkę.');
		redirect('/bookmarks/');
	}

	if ($operation == 'down' && $position != $rowcount) {
		$position2 = $position+1;
		$query = "UPDATE `ms_bookmarks` SET bookmark_position='0' WHERE bookmark_owner='$user_login' AND bookmark_position='$position'";
		$result = mysqli_query($con,$query);
		$query = "UPDATE `ms_bookmarks` SET bookmark_position='$position' WHERE bookmark_owner='$user_login' AND bookmark_position='$position2'";
		$result = mysqli_query($con,$query);
		$query = "UPDATE `ms_bookmarks` SET bookmark_position='$position2' WHERE bookmark_owner='$user_login' AND bookmark_position='0'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','Przesunięto notatkę.');
		redirect('/bookmarks/');
	}
	if ($operation == 'delete') {
		$query = "DELETE FROM `ms_bookmarks` WHERE bookmark_owner='$user_login' AND bookmark_position='$position'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','Usunięto notatkę.');
		$query = "SELECT * FROM `ms_bookmarks` WHERE bookmark_owner='$user_login' AND bookmark_position>'$position' ORDER BY bookmark_position ASC";
		$result = mysqli_query($con,$query);
		while ($array = mysqli_fetch_assoc($result)) {
			$query = "UPDATE `ms_bookmarks` SET bookmark_position='$position' WHERE bookmark_owner='$user_login' AND bookmark_position='".($position+1)."'";
			$result2 = mysqli_query($con,$query);
			$position++;
		}
		redirect('/bookmarks/');
	}
}

if (isset($_POST['bookmark_name']) && isset($_POST['bookmark_tags']) && isset($_POST['bookmark_link']) && isset($_SESSION['user_login']) && isset($_POST['submit'])) {
	if(!empty($_POST['bookmark_name']) && !empty($_POST['bookmark_link'])) {
		$bookmark_name = $_POST["bookmark_name"];
		$bookmark_tags = $_POST["bookmark_tags"];
		$bookmark_link = $_POST["bookmark_link"];

		if($bookmark_link != "") {
			if(!preg_match('#^http[s]?:\/\/#i', $bookmark_link)) {
				$bookmark_link = 'http://' . $bookmark_link;
			}
			if(!preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $bookmark_link)) {
				//$bookmark_link = '';
			}
		}

		$con = connect_database();
		if ($con == false ) {
			$alert = alert('warning','Nie możemy zapisać zakładki.');
		} else {
			if ($_POST['bookmarktype']=='new') {
				$query = "INSERT INTO `ms_bookmarks` (`bookmark_name`, `bookmark_owner`, `bookmark_link`, `bookmark_position`, `bookmark_date`, `bookmark_date_gmt`, `bookmark_tags`) VALUES ('".htmlspecialchars($bookmark_name,ENT_QUOTES)."', '".$_SESSION['user_login']."', '$bookmark_link', '".(get_user_stats('bookmarks')+1)."', '".get_datetime()."', '".get_datetime_GMT()."', '$bookmark_tags');";
				if (!mysqli_query($con,$query)) {
					$alert = alert('warning','Nie możemy zapisać zakładki.');
				} else {
					$_SESSION['notes_alert'] = alert('success','Zapisano nową zakładkę.');
					redirect('/bookmarks/');
					exit;
				}
			} elseif ($_POST['bookmarktype']=='edit') {
				$query = "UPDATE ms_bookmarks SET bookmark_name='$bookmark_name', bookmark_link='$bookmark_link', bookmark_tags='$bookmark_tags' WHERE bookmark_owner='$user_login' AND bookmark_position='$position'";
				if (!mysqli_query($con,$query)) {
					$alert = alert('warning','Nie możemy zapisać zakładki.');
				} else {
					$_SESSION['notes_alert'] = alert('success','Zapisano zmiany w zakładce.');
					redirect('/bookmarks/');
					exit;
				}
			} else {
				$alert = alert('danger','Błąd przetwarzania strony.');
			}
		}
	} else {
		$alert = alert('danger','Pusta nazwa lub link.');
	}
}
?>

	<div id="content">
<?php
if(isset($_SESSION['notes_alert'])) {
	echo $_SESSION['notes_alert'];
	unset($_SESSION['notes_alert']);
}
check_access(true); //DOSTĘP TYLKO DLA ZALOGOWANYCH
echo @$alert;

if (@$operation == 'new' || @$operation == 'edit') {
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/bookmark.php');
}

if ($con != false && isset($_SESSION['user_login'])) {
	$query = "SELECT * FROM ms_bookmarks WHERE bookmark_owner='$user_login'";
	$result = mysqli_query($con,$query);
	$rowcount = mysqli_num_rows($result);

	$query = "SELECT * FROM ms_bookmarks WHERE bookmark_owner='$user_login' AND bookmark_position BETWEEN '$position_start' AND '$position_end' ORDER BY bookmark_position ASC";
	$result = mysqli_query($con,$query);
?>
		<div class="window table-list" id="window-bookmarks">
			<h2><?php echo __('Bookmarks'); ?></h2>
			<div id="table-searcher">
				<span class="left" title="<?php echo __('Add new bookmark'); ?>"><?php echo icon('add_box','bookmarks/new'); ?></span>
				<input type="text" class="search" name="search" onkeyup="search(this)" placeholder="<?php echo __('Search'); ?>...">
				<span class="right" title="<?php echo __('Version'); ?>: HTML"><?php echo icon('view_list','html/bookmarks'); ?></span>
			</div>
			<div id="table-news-list"><table>
				<thead>
					<tr>
						<th style="width: 20px;">#</th>
						<th><?php echo __('Name'); ?></th>
						<th class="hidden-sm" style="width: 164px;"><?php echo __('Date'); ?></th>
						<th class="hidden-sm" style="width: 15%;"><?php echo __('Tags'); ?></th>
						<th style="width: 12%;"><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>
<?php
	while ($array = mysqli_fetch_assoc($result)) {
		$date = $array['bookmark_date'];
		if ($date == '0000-00-00 00:00:00') 	{ $date = ''; }
?>
					<tr id="rows<?php echo $array['bookmark_position']; ?>">
						<td><?php echo $array['bookmark_position']; ?></td>
						<td class="bookmark-link" onclick="window.open('<?php echo $array['bookmark_link']; ?>')"><a href='<?php echo $array['bookmark_link']; ?>' target="blank" id='name<?php echo $array['bookmark_position']; ?>'><?php echo $array['bookmark_name']; ?></a></td>
						<td class="hidden-sm"><?php echo $date; ?></td>
						<td id='tags<?php echo $array['bookmark_position']; ?>' class="hidden-sm"><?php echo $array['bookmark_tags']; ?></td>
						<td class="icons-operation" id='operation<?php echo $array['bookmark_position']; ?>'>
<?php if ($array['bookmark_position'] != 1) { ?>
							<span title="<?php echo __('Up'); ?>"><?php echo icon('arrow_upward','bookmarks/up/'.$array['bookmark_position']); ?></span>
<?php } else { ?>
							<span style="visibility: hidden;"><?php echo icon('arrow_upward'); ?></span>
<?php } if ($array['bookmark_position'] != $rowcount) { ?>
							<span title="<?php echo __('Down'); ?>"><?php echo icon('arrow_downward','bookmarks/down/'.$array['bookmark_position']); ?></span>
<?php } else { ?>
							<span style="visibility: hidden;"><?php echo icon('arrow_downward'); ?></span>
<?php } ?>
							<span title="<?php echo __('Edit'); ?>"><?php echo icon('edit','bookmarks/edit/'.$array['bookmark_position']); ?></span>
							<span title="<?php echo __('Delete'); ?>"><?php echo icon('delete','bookmarks/delete/'.$array['bookmark_position']); ?></span>
						</td>
					</tr>
<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="8"><a href="bookmarks/new"><?php echo __('Add new bookmark'); ?></a></td>
					</tr>
				</tfoot>
			</table></div>
<?php
	if ($bookmarks_per_site != 'all') {
		$minpage = $page - 3;
		$activepage = $page;
		$maxpage = $page + 3;
		$page = $minpage;
?>
			<div class="pagination-section">
				<div class="pagination">
<?php
		if ($activepage-1 > 0) {
?>
					<a href="bookmarks/1" title="<?php echo __('To first page'); ?>">«</a>
<?php
		}
		while ($page <= $maxpage) {
			if ($page >0 && $page<=ceil(get_user_stats('bookmarks')/$bookmarks_per_site)) {
				$activeclass = '';
				if ($activepage == $page) {
					$activeclass = ' class="active"';
				}
?>
					<a href="bookmarks/<?php echo $page; ?>"<?php echo $activeclass; ?>><?php echo $page; ?></a>
<?php
				}
			$page++;
		}
		if ($activepage+1<=ceil(get_user_stats('bookmarks')/$bookmarks_per_site)) {
?>
					<a href="bookmarks/<?php echo ceil(get_user_stats('bookmarks')/$bookmarks_per_site); ?>" title="<?php echo __('To last page'); ?>">»</a>
<?php } ?>
				</div>
			</div>
<?php } ?>
		</div>
<?php } ?>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>
