<?php
if (!defined("ALLOW_INCLUDE"))	die('Access forbidden');
$con = connect_database();
$query = "SELECT * FROM ms_users WHERE user_ID='$position'";
$result = mysqli_query($con,$query);
$array = mysqli_fetch_assoc($result);
?>

	<div class="window-ap" id="window-user-edit">
		<h2>Edytuj dane użytkownika</h2>
		<form id="edituser-form" method="post" action="" onsubmit="return validatenewnoteform()">
		<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">ID</th>
				<td><?php echo $array['user_ID']; ?>
				<input type="hidden" name="user_ID" id="user_ID" size="25" value="<?php echo $array['user_ID']; ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_login">Login</label></th>
				<td><input type="text" name="user_login" id="user_login" size="25" value="<?php echo $array['user_login']; ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_name">Name</label></th>
				<td><input type="text" name="user_name" id="user_name" size="25" value="<?php echo $array['user_name']; ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_email">Email</label></th>
				<td><input type="text" name="user_email" id="user_email" size="25" value="<?php echo $array['user_email']; ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_activation_key">Activation Key</label></th>
				<td><input type="text" name="user_activation_key" id="user_activation_key" size="25" value="<?php echo $array['user_activation_key']; ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_status">Status</label></th>
				<td><input type="text" name="user_status" id="user_status" size="25" value="<?php echo $array['user_status']; ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="user_service">Service</label></th>
				<td><input type="text" name="user_service" id="user_service" size="25" value="<?php echo $array['user_service']; ?>"></td>
			</tr>
			<tr>
				<th scope="row">Timezone</th>
				<td><?php echo $array['user_timezone']; ?></td>
			</tr>
			<tr>
				<th scope="row">Language</th>
				<td><?php echo get_lang_info('lang_name',$array['user_language']).'/'.get_lang_info('lang_name_en',$array['user_language']); ?></td>
			</tr>
		</tbody>
		</table>
		<p class="sendformblock">
			<input type="submit" name="submit" id="submit" value="Save user data">
		</p>
		</form>	
	</div>