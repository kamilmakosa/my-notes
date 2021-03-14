<?php
define("ALLOW_INCLUDE", "yes");
include('../bootstrapper.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/head.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/menu.php');
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/header.php');
?>

	<div id="content">
<?php
if(isset($_SESSION['notes_alert'])) {
	echo $_SESSION['notes_alert'];
	unset($_SESSION['notes_alert']);
}
check_access(true); //DOSTĘP TYLKO DLA ZALOGOWANYCH
echo @$alert;

$y = @$splitURL[2];
$m = @$splitURL[3];
if(empty($y)) $y = date("Y");
if(empty($m)) $m = date("m");
$y1= $y;
$m1= $m-1;
$y2= $y;
$m2= $m+1;
if ($m == 1) {
	$y1 = $y-1;
	$m1 = 12;
}
if ($m == 12) {
	$y2 = $y+1;
	$m2 = 1;
}
?>
		<div class="window table-list" id="window-bookmarks">
			<h2><?php echo __('New event'); ?></h2>
			<div id="table-searcher">
				<span class="left" title="<?php echo __('Previous'); ?>"><?php echo icon('navigate_before','/calendar/'.$y1.'/'.$m1); ?></span>
				<span class="calendar-header"><?php echo __(date("F",mktime(0,0,0,$m,1,$y))).' '.date("Y",mktime(0,0,0,$m,1,$y)); ?></span>
				<span class="right" title="<?php echo __('Next'); ?>"><?php echo icon('navigate_next','/calendar/'.$y2.'/'.$m2); ?></span>
			</div>
			<div id="table-calendar"><table>
				<thead>
					<tr>
						<th><?php echo __('Week'); ?></th>
						<th title="<?php echo mb_convert_case(__('poniedziałek'),MB_CASE_TITLE, "UTF-8"); ?>"><?php echo mb_convert_case(__('skrot_poniedziałek'),MB_CASE_TITLE, "UTF-8");?></th>
						<th title="<?php echo mb_convert_case(__('wtorek'),MB_CASE_TITLE, "UTF-8"); ?>"><?php echo mb_convert_case(__('skrot_wtorek'),MB_CASE_TITLE, "UTF-8");?></th>
						<th title="<?php echo mb_convert_case(__('środa'),MB_CASE_TITLE, "UTF-8"); ?>"><?php echo mb_convert_case(__('skrot_środa'),MB_CASE_TITLE, "UTF-8");?></th>
						<th title="<?php echo mb_convert_case(__('czwartek'),MB_CASE_TITLE, "UTF-8"); ?>"><?php echo mb_convert_case(__('skrot_czwartek'),MB_CASE_TITLE, "UTF-8");?></th>
						<th title="<?php echo mb_convert_case(__('piątek'),MB_CASE_TITLE, "UTF-8"); ?>"><?php echo mb_convert_case(__('skrot_piątek'),MB_CASE_TITLE, "UTF-8");?></th>
						<th title="<?php echo mb_convert_case(__('sobota'),MB_CASE_TITLE, "UTF-8"); ?>"><?php echo mb_convert_case(__('skrot_sobota'),MB_CASE_TITLE, "UTF-8");?></th>
						<th title="<?php echo mb_convert_case(__('niedziela'),MB_CASE_TITLE, "UTF-8"); ?>"><?php echo mb_convert_case(__('skrot_niedziela'),MB_CASE_TITLE, "UTF-8");?></th>
					</tr>
				</thead>
				<tbody>
<?php
	$splitURL = explode('/', substr($_SERVER['REQUEST_URI'], strlen(PATH)));
	if (@$splitURL[2] != '' && @$splitURL[3] != '') {
		$year = $splitURL[2];
		$month = $splitURL[3];
	}

	if(empty($year)) $year = date("Y");
	if(empty($month)) $month = date("m");
	$day = date("d");

	$previous_month_start = date("t",mktime(0,0,0,$month-1,$day,$year))-date("N",mktime(0,0,0,$month,1,$year))+2;
	$previous_month_length = date("t",mktime(0,0,0,$month-1,$day,$year));
	$month_length = date("t",mktime(0,0,0,$month,$day,$year));
	$next_month_length = 8-date("N",mktime(0,0,0,$month+1,1,$year));

	if ($month == 1) {
		$previous_month_start = date("t",mktime(0,0,0,12,$day,$year-1))-date("N",mktime(0,0,0,$month,1,$year))+2;
		$previous_month_length = date("t",mktime(0,0,0,12,$day,$year-1));
	}
	if ($month == 12) {
		$next_month_length = 8-date("N",mktime(0,0,0,1,1,$year+1));
	}
	if (date("N",mktime(0,0,0,$month,1,$year))==1) {
		$previous_month_start=$previous_month_start-7;
	}

	$counter=1;
	for ($i=$previous_month_start;$i<=$previous_month_length;$i++) {
		if($counter%7==1) {
			echo "<tr><td>".date("W",mktime(0,0,0,$month-1,$i,$year))."</td>";
		}
		echo "<td>".$i."</td>";
		if($counter%7==0) {
			echo "</tr>";
		}
		$counter++;
	}
	for ($i=1;$i<=$month_length;$i++) {
		if($counter%7==1) {
			echo "<tr><td>".date("W",mktime(0,0,0,$month,$i,$year))."</td>";
		}
		echo "<td>".$i."</td>";
		if($counter%7==0) {
			echo "</tr>";
		}
		$counter++;
	}
	for ($i=1;$i<=$next_month_length;$i++) {
		if($counter%7==1) {
			echo "<tr><td>".date("W",mktime(0,0,0,$month+1,$i,$year))."</td>";
		}
		//echo "<td>".$i." ".$counter."</td>";
		echo "<td>".$i."</td>";
		if($counter%7==0) {
			echo "</tr>";
		}
		if ($counter==35 && $next_month_length<7) {
			$next_month_length=$next_month_length+7;
		}
		$counter++;
	}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="8"><a href="calendar/event/new"><?php echo __('Add new event'); ?></a></td>
					</tr>
				</tfoot>
			</table></div>
		</div>
	</div>
<?php
include($_SERVER['DOCUMENT_ROOT'].PATH.'/template/footer.php');
?>
