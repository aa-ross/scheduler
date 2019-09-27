<?php

function set_hours($day){
	echo "<p><label>$day</label>";
	$hs = $day . 'hs';
	$he = $day . 'he';
	$ms = $day . 'ms';
	$me = $day . 'me';
	$ts = $day . 'ts';
	$te = $day . 'te';
	echo "<select name='$hs'>";
	for($num=1;$num<13;$num++){
		echo "<option value='$num'";
		if(isset($_POST["$hs"]) && ($_POST["$hs"]=='$num')){
			echo ' selected="selected"';
		}
			echo ">$num</option>";
	}
	echo '</select> : ';
	echo "<select name='$ms'>";
		$min = ['00','15','30','45'];
		foreach($min as $mn){
			echo "<option value='$mn'";
			if(isset($_POST['$mn']) && ($_POST['$mn']=='$mn')){
				echo ' selected="selected"';
			}
			echo ">$mn</option>";
		}
	echo '</select> ';
	echo "<select name='$ts'>";
	$time = ['AM','PM'];
	foreach($time as $ts2){
		echo "<option value='$ts2'";
		if(isset($_POST['$ts2']) && ($_POST['$ts2']=='$ts2')){
			echo ' selected="selected"';
		}
			echo ">$ts2</option>";
	}
		echo '</select> - ';
	echo "<select name='$he'>";
	for($num=1;$num<13;$num++){
		echo "<option value='$num'";
		if(isset($_POST["$he"]) && ($_POST["$he"]=='$num')){
			echo ' selected="selected"';
		}
			echo ">$num</option> ";
	}
	echo '</select> : ';
	echo "<select name='$me'>";
		$min = ['00','15','30','45'];
		foreach($min as $mn){
			echo "<option value='$mn'";
			if(isset($_POST['$mn']) && ($_POST['$mn']=='$mn')){
				echo ' selected="selected"';
			}
			echo ">$mn</option>";
		}
	echo '</select> ';
	echo "<select name='$te'>";
	$time = ['AM','PM'];
	foreach($time as $ts2){
		echo "<option value='$ts2'";
		if(isset($_POST['$ts2']) && ($_POST['$ts2']=='$ts2')){
			echo ' selected="selected"';
		}
			echo ">$ts2</option>";
	}
	echo '</select> ';
}

function adjust_time($dbc, $day, $hs, $he, $ms, $me, $ts, $te){

	if($hs=='12' && $ts=='AM'){
		$hs='00';
	} else if($hs!=='12' && $ts=='PM'){
		$hs=$hs+12;
	} 

	if($he=='12' && $te=='AM'){
		$he='00';
	} else if($he!=='12' && $te=='PM'){
		$he=$he+12;
	}

	if($he<$hs){
		echo '<p class="error">Error: Start time must be before end time.</p>';	
	} else {
		$n=$ms;
		for ($x=$hs; $x<$he+1; $x++){
			if($x!==$hs){
				$n='00';
			}
		while($n<60){
			if($x==$he && $n==$me){
				break;
			}
			$st="$x:$n";
			$n=$n+15;
				if($n==60){
					$nh=$x+1;
					$en="$nh:00";	
				} else {
					$en="$x:$n";
				}
			/*$exists = exists($dbc, $st);
			if($exists=='null'){
				echo 'Does not exist<br>';
				create_ts($dbc, $st, $en);
				set_business($dbc, $st, $day);
			} else {
				*/
				create_ts($dbc, $st, $en);
				set_business($dbc, $st, $day);
			//}
		}
		}	
	}
}

function create_ts($dbc, $st, $en){
	$q = "INSERT INTO timeslots (ts_start, ts_end) VALUES ('$st', '$en')";
	$r = @mysqli_query($dbc, $q);
	if($r){
		
		return true;
	}

}

function exists($dbc, $st){
	$q = "SELECT ts_id FROM timeslots WHERE ts_start='$st'";
	$r = @mysqli_query($dbc, $q);
	$num = mysqli_num_rows($r);
	$row = mysqli_fetch_array($r);
	$check = $row['ts_id'];
	if($num=='0'){
		return null;
	} else {
		return $check;
	}
}

function set_business($dbc, $st, $day){
	$q = "SELECT ts_id FROM timeslots WHERE ts_start='$st' LIMIT 1";
	$r = @mysqli_query($dbc, $q);
	if($r){
		$row = mysqli_fetch_array($r);
		$id = $row['ts_id'];
		$q2 = "INSERT INTO business_hours (ts_id, dw) VALUES ('$id', '$day')";
		$r2 = @mysqli_query($dbc, $q2);
	}
}

function start_time($dbc, $day){
	$q = "SELECT ts_start FROM timeslots INNER JOIN business_hours ON timeslots.ts_id = business_hours.ts_id WHERE dw='$day' ORDER BY ts_start LIMIT 1";
	$r = @mysqli_query($dbc, $q);
	if($r){
		$num = mysqli_num_rows($r);
		if($num==0){
			$start = 'Closed';
		} else {
			$row = mysqli_fetch_array($r);
			$start = $row['ts_start'];
		}
	}
	return $start;
}

function end_time($dbc, $day){
	$q = "SELECT ts_end FROM timeslots INNER JOIN business_hours ON timeslots.ts_id = business_hours.ts_id WHERE dw='$day' ORDER BY ts_start DESC LIMIT 1";
	$r = @mysqli_query($dbc, $q);
	if($r){
		$num = mysqli_num_rows($r);
		if($num==0){
			$end = 'Closed';
		} else {
			$row = mysqli_fetch_array($r);
			$end = $row['ts_end'];
		}
	return $end;
	}
}

function clear($dbc, $day){
	$q = "DELETE FROM business_hours WHERE dw='$day'";
	$r = @mysqli_query($dbc, $q);

	if($r){
		return true;
	}
}

?>