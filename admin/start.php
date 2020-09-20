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

$con = @connect_database();
if ($con == false ) {
	echo alert('danger',"Przepraszamy, nie możemy pobrać zawartości strony. Spróbuj później");
} else {
	$query = 'SELECT * FROM ms_users';
	$result = mysqli_query($con,$query);
	$data[0] = mysqli_num_rows($result);

	$query = 'SELECT * FROM ms_news';
	$result = mysqli_query($con,$query);
	$data[1] = mysqli_num_rows($result);

	$query = 'SELECT * FROM ms_bookmarks';
	$result = mysqli_query($con,$query);
	$data[2] = mysqli_num_rows($result);

	$query = 'SELECT bookmark_owner, COUNT( bookmark_owner ) AS ilosc_powtorzen FROM ms_bookmarks GROUP BY bookmark_owner ORDER BY ilosc_powtorzen DESC';
	$result = mysqli_query($con,$query);
	$user_max = '';
	$user_max_count = 0;
	while($row = mysqli_fetch_array($result)){
		if ($row['ilosc_powtorzen'] == $user_max_count) {
			if($user_max == '') {
				$user_max = $row['bookmark_owner'];
			} else {
				$user_max .= ', '.$row['bookmark_owner'];
			}
			$user_max_count = $row['ilosc_powtorzen'];
		}
		if ($row['ilosc_powtorzen'] > $user_max_count) {
			$user_max = $row['bookmark_owner'];
			$user_max_count = $row['ilosc_powtorzen'];
		}
	}
	$data[3] = $user_max;

	$query = 'SELECT * FROM ms_notes';
	$result = mysqli_query($con,$query);
	$data[4] = mysqli_num_rows($result);

	$query = 'SELECT notes_owner, COUNT( notes_owner ) AS ilosc_powtorzen FROM ms_notes GROUP BY notes_owner ORDER BY ilosc_powtorzen DESC';
	$result = mysqli_query($con,$query);
	$user_max = '';
	$user_max_count = 0;
	while($row = mysqli_fetch_array($result)){
		if ($row['ilosc_powtorzen'] == $user_max_count) {
			if($user_max == '') {
				$user_max = $row['notes_owner'];
			} else {
				$user_max .= ', '.$row['notes_owner'];
			}
			$user_max_count = $row['ilosc_powtorzen'];
		}
		if (intval($row['ilosc_powtorzen']) > $user_max_count) {
			$user_max = $row['notes_owner'];
			$user_max_count = $row['ilosc_powtorzen'];
		}
	}
	$data[5] = $user_max;

	$query = "SELECT * FROM ms_users WHERE user_status='non-accepted user'";
	$result = mysqli_query($con,$query);
	$data[6] = mysqli_num_rows($result);
	if (mysqli_num_rows($result) != 0) {
		$data[6] .= icon('error').'<a href="ap/users">AKTYWUJ</a>';
	}
?>

	<div id="content">
		<div class="window-ap" id="window-ap-start">
			<!-- LEWA KOLUMNA -->
			<div class="column">
				<h2>Statystyki</h2>
				<table class="form-table">
					<tr>
						<th scope="row">Users</th>
						<td><?php echo $data[0]; ?></td>
					</tr>
					<tr>
						<th scope="row">News</th>
						<td><?php echo $data[1]; ?></td>
					</tr>
					<tr>
						<th scope="row">Bookmarks</th>
						<td><?php echo $data[2]; ?></td>
					</tr>
					<tr>
						<th scope="row">Most bookmarks</th>
						<td><?php echo $data[3]; ?></td>
					</tr>
					<tr>
						<th scope="row">Notes</th>
						<td><?php echo $data[4]; ?></td>
					</tr>
					<tr>
						<th scope="row">Most notes</th>
						<td><?php echo $data[5]; ?></td>
					</tr>
				</table>
			</div>

			<!-- PRAWA KOLUMNA -->
			<div class="column">
				<h2>Zadania</h2>
				<table class="form-table admin-task">
					<tr>
						<th scope="row">Users for accept</th>
						<td><?php echo $data[6]; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
<?php } ?>
</div>
</body>
</html>
