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
		$query = "SELECT * FROM ms_notes WHERE notes_owner='$user_login'";
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

		$notes_per_site = get_user_info('user_notes_ps');
		if ($notes_per_site == 'all') {
			$notes_start = 1;
			$notes_end = $rowcount;
		} else {
			$notes_start = ($page-1)*$notes_per_site+1;
			$notes_end = ($page-1)*$notes_per_site+$notes_per_site;
		}
	}
}

if (isset($operation)) {
	if ($operation == 'up' && $position != 1) {
		$position2 = $position-1;
		$query = "UPDATE `ms_notes` SET notes_position='0' WHERE notes_owner='$user_login' AND notes_position='$position'";
		$result = mysqli_query($con,$query);
		$query = "UPDATE `ms_notes` SET notes_position='$position' WHERE notes_owner='$user_login' AND notes_position='$position2'";
		$result = mysqli_query($con,$query);
		$query = "UPDATE `ms_notes` SET notes_position='$position2' WHERE notes_owner='$user_login' AND notes_position='0'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','Przesunięto notatkę.');
		redirect('/notes/');
	}

	if ($operation == 'down' && $position != $rowcount) {
		$position2 = $position+1;
		$query = "UPDATE `ms_notes` SET notes_position='0' WHERE notes_owner='$user_login' AND notes_position='$position'";
		$result = mysqli_query($con,$query);
		$query = "UPDATE `ms_notes` SET notes_position='$position' WHERE notes_owner='$user_login' AND notes_position='$position2'";
		$result = mysqli_query($con,$query);
		$query = "UPDATE `ms_notes` SET notes_position='$position2' WHERE notes_owner='$user_login' AND notes_position='0'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','Przesunięto notatkę.');
		redirect('/notes/');
	}
	if ($operation == 'delete') {
		$query = "DELETE FROM `ms_notes` WHERE notes_owner='$user_login' AND notes_position='$position'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','Usunięto notatkę.');
		$query = "SELECT * FROM `ms_notes` WHERE notes_owner='$user_login' AND notes_position>'$position' ORDER BY notes_position ASC";
		$result = mysqli_query($con,$query);
		while ($array = mysqli_fetch_assoc($result)) {
			$query = "UPDATE `ms_notes` SET notes_position='$position' WHERE notes_owner='$user_login' AND notes_position='".($position+1)."'";
			$result2 = mysqli_query($con,$query);
			$position++;
		}
		redirect('/notes/');
	}
	if ($operation == 'lock') {
		$query = "UPDATE `ms_notes` SET notes_public='private' WHERE notes_owner='$user_login' AND notes_position='$position'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','Zablokowano notatkę. Status: prywatna.');
		redirect('/notes/');
	}
	if ($operation == 'unlock') {
		$query = "UPDATE `ms_notes` SET notes_public='public' WHERE notes_owner='$user_login' AND notes_position='$position'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert']= alert('success','Odblokowano notatkę. Status: publiczna.');
		redirect('/notes/');
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

if ($con != false && isset($_SESSION['user_login'])) {
	$query = "SELECT * FROM ms_notes WHERE notes_owner='$user_login'";
	$result = mysqli_query($con,$query);
	$rowcount = mysqli_num_rows($result);

	$query = "SELECT * FROM ms_notes WHERE notes_owner='$user_login' AND notes_position BETWEEN '$notes_start' AND '$notes_end' ORDER BY notes_position ASC";
	$result = mysqli_query($con,$query);
?>
		<div class="window table-list" id="window-notes">
			<h2><?php echo __('Notes'); ?></h2>
			<div id="table-searcher">
				<span class="left" title="<?php echo __('Add new note'); ?>"><?php echo icon('add_box','notes/new'); ?></span>
				<input type="text" class="search" name="search" onkeyup="search(this)" placeholder="<?php echo __('Search'); ?>...">
				<span class="right" title="<?php echo __('Version'); ?>: HTML"><?php echo icon('view_list','html/notes'); ?></span>
			</div>
			<div id="table-news-list"><table>
				<thead>
					<tr>
						<th style="width: 20px;">#</th>
						<th><?php echo __('Name'); ?></th>
						<th class="hidden-sm" style="width: 164px;"><?php echo __('Date'); ?></th>
						<th class="hidden-sm" style="width: 164px;"><?php echo __('Modification date'); ?></th>
						<th class="hidden-sm" style="width: 15%;"><?php echo __('Tags'); ?></th>
						<th style="width: 12%;"><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>
<?php
	while ($array = mysqli_fetch_assoc($result)) {
		$date = $array['notes_date'];
		$date_mod = $array['notes_modified'];
		if ($date == '0000-00-00 00:00:00') 	{ $date = ''; }
		if ($date_mod == '0000-00-00 00:00:00')	{ $date_mod = ''; }
?>
					<tr id="rows<?php echo $array['notes_position']; ?>">
						<td><?php echo $array['notes_position']; ?></td>
						<td id="name<?php echo $array['notes_position']; ?>" class="notes-link" onclick="window.location='notes/id/<?php echo$array['notes_ID']; ?>'"><?php echo $array['notes_name']; ?></td>
						<td class="hidden-sm"><?php echo $date; ?></td>
						<td class="hidden-sm"><?php echo $date_mod; ?></td>
						<td class="hidden-sm" id="tags<?php echo $array['notes_position']; ?>"><?php echo $array['notes_tags']; ?></td>
						<td class="icons-operation" id='operation<?php echo $array['notes_position']; ?>'>
<?php if ($array['notes_public'] == 'private') { ?>
							<span title="<?php echo __('Unlock'); ?>"><?php echo icon('lock_open','notes/unlock/'.$array['notes_position']); ?></span>
<?php } if ($array['notes_public'] == 'public') { ?>
							<span title="<?php echo __('Lock'); ?>"><?php echo icon('lock','notes/lock/'.$array['notes_position']); ?></span>
<?php } if ($array['notes_position'] != 1) { ?>
							<span title="<?php echo __('Up'); ?>"><?php echo icon('arrow_upward','notes/up/'.$array['notes_position']); ?></span>
<?php } else { ?>
							<span style="visibility: hidden;"><?php echo icon('arrow_upward'); ?></span>
<?php } if ($array['notes_position'] != $rowcount) { ?>
							<span title="<?php echo __('Down'); ?>"><?php echo icon('arrow_downward','notes/down/'.$array['notes_position']); ?></span>
<?php } else { ?>
							<span style="visibility: hidden;"><?php echo icon('arrow_downward'); ?></span>
<?php } ?>
							<span title="<?php echo __('Open'); ?>"><?php echo icon('description','notes/id/'.$array['notes_ID']); ?></span>
							<span title="<?php echo __('Edit'); ?>"><?php echo icon('edit','notes/edit/'.$array['notes_position']); ?></span>
							<span title="<?php echo __('Delete'); ?>"><?php echo icon('delete','notes/delete/'.$array['notes_position']); ?></span>
						</td>
					</tr>
<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="8"><a href="notes/new"><?php echo __('Add new note'); ?></a></td>
					</tr>
				</tfoot>
			</table></div>
<?php
	if ($notes_per_site != 'all') {
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
				<a href="notes/1" title="<?php echo __('To first page'); ?>">«</a>
<?php
		}
		while ($page <= $maxpage) {
			if ($page >0 && $page<=ceil(get_user_stats('notes')/$notes_per_site)) {
				$activeclass = '';
				if ($activepage == $page) {
					$activeclass = ' class="active"';
				}
?>
					<a href="notes/<?php echo $page; ?>"<?php echo $activeclass; ?>><?php echo $page; ?></a>
<?php
			}
			$page++;
		}
		if ($activepage+1<=ceil(get_user_stats('notes')/$notes_per_site)) {
?>
					<a href="notes/<?php echo ceil(get_user_stats('notes')/$notes_per_site) ?>" title="<?php echo __('To last page'); ?>">»</a>
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
