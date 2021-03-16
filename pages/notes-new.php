<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');

if (isset($_POST['note_name']) && isset($_POST['note_tags']) && isset($_POST['note_content']) && isset($_SESSION['user_login'])) {
	if(!empty($_POST['note_name']) && !empty($_POST['note_content'])) {
		$note_name = $_POST["note_name"];
		$note_tags = $_POST["note_tags"];
		$note_content = $_POST["note_content"];
		$note_public = 'private';
		if (isset($_POST['note_public'])) {
			$note_public = 'public';
		}
		$con = connect_database();
		if ($con == false ) {
			$alert = alert('warning','Nie możemy zapisać notatki.');
		}
		else {
			$query = "INSERT INTO ms_notes (notes_name, notes_owner, notes_content,  notes_public, notes_position, notes_date, notes_date_gmt, notes_tags) VALUES ('$note_name', '".$_SESSION['user_login']."', '$note_content', '$note_public', '".(get_user_stats('notes')+1)."', '".get_datetime()."', '".get_datetime_GMT()."', '$note_tags');";
echo $query;
			if (!mysqli_query($con,$query)) {
				$alert = alert('warning','Nie możemy zapisać notatki.');
			} else {
				$_SESSION['notes_alert'] = alert('success','Zapisano notatkę.');
				redirect('/notes/');
			}
		}
	}
	else {
		$alert = alert('warning','Nie wypełniono nazwy lub treści notatki.');
	}
}
?>

<div id="content">
	<?php
	if(!isset($_SESSION['user_login'])) {
		echo alert('danger','Brak dostępu');
		echo '</div>';
		include($_SERVER['DOCUMENT_ROOT'].PATH.'/section/footer.php');
		exit;
	}
	echo @$alert;
	?>
	<div class="window" id="window-newnote">
	<h2>New note</h2>
	<form id="newnote-form" method="post" action="" onsubmit="return validatenewnoteform()">
	<table class="form-table"><tbody>
		<tr>
			<th scope="row"><label for="note_name">Note name</label></th>
			<td><input type="text" name="note_name" id="note_name" size="25"></td>
		</tr>
		<tr>
			<th scope="row"><label for="note_public">Public note</label></th>
			<td><input type="checkbox" name="note_public" id="note_public" value="note_public" size="25"></td>
		</tr>
		<tr>
			<th scope="row"><label for="note_tags">Tags</label></th>
			<td><input type="text" name="note_tags" id="note_tags" size="25">
			<p><span class="description important"><strong>Important:</strong>
			Please use comma to separate tags.</span></p></td>
		</tr>
		<tr>
			<th scope="row"><label for="note_content">Content</label></th>
			<td><textarea name="note_content" id="note_content" rows="" cols="" style="overflow-y: hidden;" onkeyup="scal()"></textarea></td>
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

<script>
function scal() {
	var text = document.getElementById('note_content');
    var theCSSprop = window.getComputedStyle(text, null).getPropertyValue("padding");
	//alert(theCSSprop);
	text.style.height = 'auto';
	text.style.padding = 0;
	wys = text.scrollHeight+8;
	text.style.height = wys+'px';
	if (wys<200){
		text.style.height = '200px';
	}
	text.style.padding = theCSSprop;

}

var textarea = document.querySelector('textarea');

textarea.addEventListener('keydown', autosize);

function autosize(){
  var el = this;
  setTimeout(function(){
   // el.style.cssText = 'height:auto; padding:0';
    // for box-sizing other than "content-box" use:
    // el.style.cssText = '-moz-box-sizing:content-box';
    //el.style.cssText = 'height:' + el.scrollHeight + 'px';
  },0);
}
</script>
