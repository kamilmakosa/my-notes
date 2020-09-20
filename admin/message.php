<?php
define("ALLOW_INCLUDE", "yes");
define("PATH", "/demo/my-notes");
session_start();
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/functions.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/functions/autostart.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu_ap.php');

if (!isset($_SESSION['user_login']) || get_user_info('user_name') != 'Administrator') {
	header('Location: /signin');
	exit;
}

if (isset($_SESSION['user_login']) && get_user_info('user_name') == 'Administrator') {
	$con = connect_database();
	if ($con == false ) {
		$alert = alert('info','Przepraszamy, nie możemy pobrać zawartości strony. Spróbuj później.');
	} else {
		$user_login = $_SESSION['user_login'];
		$splitURL = explode('/', substr($_SERVER['REQUEST_URI'], strlen(PATH)));
		if (@$splitURL[3] != '' && @$splitURL[3] != '') {
			$operation = $splitURL[3];
			$position = $splitURL[4];
		}
	}
}
?>

	<div class="content-ap">
<?php
if(isset($_SESSION['notes_alert'])) {
	echo $_SESSION['notes_alert'];
	unset($_SESSION['notes_alert']);
}
echo @$alert;

if (isset($operation) && $operation == 'edit') {
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/users-edit.php');
}

if (!isset($_SESSION['user_login']) || get_user_info('user_name') != 'Administrator') {
	echo alert('danger','Brak dostępu');
	echo '</div>';
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
	exit;
}

/* --- SEKCJA NIEODCZYTANE WIADOMOSCI --- */
$query = "SELECT * FROM ms_messages WHERE message_isread='false' ORDER BY message_ID ASC;";
$result = mysqli_query($con,$query);
if (!$result){
	echo alert('danger',mysqli_error($con));
} else {
	$rowcount = mysqli_num_rows($result);
	if ($rowcount != 0) {
?>

		<div class="window-ap table-list" id="window-ap-news">
			<h2><span><?php echo icon('arrow_drop_down');?></span>Lista użytkowników do akceptacji</h2>
			<div id="table-user-not-accepted-list" class="folddiv"><table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Subject</th>
					<th>Text</th>
					<th>Date</th>
					<th>Source</th>
					<th>Sender</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
<?php
$con = connect_database();
if ($con == false ) {
	echo alert('danger','Przepraszamy, nie możemy pobrać zawartości strony. Spróbuj później.');
}
else {
	$query = "SELECT * FROM ms_messages WHERE message_isread='false' ORDER BY message_ID ASC;";
	$result = mysqli_query($con,$query);
	if (!$result){
		echo alert('danger',mysqli_error($con));
	}
	else {
		$counter = 0;
		while ($array = mysqli_fetch_assoc($result)) {
			$counter++;
			echo '<tr>';
			echo '<td>'.$array['message_ID'].'</td>';
			echo '<td>'.$array['message_subject'].'</td>'."\n";
			$message_text = strlen($array['message_text']) > 50 ? substr($array['message_text'],0,50)."..." : $array['message_text'];
			echo '<td>'.$message_text.'</td>'."\n";
			echo '<td>'.$array['message_date'].'</td>'."\n";
			echo '<td>'.$array['message_source'].'</td>'."\n";
			echo '<td>'.$array['message_sender'].'</td>'."\n";
			echo '<td>'.icon('done','/ap/users/activate/'.$array['message_ID']).'</td>'."\n";
			echo '</tr>'."\n";
		}
	}
}
?>
			</tbody>
			<tfoot>
<?php
echo '<tr>'."\n";
echo '<td colspan="7">Liczba wierszy: '.$counter.'</td>';
echo '</tr>'."\n";
?>
			</tfoot>
			</table></div>
		</div>
<?php
	}
}
/* --- KONIEC SEKCJI UZYTKOWNICY DO AKCEPTACJI --- */
?>

		<div class="window-ap table-list" id="window-ap-news">
			<h2><span><?php echo icon('arrow_drop_down');?></span>Lista użytkowników</h2>
			<div id="table-user-list" class="folddiv"><table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Subject</th>
					<th>Text</th>
					<th>Date</th>
					<th>Source</th>
					<th>Sender</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
<?php
$con = connect_database();
if ($con == false ) {
	echo alert('danger','Przepraszamy, nie możemy pobrać zawartości strony. Spróbuj później.');
}
else {
	$query = "SELECT * FROM ms_messages WHERE message_isread!='false' ORDER BY message_ID ASC;";
	$result = mysqli_query($con,$query);
	if (!$result){
		echo alert('danger',mysqli_error($con));
	}
	else {
		$counter = 0;
		while ($array = mysqli_fetch_assoc($result)) {
			$counter++;
			echo '<tr id="tr'.$counter.'">';
			echo '<td class="" id="id'.$counter.'">'.$array['message_ID'].'</td>';
			echo '<td class="" id="subject'.$counter.'">'.$array['message_subject'].'</td>'."\n";
			echo '<td class="" id="text'.$counter.'">'.$array['message_text'].'</td>'."\n";
			echo '<td class="" id="date'.$counter.'">'.$array['message_date'].'</td>'."\n";
			echo '<td class="" id="source'.$counter.'">'.$array['message_source'].'</td>'."\n";
			echo '<td class="" id="sender'.$counter.'">'.$array['message_sender'].'</td>'."\n";
			echo '<td class="icons-operation" id="operation'.$counter.'">';
			echo '</td>'."\n";
			echo '</tr>'."\n";
		}
	}
}
?>
			</tbody>
			<tfoot>
<?php
echo '<tr>'."\n";
echo '<td colspan="7">Liczba wierszy: <span id="suma">'.$counter.'<span></td>';
echo '</tr>'."\n";
?>
			</tfoot>
			</table></div>
		</div>	
	</div>
</div>

</body>
</html>