<?php # Script - Set Availability

$page_title = "Set Availability";
include('includes/header.html');
require('sethours_func.php');
require('other_func.php');
require('../mysqli_connect.php');
echo '<h1>Set Availability</h1>';

$dw = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
if (isset($_COOKIE['user_type']) && $_COOKIE['user_type'] == 'emp'){
		$user = $_COOKIE['user_id'];
		$emp = employee_id($dbc, $user);
		}


if($_SERVER['REQUEST_METHOD']=='POST'){
	for ($i=0;$i<7;$i++){
		$day = $dw[$i];
		$hs = $day . 'hs';
		$he = $day . 'he';
		$ms = $day . 'ms';
		$me = $day . 'me';
		$ts = $day . 'ts';
		$te = $day . 'te';
		

		$hs = ($_POST[$hs]);
		$he = ($_POST[$he]);
		$ms = ($_POST[$ms]);
		$me = ($_POST[$me]);
		$ts = ($_POST[$ts]);
		$te = ($_POST[$te]);

		if($hs!=='1' && $he!=='1'){
			clear_emp($dbc, $day, $emp);
			if ('clear_emp'){
				adjust_emp($dbc, $emp, $day, $hs, $he, $ms, $me, $ts, $te);
			}
		}

		
		$start = emp_start($dbc, $emp, $day);
		$end = emp_end($dbc, $emp, $day);
	}
}

if (isset($_COOKIE['user_type']) && $_COOKIE['user_type'] == 'emp'){
		$user = $_COOKIE['user_id'];
		$emp = employee_id($dbc, $user);
		echo '<h2>Business Hours</h2>
	<table width="60%">
	<thead>
	<tr>
	<th align="left"><strong>Day</strong></th>
	<th align="left"><strong>Time Start</strong></th>
	<th align="left"><strong>Time End</strong></th>
	</tr>
	</thead>
	<tbody>';
	for ($i=0;$i<7;$i++){
		$day = $dw[$i];
		$start = start_time($dbc, $day);
		//$start = date('h:i:s', $start);
		$end = end_time($dbc, $day);
		//echo  $endTime);

		echo '<tr>
		<td align="left">' . $day . '</td>
		<td align="left">' . $start . '</td>
		<td align="left">' . $end . '</td>
		</tr>
		';
	}
	echo '</tbody></table>';
	echo "<p></p>";

echo '<h2>Your Hours</h2>
	<table width="60%">
	<thead>
	<tr>
	<th align="left"><strong>Day</strong></th>
	<th align="left"><strong>Time Start</strong></th>
	<th align="left"><strong>Time End</strong></th>
	</tr>
	</thead>
	<tbody>';
	for ($i=0;$i<7;$i++){
		$day = $dw[$i];
		$start = emp_start($dbc, $emp, $day);
		$end = emp_end($dbc, $emp, $day);

		echo '<tr>
		<td align="left">' . $day . '</td>
		<td align="left">' . $start . '</td>
		<td align="left">' . $end . '</td>
		</tr>
		';
	}
	echo '</tbody></table>';
	echo "<p></p>";

echo '<p></p><form action="setavail.php" method="post">';
	for ($i=0;$i<7;$i++){
		set_hours($dw[$i]);
	}
echo'<p><input type="submit" name="submit" value="Set"></p></form>';
} else {
		echo 'You do not have permission to view this page.';
}


?>


<?php include('includes/footer.html'); ?>