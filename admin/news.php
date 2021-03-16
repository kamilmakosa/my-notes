<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu_ap.php');

if (!isset($_SESSION['user_login']) || get_user_info('user_name') != 'Administrator') {
	redirect('/signin');
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
	if ($operation == 'delete') {
		$query = "DELETE FROM `ms_news` WHERE news_ID='$position'";
		$result = mysqli_query($con,$query);
		$_SESSION['notes_alert'] = alert('success','News usunięty.');
		redirect('/ap/news/');
		exit;
	}
}

if (isset($_POST['news_title']) && isset($_POST['news_category']) && isset($_POST['news_status']) && isset($_POST['news_name']) && isset($_POST['news_content']) && isset($_POST['news_excerpt'])) {
	if(!empty($_POST['news_title']) && !empty($_POST['news_category']) && !empty($_POST['news_status']) && !empty($_POST['news_name']) && !empty($_POST['news_content']) && !empty($_POST['news_excerpt'])) {
		$news_title = $_POST["news_title"];
		$news_category = $_POST["news_category"];
		$news_status = $_POST["news_status"];
		$news_name = $_POST["news_name"];
		$news_content = $_POST["news_content"];
		$news_excerpt = $_POST["news_excerpt"];
		$user_login = @$_SESSION['user_login'];
		$news_date = get_datetime();
		$news_date_gmt = get_datetime_GMT();
		$news_day = get_day();

		$con = @connect_database();
		if ($con == false ) {
			$alert = alert('warning','Nie możemy zapisać notatki.');
		} else {
			if (isset($operation) && $operation=='edit') {
				$query = "UPDATE ms_news SET news_title='$news_title',news_content='$news_content',news_excerpt='$news_excerpt',news_status='$news_status',news_name='$news_name',news_modified='$news_date',news_modified_gmt='$news_date_gmt',news_category='$news_category' WHERE news_ID='$position'";
				if (!mysqli_query($con,$query)) {
					$alert = alert('warning','Nie możemy zapisać zmian.');
				} else {
					$_SESSION['notes_alert'] = alert('success','Zapisano news.');
					redirect('/ap/news/');
					exit;
				}
			} else {
				$query = "INSERT INTO ms_news(`news_author`, `news_title`, `news_date`, `news_date_gmt`, `news_day`, `news_content`, `news_excerpt`, `news_status`, `news_name`, `news_category`) VALUES ('$user_login', '$news_title', '$news_date', '$news_date_gmt', '$news_day', '$news_content', '$news_excerpt', '$news_status', '$news_name', '$news_category')";
				if (!mysqli_query($con,$query)) {
					$alert = alert('warning','Nie możemy zapisać newsa.');
				} else {
					$_SESSION['notes_alert'] = alert('success','News zapisany.');
					redirect('/ap/news/');
					exit;
				}
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
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/news-edit.php');
}

if (!isset($_SESSION['user_login']) || get_user_info('user_name') != 'Administrator') {
	echo alert('danger','Brak dostępu');
	echo '</div>';
	include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
	exit;
}
?>
		<div class="window-ap table-list" id="window-ap-news-list">
			<h2><span><?php echo icon('arrow_drop_down');?></span>Lista newsów</h2>
			<div id="table-news-list" class="folddiv"><table>
				<thead>
				<tr>
					<th>ID</th>
					<th>Autor</th>
					<th>Tytuł</th>
					<th>Data</th>
					<th>Data GMT</th>
					<th>Dzień</th>
					<th>Status</th>
					<th>Kategoria</th>
					<th>Nazwa</th>
					<th>Data modyfikacji</th>
					<th>Data modyfikacji GMT</th>
					<th>Operacje</th>
				</tr>
				</thead>
				<tbody>
<?php
$con = connect_database();
if ($con === false) {
	echo alert('info',__('Przepraszamy, nie możemy pobrać zawartości strony.'));
} else {
	$query = "SELECT * FROM `ms_news` ORDER BY news_ID ASC;";
	$result = mysqli_query($con,$query);
	if (!$result){
		echo mysqli_error($con);
	}
	$rowcount = mysqli_num_rows($result);
	$counter = 0;

	$lista['author'] = array();
	$lista['day'] = array();
	$lista['status'] = array();
	$lista['category'] = array();

	while ($array = mysqli_fetch_assoc($result)) {
		$counter++;
?>
					<tr id="tr<?php echo $counter; ?>">
						<td class="" id="id<?php echo $counter; ?>"><?php echo $array['news_ID']; ?></td>
						<td class="" id="author<?php echo $counter; ?>"><?php echo $array['news_author']; ?></td>
						<td class="" id="title<?php echo $counter; ?>"><?php echo $array['news_title']; ?></td>
						<td class="" id="date<?php echo $counter; ?>"><?php echo $array['news_date']; ?></td>
						<td class="" id="date-gmt<?php echo $counter; ?>"><?php echo $array['news_date_gmt']; ?></td>
						<td class="" id="day<?php echo $counter; ?>"><?php echo $array['news_day']; ?></td>
						<td class="" id="status<?php echo $counter; ?>"><?php echo $array['news_status']; ?></td>
						<td class="" id="category<?php echo $counter; ?>"><?php echo $array['news_category']; ?></td>
						<td class="" id="name<?php echo $counter; ?>"><?php echo $array['news_name']; ?></td>
						<td class="" id="modified<?php echo $counter; ?>"><?php echo $array['news_modified']; ?></td>
						<td class="" id="modified-gmt<?php echo $counter; ?>"><?php echo $array['news_modified_gmt']; ?></td>
						<td class="icons-operation" id="operation<?php echo $counter; ?>"><?php echo icon('description','/news/id/'.$array['news_ID']).icon('edit','/ap/news/edit/'.$array['news_ID']).icon('delete','/ap/news/delete/'.$array['news_ID']); ?></td>
					</tr>
<?php
		if (!in_array($array['news_author'], $lista['author'])) {
			$lista['author'][] = $array['news_author'];
		}
		if (!in_array($array['news_day'], $lista['day'])) {
			$lista['day'][] = $array['news_day'];
		}
		if (!in_array($array['news_status'], $lista['status'])) {
			$lista['status'][] = $array['news_status'];
			}
		if (!in_array($array['news_category'], $lista['category'])) {
			$lista['category'][] = $array['news_category'];
		}
	}
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="12">Liczba wierszy: <span id="suma"><?php echo $counter; ?><span></td>
					</tr>
				</tfoot>
			</table></div>
		</div>

		<div class="window-ap" id="window-ap-news-new">
			<h2>Dodaj newsa</h2>
			<form id="news-new" method="post" action="" novalidate="novalidate">
				<!--<div class="column block "><!-- LEWA KOLUMNA -->
					<table class="form-table"><tbody>
						<tr>
							<th scope="row"><label for="news_title">Tytuł</label></th>
							<td><input type="text" name="news_title" id="news_title" size="25"></td>
						</tr>
						<tr>
							<th scope="row"><label for="news_category">Kategoria</label></th>
							<td><input type="text" name="news_category" id="news_category" size="25"></td>
						</tr>
						<tr>
							<th scope="row"><label for="news_status">Status</label></th>
							<td><select name="news_status" id="news_status">
								 <option value="" disabled>Choose your option</option>
								<option value="public" selected>public</option>
								<option value="publish_only_user">publish_only-user</option>
								<option value="not-publish">not-publish</option>
							</select></td>
						</tr>
						<tr>
							<th scope="row"><label for="news_name">Nazwa adresu</label></th>
							<td><input type="text" name="news_name" id="news_name" size="25"></td>
						</tr>
					</tbody></table>
				<!--</div>
				<div class="column"><!-- PRAWA KOLUMNA -->
					<table class="form-table"><tbody>
						<tr>
							<th scope="row"><label for="news_content">Treść</label></th>
							<td><textarea type="text" name="news_content" id="news_content" size="25"></textarea></td>
						</tr>
						<tr>
							<th scope="row"><label for="news_excerpt">Opis skrócony</label></th>
							<td><textarea type="text" name="news_excerpt" id="news_excerpt" size="25"></textarea></td>
						</tr>
					</tbody></table>
				<!--</div> -->
				<p class="sendformblock">
					<input type="submit" name="Submit" id="submit" value="Add news">
				</p>
			</form>
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
		<span>Date from:</span>
		<input type="date" id="date_from" onChange="filter();">
		<span>Date to:</span>
		<input type="date" id="date_to" onChange="filter();">
		<span>Modified date from:</span>
		<input type="date" id="date_modified_from" onChange="filter();">
		<span>Modified date to:</span>
		<input type="date" id="date_modified_to" onChange="filter();">
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
	value1 = document.getElementById("filter_author").value;
	value2 = document.getElementById("filter_day").value;
	value3 = document.getElementById("filter_status").value;
	value4 = document.getElementById("filter_category").value;
	value5 = Date.parse(document.getElementById("date_from").value);
	value6 = Date.parse(document.getElementById("date_to").value);
	value7 = Date.parse(document.getElementById("date_modified_from").value);
	value8 = Date.parse(document.getElementById("date_modified_to").value);

	licznik=0;
	tagsrow = document.querySelectorAll("#table-news-list tbody tr");
	tagsrow = tagsrow.length;

	for (i=1;i<tagsrow+1;i++) {
		wiersz = "tr"+i;

		pole1 = document.getElementById("author"+i).innerHTML;
		pole2 = document.getElementById("day"+i).innerHTML;
		pole3 = document.getElementById("status"+i).innerHTML;
		pole4 = document.getElementById("category"+i).innerHTML;
		pole5 = document.getElementById("title"+i).innerHTML;

		pole6 = document.getElementById("date"+i).innerHTML;
		pole7 = document.getElementById("modified"+i).innerHTML;
		reg = /[0-9]{4}-[0-9]{2}-[0-9]{2}/g;
		pole6 = Date.parse(pole6.match(reg));
		pole7 = Date.parse(pole7.match(reg));

		var wzor = new RegExp("^.*("+textsearch+").*$","i");

		if((pole1 == value1 || value1 == "all") &&
		(pole2 == value2 || value2 == "all") &&
		(pole3 == value3 || value3 == "all") &&
		(pole4 == value4 || value4 == "all") &&
		(wzor.test(pole5)) &&
		(pole6>=value3 || isNaN(value5)) &&
		(pole6<=value4 || isNaN(value6)) &&
		(pole7>=value7 || isNaN(value7)) &&
		(pole7<=value8 || isNaN(value8))) {
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
