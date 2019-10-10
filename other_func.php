<?php

function employee_id ($dbc, $user_id){
	$q = "SELECT emp_id FROM employee WHERE user_id='$user_id'";
	$r = @mysqli_query($dbc, $q);
	if($r){
		$row = mysqli_fetch_array($r);
		$id = $row['emp_id'];
		return $id;
	}
}

function clear_emp($dbc, $day, $id){
	$q = "SELECT business_hours.bh_id FROM business_hours INNER JOIN employee_hours ON business_hours.bh_id = employee_hours.bh_id WHERE emp_id='$id' AND dw='$day'";
	$r = @mysqli_query($dbc, $q);
	if($r){
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$bh = $row['bh_id'];
			$q2 = "DELETE FROM employee_hours WHERE bh_id='$bh'";
			$r2 = @mysqli_query($dbc, $q2);

			if($r2){
				return true;
			}
		}
	}
}

function emp_start($dbc, $id, $day){
	$q = "SELECT ts_start FROM timeslots INNER JOIN business_hours ON timeslots.ts_id = business_hours.ts_id INNER JOIN employee_hours ON business_hours.bh_id = employee_hours.bh_id WHERE dw='$day' AND emp_id='$id' ORDER BY ts_start ASC LIMIT 1";
	$r = @mysqli_query($dbc, $q);
		
		if($r){
			$num = mysqli_num_rows($r);
			echo $num;
			if($num==0){
				$start = 'Unavailable';
			} else {
				$row = mysqli_fetch_array($r);
				$start = $row['ts_start'];
			}
		} else {
			$start = 'Unavailable';
		}
	return $start;
}

function emp_end($dbc, $id, $day){
	$q = "SELECT ts_end FROM timeslots INNER JOIN business_hours ON timeslots.ts_id = business_hours.ts_id INNER JOIN employee_hours ON business_hours.bh_id = employee_hours.bh_id WHERE dw='$day' AND emp_id='$id' ORDER BY ts_end DESC LIMIT 1";
	$r = @mysqli_query($dbc, $q);
		
		if($r){
			$num = mysqli_num_rows($r);
			if($num==0){
				$end = 'Unavailable';
			} else {
				$row = mysqli_fetch_array($r);
				$end = $row['ts_end'];
			}
		} else {
			$end = 'Unavailable';
		}
	return $end;
}

function adjust_emp($dbc, $emp, $day, $hs, $he, $ms, $me, $ts, $te){

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
				$bh = emp_exists($dbc, $day, $st);
				
				if($bh==null){
					echo 'Error! Must be within business hours.';
					break;
				} else {
					insert_times($dbc, $emp, $bh);
					if ('insert_times'){
						insert_avail($dbc, $day, $emp, $st);
					} else {
						//echo 'Error!';
					}
				}
			}
		}	
	}
}

function insert_times($dbc, $emp, $bh){
	$q = "INSERT INTO employee_hours (emp_id, bh_id) VALUES ('$emp', '$bh')";
	$r = @mysqli_query($dbc, $q);

	if($r){
		return true;
	} else {
		return false;
	}
}

function insert_avail($dbc, $day, $emp, $st){
	$q = "SELECT date_id FROM dates WHERE dw='$day'";
	$r = @mysqli_query($dbc, $q);
	
	if($r){
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$date = $row['date_id'];
			//echo "$date ok<br>";

			$q2 = "SELECT ts_id FROM timeslots WHERE ts_start='$st'";
			$r2 = @mysqli_query($dbc, $q2);

			if($r2){
				while ($row = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
					$time = $row['ts_id'];

					//echo "$time ok<br>";

					$q3 = "INSERT INTO avail (date_id, emp_id, avail, ts_id) VALUES ('$date', '$emp', 'yes', '$time')";
					$r3 = @mysqli_query($dbc, $q3);
				}
			}
		}
	}
}

function emp_exists($dbc, $day, $st){
	$q = "SELECT bh_id FROM business_hours INNER JOIN timeslots ON business_hours.ts_id = timeslots.ts_id WHERE ts_start='$st' AND dw='$day'";
	$r = @mysqli_query($dbc, $q);
	if($r){
		$num = mysqli_num_rows($r);
		if($num==0){
			return null;
		} else {
			$row =  mysqli_fetch_array($r);
			return $row['bh_id'];
		}
	} else {
		echo 'Another error!';
		return null;
	}
}

function get_user ($dbc, $user_id){
	$q = "SELECT first_name, last_name FROM users WHERE user_id='$user_id'";
	$r = @mysqli_query($dbc, $q);
	if($r){
		$row = mysqli_fetch_array($r);
		$name = $row['first_name'] . ' ' . $row['last_name'];
		return $name;
	}
}

function get_service ($dbc, $service_id){
	$q = "SELECT serv_title FROM service WHERE serv_id='$service_id'";
	$r = @mysqli_query($dbc, $q);
	if($r){
		$row = mysqli_fetch_array($r);
		$name = $row['serv_title'];
		return $name;
	}
}

function delete_user ($dbc, $user_id){
	$name = get_user($dbc, $user_id);
	$q = "DELETE users, client FROM users INNER JOIN client on users.user_id = client.user_id WHERE users.user_id='$user_id'";
	$r = @mysqli_query($dbc, $q);
	if($r){
		echo "$name deleted.";
	} else {
		echo "User deletion failed.";
	}
}

function delete_service ($dbc, $service_id){
	$name = get_service($dbc, $service_id);
	$q = "DELETE FROM service WHERE serv_id='$service_id'";
	$r = @mysqli_query($dbc, $q);
	if($r){
		echo "$name deleted.";
	} else {
		echo "User deletion failed.";
	}
	
}

?>