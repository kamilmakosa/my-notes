<?php
if (!defined("ALLOW_INCLUDE"))	die('Access forbidden');
$title = 'Nowa zakładka';
$status = 'new';
$name = '';
$tags = '';
$link = '';

if ($operation == 'edit' && $con != false) {
	$user_login = $_SESSION['user_login'];
	$bookmark_position = @$splitURL[3];
	$query = "SELECT * FROM `ms_bookmarks` WHERE bookmark_position='$bookmark_position' AND bookmark_owner='$user_login';";
	$result = mysqli_query($con,$query);
	$array = mysqli_fetch_assoc($result);
	$title = 'Edytuj zakładkę';
	$status = 'edit';
	$name = $array['bookmark_name'];
	$tags = $array['bookmark_tags'];
	$link = $array['bookmark_link'];
}

?>
		<div class="window" id="window-bookmarks">
			<h2><?php echo $title; ?></h2>
			<form id="newnote-form" method="post" action="" onsubmit="return validatenewnoteform()">
			<input type="hidden" name="bookmarktype" value="<?php echo $status; ?>" />
			<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="bookmark_name">Name</label></th>
					<td><input type="text" name="bookmark_name" id="bookmark_name" size="25" value="<?php echo $name; ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="bookmark_tags">Tags</label></th>
					<td><input type="text" name="bookmark_tags" id="bookmark_tags" size="25" value="<?php echo $tags; ?>">
					<p><span class="description important"><strong>Important:</strong>
					Please use comma to separate tags.</span></p></td>
				</tr>
				<tr>
					<th scope="row"><label for="bookmark_link">Web address</label></th>
					<td><input type="text" name="bookmark_link" id="bookmark_link" size="25" value="<?php echo $link; ?>"></td>
				</tr>
			</tbody>
			</table>
			<p class="sendformblock">
				<input type="submit" name="submit" id="submit" value="Save bookmark">
			</p>
			</form>	
		</div>