<?php
if (!defined("ALLOW_INCLUDE"))	die('Access forbidden');
$con = connect_database();
$query = "SELECT * FROM `ms_news` WHERE news_ID='$position';";
$result = mysqli_query($con,$query);
$array = mysqli_fetch_assoc($result);
?>

		<div class="window-ap" id="window-ap-news-new">
			<h2>Edytuj newsa</h2>
			<form id="news-new" method="post" action="" novalidate="novalidate">
				<!--<div class="column block "><!-- LEWA KOLUMNA -->
					<table class="form-table"><tbody>
						<tr>
							<th scope="row"><label for="news_title">Tytuł</label></th>
							<td><input type="text" name="news_title" id="news_title" size="25" value="<?php echo $array['news_title']; ?>">
							<input type="hidden" name="news_ID" id="news_ID" size="25" value="<?php echo $array['news_ID']; ?>"></td>
						</tr>
						<tr>
							<th scope="row"><label for="news_category">Kategoria</label></th>
							<td><input type="text" name="news_category" id="news_category" size="25" value="<?php echo $array['news_category']; ?>"></td>
						</tr>
						<tr>
							<th scope="row"><label for="news_status">Status</label></th>
							<td><select name="news_status" id="news_status">
								 <option value="" disabled>Choose your option</option>
								<option <?php if($array['news_status']=='public') { echo 'selected'; }?> value="public">publish</option>
								<option <?php if($array['news_status']=='publish_only_user') { echo 'selected'; }?> value="publish_only_user">publish_only_user</option>
								<option <?php if($array['news_status']=='not-publish') { echo 'selected'; }?> value="not-publish">not-publish</option>
							</select></td>
						</tr>
						<tr>
							<th scope="row"><label for="news_name">Nazwa adresu</label></th>
							<td><input type="text" name="news_name" id="news_name" size="25" value="<?php echo $array['news_name']; ?>"></td>
						</tr>
					</tbody></table>
				<!--</div>
				<div class="column"><!-- PRAWA KOLUMNA -->
					<table class="form-table"><tbody>
						<tr>
							<th scope="row"><label for="news_content">Treść</label></th>
							<td><textarea type="text" name="news_content" id="news_content" size="25"><?php echo $array['news_content']; ?></textarea></td>
						</tr>
						<tr>
							<th scope="row"><label for="news_excerpt">Opis skrócony</label></th>
							<td><textarea type="text" name="news_excerpt" id="news_excerpt" size="25"><?php echo $array['news_excerpt']; ?></textarea></td>
						</tr>
					</tbody></table>
				<!--</div> -->
				<p class="sendformblock">
					<input type="submit" name="Submit" id="submit" value="Save change">
				</p>
			</form>
		</div>