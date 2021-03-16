<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');

if (isset($_SESSION['user_login'])) {
	$user_login = $_SESSION['user_login'];
	$splitURL = split_url();
	$notes_position = @$splitURL[2];
	$notes_position = str_replace(array('/','\\'), '', $notes_position);

	if (isset($_POST['note_name']) && isset($_POST['note_tags']) && isset($_POST['note_content'])) {
		if(!empty($_POST['note_name']) && !empty($_POST['note_content'])) {
			$note_name = $_POST["note_name"];
			$note_tags = $_POST["note_tags"];
			$note_content = $_POST["note_content"];
			$note_public = 'private';
			if (isset($_POST['note_public'])) {
				$note_public = 'public';
			}
			$con = @connect_database();
			if ($con == false ) {
				$alert = alert('warning','Nie możemy zapisać notatki.');
				echo mysqli_error();
			}
			else {
				$query = "UPDATE ms_notes SET notes_name='$note_name', notes_content='$note_content', notes_public='$note_public', notes_modified='".get_datetime()."', notes_modified_gmt='".get_datetime_GMT()."', notes_tags='$note_tags' WHERE notes_owner='$user_login' AND notes_position='$notes_position'";
				if (!mysqli_query($con,$query)) {
					$alert = alert('warning','Nie możemy zapisać notatki.');
					echo mysqli_error($con);
				} else {
					$_SESSION['notes_alert'] = alert('success','Zapisano zmiany.');
					redirect('/notes/');
				}
			}
		}
		else {
			$alert = alert('warning','Nie wypełniono nazwy lub treści notatki.');
		}
	}

	if($notes_position < 1) {
		return include($_SERVER['DOCUMENT_ROOT'].PATH.'/404.php');
	}
	$con = connect_database();
	if ($con == false ) return false;

	$query = "SELECT * FROM `ms_notes` WHERE notes_position='$notes_position' AND notes_owner='$user_login';";
	$result = mysqli_query($con,$query);
	$array = mysqli_fetch_assoc($result);
}
?>

<div id="content">
	<?php
	if(!isset($_SESSION['user_login'])) {
		echo alert('danger','Brak dostępu');
		echo '</div>';
		include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
		exit;
	}
	echo @$alert;
	?>
	<div class="window" id="window-newnote">
	<h2>Edit note</h2>
	<form id="newnote-form" method="post" action="" onsubmit="return validatenewnoteform()">
	<table class="form-table"><tbody>
		<tr>
			<th scope="row"><label for="note_name">Note name</label></th>
			<td><input type="text" name="note_name" id="note_name" size="25" value="<?php echo $array['notes_name']?>"></td>
		</tr>
		<tr>
			<th scope="row"><label for="note_public">Public note</label></th>
			<td><input type="checkbox" name="note_public" id="note_public" value="note_public" size="25" <?php if($array['notes_public'] == 'public') { echo 'checked';  }?>></td>
		</tr>
		<tr>
			<th scope="row"><label for="note_tags">Tags</label></th>
			<td><input type="text" name="note_tags" id="note_tags" size="25" value="<?php echo $array['notes_tags']?>">
			<p><span class="description important"><strong>Important:</strong>
			Please use comma to separate tags.</span></p></td>
		</tr>
		<tr>
			<th scope="row"><label for="note_content">Content</label></th>
			<td><textarea name="note_content" id="note_content" rows="" cols=""><?php echo $array['notes_content']?></textarea></td>
		</tr>
	</tbody></table>
	<p class="sendformblock">
		<input type="submit" name="submit" id="submit" value="Save note">
	</p>
	</form>
	</div>

	<div class="alert" id="warning_alert" data-type="warning" style="display: none;">
		<span onclick="this.parentElement.style.display='none'" class="alert_close">&times;</span>
		<h3>Warning!</h3>
		<p id="warnign_alert_newnote"></p>
	</div>
</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>
