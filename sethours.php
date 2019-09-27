<?php # Script Add Hours

$page_title = 'Set Hours';
include('includes/header.html');
echo '<h1>Set Hours</h1>';
require('sethours_func.php');
require('../mysqli_connect.php');


	$dw = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

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
			clear($dbc, $day);
			if('clear'){
			adjust_time($dbc, $day, $hs, $he, $ms, $me, $ts, $te);
			}
		}
		$start = start_time($dbc, $day);
		$end = end_time($dbc, $day);
	}
}
			

echo '<table width="60%">
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
		$start = strtotime(start_time($dbc, $day));
		$start = date('h:i A', $start);
		$end = strtotime(end_time($dbc, $day));
		$end = date('h:i A', $end);

		echo '<tr>
		<td align="left">' . $day . '</td>
		<td align="left">' . $start . '</td>
		<td align="left">' . $end . '</td>
		</tr>
		';
	}
	echo '</tbody></table>';
	echo "<p></p>";


	echo '<form action="sethours.php" method="post">';

	for ($i=0;$i<7;$i++){
		set_hours($dw[$i]);
	}

echo '<p><input type="submit" name="submit" value="Set"></p>
</form>';
include('includes/footer.html'); ?>