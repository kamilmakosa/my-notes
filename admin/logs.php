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

if (isset($operation)) {
	if ($operation == 'activate') {
		$query = "UPDATE `ms_users` SET user_status='actived user' WHERE user_ID='$position'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','Aktywowano użytkownika.');
		header('Location: /ap/users/');
		exit;
	}
	if ($operation == 'delete') {
		$query = "DELETE FROM `ms_users` WHERE user_ID='$position'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','Usunięto użytkownika.');
		header('Location: /ap/users/');
		exit;
	}
	if ($operation == 'lock') {
		$query = "UPDATE `ms_users` SET user_status='blocked user' WHERE user_ID='$position'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','Zablokowano użytkownika.');
		header('Location: /ap/users/');
		exit;
	}
	if ($operation == 'unlock') {
		$query = "UPDATE `ms_users` SET user_status='actived user' WHERE user_ID='$position'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert']= alert('success','Odblokowano użytkownika.');
		header('Location: /ap/users/');
		exit;
	}
}

if (isset($_POST['user_login']) && isset($_POST['user_name']) && isset($_POST['user_email']) && isset($_POST['user_activation_key']) && isset($_POST['user_status']) && isset($_POST['user_service']) && isset($_SESSION['user_login']) && isset($_POST['submit'])) {
	if(!empty($_POST['user_login']) && !empty($_POST['user_name']) && !empty($_POST['user_email']) && !empty($_POST['user_activation_key']) && !empty($_POST['user_status']) && !empty($_POST['user_service'])) {
		$user_ID = $_POST['user_ID'];
		$user_login = $_POST['user_login'];
		$user_name = $_POST['user_name'];
		$user_email = $_POST['user_email'];
		$user_activation_key = $_POST['user_activation_key'];
		$user_status = $_POST['user_status'];
		$user_service = $_POST['user_service'];
		echo $user_ID;

		$con = connect_database();
		if ($con == false ) {
			$alert = alert('warning','Nie możemy zapisać zakładki.');
		}
		else {
			$query = "UPDATE ms_users SET user_login='$user_login', user_name='$user_name', user_email='$user_email', user_activation_key='$user_activation_key', user_status='$user_status', user_service='$user_service' WHERE user_ID='$user_ID'";
			if (!mysqli_query($con,$query)) {
				$alert = alert('warning','Nie możemy zapisać zmian w edycji użytkownika.');
			} else {
				$_SESSION['notes_alert'] = alert('success','Zapisano zmiany.');
				header('Location: /ap/users/');
				exit;
			}
		}
	}
	else {
		$alert = alert('warning','Nie wypełniono wszystkich pól.');
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

/* --- SEKCJA UZYTKOWNICY DO AKCEPTACJI --- */
$query = "SELECT * FROM ms_users WHERE user_status='non-accepted user' ORDER BY user_ID ASC;";
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
					<th>Login</th>
					<th>Name</th>
					<th>Email</th>
					<th>Last login</th>
					<th>Date registration</th>
					<th>Status</th>
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
	$query = "SELECT * FROM ms_users WHERE user_status='non-accepted user' ORDER BY user_ID ASC;";
	$result = mysqli_query($con,$query);
	if (!$result){
		echo alert('danger',mysqli_error($con));
	}
	else {
		$counter = 0;
		while ($array = mysqli_fetch_assoc($result)) {
			$counter++;
			echo '<tr>';
			echo '<td>'.$array['user_ID'].'</td>';
			echo '<td>'.$array['user_login'].'</td>'."\n";
			echo '<td>'.$array['user_name'].'</td>'."\n";
			echo '<td>'.$array['user_email'].'</td>'."\n";
			echo '<td>'.$array['user_last_login'].'</td>'."\n";
			echo '<td>'.$array['user_registered'].'</td>'."\n";
			echo '<td>'.$array['user_status'].'</td>'."\n";
			echo '<td>'.icon('done','/ap/users/activate/'.$array['user_ID']).'</td>'."\n";
			echo '</tr>'."\n";
		}
	}
}
?>
			</tbody>
			<tfoot>
<?php
echo '<tr>'."\n";
echo '<td colspan="8">Liczba wierszy: '.$counter.'</td>';
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
					<th>Login</th>
					<th>Name</th>
					<th>Email</th>
					<th>Last login</th>
					<th>Date registration</th>
					<th>Status</th>
					<th>Service</th>
					<th>B</th>
					<th>N</th>
					<th>Operacje</th>
				</tr>
			</thead>
			<tbody>
<?php
$con = connect_database();
if ($con == false ) {
	echo alert('danger','Przepraszamy, nie możemy pobrać zawartości strony. Spróbuj później.');
}
else {
	$query = "SELECT * FROM `ms_users` WHERE user_status!='non-accepted user' ORDER BY user_ID ASC;";
	$result = mysqli_query($con,$query);
	if (!$result){
		echo alert('danger',mysqli_error($con));
	}
	else {
		$lista['status'] = array();
		$lista['service'] = array();
		
		$counter = 0;
		while ($array = mysqli_fetch_assoc($result)) {
			$counter++;
			echo '<tr id="tr'.$counter.'">';
			echo '<td class="" id="id'.$counter.'">'.$array['user_ID'].'</td>';
			echo '<td class="" id="login'.$counter.'">'.$array['user_login'].'</td>'."\n";
			echo '<td class="" id="name'.$counter.'">'.$array['user_name'].'</td>'."\n";
			echo '<td class="" id="email'.$counter.'">'.$array['user_email'].'</td>'."\n";
			echo '<td class="" id="last-login'.$counter.'">'.$array['user_last_login'].'</td>'."\n";
			echo '<td class="" id="registered'.$counter.'">'.$array['user_registered'].'</td>'."\n";
			echo '<td class="" id="status'.$counter.'">'.$array['user_status'].'</td>'."\n";
			echo '<td class="" id="service'.$counter.'">'.$array['user_service'].'</td>'."\n";
			echo '<td class="" id="bookmarks'.$counter.'">'.get_user_stats('bookmarks',false,$array['user_login']).'</td>'."\n";
			echo '<td class="" id="notes'.$counter.'">'.get_user_stats('notes',false,$array['user_login']).'</td>'."\n";
			echo '<td class="icons-operation" id="operation'.$counter.'">';

			if ($array['user_status'] == 'blocked user') {
				echo icon('lock_open','/ap/users/unlock/'.$array['user_ID']);
			}
			if ($array['user_status'] == 'actived user' && $array['user_name'] != 'Administrator') {
				echo icon('lock','/ap/users/lock/'.$array['user_ID']);
			}
			if ($array['user_name'] != 'Administrator') {
				echo icon('edit','/ap/users/edit/'.$array['user_ID']).icon('delete','/ap/users/delete/'.$array['user_ID']).'</td>'."\n";
			}
			echo '</tr>'."\n";
			
			if (!in_array($array['user_status'], $lista['status'])) {
				$lista['status'][] = $array['user_status'];
			}
			if (!in_array($array['user_service'], $lista['service'])) {
				$lista['service'][] = $array['user_service'];
			}
		}
	}
}
?>
			</tbody>
			<tfoot>
<?php
echo '<tr>'."\n";
echo '<td colspan="12">Liczba wierszy: <span id="suma">'.$counter.'<span></td>';
echo '</tr>'."\n";
?>
			</tfoot>
			</table></div>
		</div>	
	</div>
</div>

<div class="sidepanel">
	<div id="title" onclick="tooglePanel(this)">Filtruj</div>
	<div id="content">
		<input type="text" class="search" id="filter_searcher" name="search" onkeyup="filter()" placeholder="Search.."><br>
<?php
foreach ($lista as $key => $value) {
	sort($lista[$key]);
	echo '<span>'.ucfirst($key).':</span>';
	echo '<select id="filter_'.$key.'" onChange="filter();">';
	echo '<option disabled>'.ucfirst($key).'</option>';
	echo '<option value="all" checked>all</option>';
	for ($i=0;$i<count($lista[$key]);$i++) {
		echo '<option value="'.$lista[$key][$i].'">'.$lista[$key][$i].'</option>';
	}
	echo '</select>';
}
?>
		<span>Last login date from:</span>
		<input type="date" id="date_lastlogin_from" onChange="filter();">
		<span>Last login date to:</span>
		<input type="date" id="date_lastlogin_to" onChange="filter();">
		<span>Registration date from:</span>
		<input type="date" id="date_registration_from" onChange="filter();">
		<span>Registration date to:</span>
		<input type="date" id="date_registration_to" onChange="filter();">
	</div>
</div>

<script>
function tooglePanel(object) {
	var x = object.parentElement;
	if (x.className.indexOf("panel-on") == -1) {
        x.className += " panel-on";
    } else { 
        x.className = x.className.replace(" panel-on", "");
    }
}

function filter() {
	textsearch = document.getElementById("filter_searcher").value;
	value1 = document.getElementById("filter_status").value;
	value2 = document.getElementById("filter_service").value;
	value3 = Date.parse(document.getElementById("date_lastlogin_from").value);
	value4 = Date.parse(document.getElementById("date_lastlogin_to").value);
	value5 = Date.parse(document.getElementById("date_registration_from").value);
	value6 = Date.parse(document.getElementById("date_registration_to").value);
	
	licznik=0;
	tagsrow = document.querySelectorAll("#table-user-list tbody tr");
	tagsrow = tagsrow.length;
	for (i=1;i<tagsrow+1;i++) {
		wiersz = "tr"+i;
		
		pole1 = document.getElementById("status"+i).innerHTML;
		pole2 = document.getElementById("service"+i).innerHTML;
		
		pole3 = document.getElementById("last-login"+i).innerHTML;
		pole4 = document.getElementById("registered"+i).innerHTML;
		reg = /[0-9]{4}-[0-9]{2}-[0-9]{2}/g;
		pole3 = Date.parse(pole3.match(reg));
		pole4 = Date.parse(pole4.match(reg));
		
		pole5 = document.getElementById("login"+i).innerHTML;
		pole6 = document.getElementById("name"+i).innerHTML;
		pole7 = document.getElementById("email"+i).innerHTML;
		var wzor = new RegExp("^.*("+textsearch+").*$","i");
		
		
		if((pole1 == value1 || value1 == "all") &&
		(pole2 == value2 || value2 == "all") &&
		(pole3>=value3 || isNaN(value3)) &&
		(pole3<=value4 || isNaN(value4)) &&
		(pole4>=value5 || isNaN(value5)) &&
		(pole4<=value6 || isNaN(value6)) &&
		(wzor.test(pole5) || wzor.test(pole6) || wzor.test(pole7))) {
			document.getElementById(wiersz).style.display = "table-row";
			licznik++;
		}
		else {
			document.getElementById(wiersz).style.display = "none";
		}
	}
	document.getElementById("suma").innerHTML = licznik;
}
</script>

</body>
</html>