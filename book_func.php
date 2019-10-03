<?php

function view_cat ($dbc){
	$q = "SELECT * FROM category ORDER BY category_name ASC";
	$r = @mysqli_query($dbc, $q);

	$num = mysqli_num_rows($r);
	if($num>0){

		$i = 0;
	 	
	 	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	 		$nm = $row['category_name'];
	 		$id = $row['category_id'];
	 		$i++;
	 //	echo "<option value=$id2>$nm</option>";
	 		
	 		echo "<span id='$id' class='button' onclick=Show('s$id')>$nm</span> ";
	 		
		}
		return $i;
	 mysqli_free_result($r);
	}
}

function view_serv ($dbc, $id){
	$q = "SELECT * FROM service WHERE serv_category=$id ORDER BY serv_title ASC";
	$r = @mysqli_query($dbc, $q);

	$num = mysqli_num_rows($r);
	if($num>0){
	 	
	 	//$a = array();

	 	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	 		$nm = $row['serv_title'];
	 		$id2 = $row['serv_id'];
	 		echo "<span id='se$id2' class='button' onclick=Show('info$id2')>$nm</span> ";
		}
		
	 mysqli_free_result($r);
	}
}

function serv_info ($dbc){
	$q = "SELECT * FROM service";
	$r = @mysqli_query($dbc, $q);

	if($r){
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$id = $row['serv_id'];
			$title = $row['serv_title'];
			$cost = $row['serv_cost'];
			$dur = $row['serv_duration'];
			$desc = $row['description'];

			echo "<span id='info$id' class='hidden'>";
			echo "<h3>$title</h3>
			  <p>$desc</p>
			  <p><b>Cost:</b> $cost</p>
			  <p><b>Duration:</b> $dur minutes</p>";
			//echo "<a href='#$id'>Select</a>";
			  echo '<a href="book2.php?id=' . $row['serv_id'] . '">Select</a>';
			echo "</span>";
		}

	}
}

function get_serv($dbc, $id){
	$q = "SELECT * FROM service WHERE serv_id='$id'";
	$r = @mysqli_query($dbc, $q);

	if($r){
		echo "<span id='#select$id'>" ;
	}

}

function view_dates($dbc, $month, $year){

	$q = "SELECT dw, d FROM dates WHERE y='$year' AND m='$month'";
	$r= @mysqli_query($dbc, $q);
	echo "<span class='month' id='$month'>";
	echo '<ul><li class="prev">&#10094;</li>';
    echo "<li class='name'>$month $year</li>";
    echo '<li class="next">&#10095;</li>';
  	echo "</ul></span>";

  	echo '<ul class="dw">
  	<li>Su</li>
  	<li>Mo</li>
  	<li>Tu</li>
  	<li>We</li>
  	<li>Th</li>
  	<li>Fr</li>
  	<li>Sa</li>
	</ul>
	';			
	echo '<ul class="day">';

	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$day = $row['d'];
		$dw = $row['dw'];
		if($day=='1' && $dw!=='Sunday'){
			switch ($dw) {
				case 'Monday':
					$i=1;
					break;
				case 'Tuesday':
					$i=2;
					break;
				case 'Wednesday':
					$i=3;
					break;
				case 'Thursday':
					$i=4;
					break;
				case 'Friday':
					$i=5;
					break;
				case 'Saturday':
					$i=6;
					break;
			}

			$n=$i;
			while($n>0){
				echo '<li> </li>';
				$n--;
			}
		}

		echo "<li id='$month$day' onclick=Show('$month$day-2')><a href='#'>$day</a></li>";
			if($i=='7'){
				$i=0;
			} else {
				$i++;
			}


		if($day=='31'){
			if($month=='January' || 'March' || 'May' || 'July' || 'August' || 'October' || 'December'){
				$n=6-$i;
				while($n>0){
					echo '<li> </li>';
					$n--;
				}
			}
		} else if($day=='30'){
			if($month=='April' || 'June' || 'September' || 'November'){
				$n=6-$i;
				while($n>0){
					echo '<li> </li>';
					$n--;
				}
			}
		} else if($month=='February'){
			if($day=='29' && $year%4=='0'){
				$n=6-$i;
				while($n>0){
					echo '<li> </li>';
					$n--;
				}
			}	else if($day=='28'){
				$n=6-$i;
				while($n>0){
					echo '<li> </li>';
					$n--;
				}
			}
		}
	}
	echo '</ul>';		
}

function select_day($dbc, $month, $id){
	$q = "SELECT * FROM dates WHERE m='$month'";
	$r = @mysqli_query($dbc, $q);

	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$day = $row['d'];
		echo "<span id='$month$day-2' class='hidden'>";
		$servid = $id;
		$length = get_length($dbc, $servid);
		$q2 = "SELECT date_id FROM dates WHERE d='$day' AND m='$month'";
		$r2 = @mysqli_query($dbc, $q2);
		$row = mysqli_fetch_array($r2, MYSQLI_ASSOC);
		$date = $row['date_id'];
		timeslots($dbc, $length, $date, $id);
		echo "</span>";
	}
}

function get_length($dbc, $serv_id){
	$q = "SELECT serv_duration FROM service WHERE serv_id='$serv_id'";
	$r = @mysqli_query($dbc, $q);

	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);

	$length = $row['serv_duration'];
	$length = $length / 15;
	return $length;
}



function timeslots($dbc, $length, $date, $serv){
	$q = "SELECT ts_start, avail_id, emp_id FROM timeslots INNER JOIN avail ON timeslots.ts_id = avail.ts_id WHERE date_id='$date' ORDER BY ts_start ASC";
	$r = @mysqli_query($dbc, $q);

	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
		$i = $length;
		$start = $row['ts_start'];
		$id = $row['avail_id'];
		$curr = $start;
		//echo $curr;
		while($i>0){
			$split = explode(':', $curr);
			$curr_hour = $split[0];
			$curr_min = $split[1];
			$next_min = $curr_min + 15;
				if($next_min=='60'){
					$next_min='00';
					$next_hour=$curr_hour + 1;
					$next = $next_hour . ':' . $next_min . ':00';
				} else {
					$next = $curr_hour . ':' . $next_min . ':00';
				}

				$q2 = "SELECT avail FROM avail INNER JOIN timeslots ON avail.ts_id = timeslots.ts_id WHERE ts_start = '$next'";
				$r2 = @mysqli_query($dbc, $q2);

		
			$row = mysqli_fetch_array($r2);
			$avail = $row['avail'];

			if($avail=='no'){
				break;
			} else {
				if($i=='1'){
					echo "<span id='$id' class='button' onclick=Show('$id-show')>$start</span> ";
					show_timeslot($dbc, $id, $serv);
				}
				//echo("$curr<br>$next<br>.<br>");
				$curr = $next;
				$i--;

			}
		}
	}
}

function show_timeslot($dbc, $avail, $serv){
	$q = "SELECT emp_id, date_id, ts_id FROM avail WHERE avail_id='$avail'";
	$r = @mysqli_query($dbc, $q);

	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$id = $row['emp_id'];
	$date = $row['date_id'];
	$ts = $row['ts_id'];

	$q = "SELECT first_name FROM users INNER JOIN employee ON users.user_id = employee.user_id WHERE emp_id='$id'";
	$r = @mysqli_query($dbc, $q);

	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$emp = $row['first_name'];

	echo "<span id='$avail-show' class='hidden'><br>This appointment is with $emp.<br><p class='booking'><a href='#' onclick=Show('$avail-book')>Book?</a></p></span>";
	echo "<span id='$avail-confirm' class='confirm'>";
	confirm($dbc, $serv, $id, $emp, $ts, $date, $avail);
	echo '</span>';
}

function get_emp($dbc, $serv){
	$q = "SELECT emp_id FROM emp_serv WHERE serv_id='$serv'";
	$r = @mysqli_query($dbc, $q);
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
		$emp = $row['emp_id'];
		$q2 = "SELECT first_name FROM users INNER JOIN employee ON users.user_id = employee.user_id WHERE emp_id = $emp";
		$r2 = @mysqli_query($dbc, $q2);
		while($row = mysqli_fetch_array($r2, MYSQLI_ASSOC)){
				$name = $row['first_name'];
		}
	}
}

function confirm($dbc, $serv, $empid, $empname, $time, $date, $avail){
	$q = "SELECT serv_title FROM service WHERE serv_id='$serv'";
	$r = @mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$servtitle = $row['serv_title'];

	$q = "SELECT ts_start FROM timeslots WHERE ts_id='$time'";
	$r = @mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$starttime = $row['ts_start'];

	$q = "SELECT m, d FROM dates WHERE date_id='$date'";
	$r = @mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$month = $row['m'];
	$day = $row['d'];
	echo "<span class='hidden' id='$avail-book'><h4>You are scheduling an appointment for a(n) $servtitle with $empname at $starttime on $month $day.</h4><br><a href='book3.php?serv=$serv&emp=$empid&time=$time&date=$date' id='conf'>Confirm</a><br></span>";
}

function get_client($dbc, $user){
	$q = "SELECT client_id FROM client WHERE user_id='$user'";
	$r = @mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$client = $row['client_id'];
	return $client;
}

function schedule($dbc, $serv, $user, $emp, $time, $date){
	$q = "SELECT avail_id, avail FROM avail WHERE date_id='$date' AND ts_id='$time' AND emp_id='$emp'";
	$r = @mysqli_query($dbc, $q);

	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$avail = $row['avail_id'];
	$is = $row['avail'];

	if($is=='no'){
		echo '<p class="error">The selected appointment time is no longer available. Please try again.</p>';
	} else {

		$client = get_client($dbc, $user);

		$q = "INSERT INTO appt (avail_id, client_id, serv_id) VALUES ($avail, $client, $serv)";
		$r = @mysqli_query($dbc, $q);

		if($r){
			$q2 = "UPDATE `avail` SET `avail`=('no') WHERE avail_id='$avail'";
			$r2 = @mysqli_query($dbc, $q2);

			if($r2){
				echo '<h3>Your appointment was successfully scheduled:</h3>';
				scheduled_appt($dbc, $serv, $emp, $time, $date);
				}
			}
		}
}

function scheduled_appt($dbc, $serv, $empid, $time, $date){
	$q = "SELECT serv_title FROM service WHERE serv_id='$serv'";
	$r = @mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$servtitle = $row['serv_title'];

	$q = "SELECT first_name FROM users INNER JOIN employee ON employee.user_id = users.user_id";
	$r = @mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$empname = $row['first_name'];

	$q = "SELECT ts_start FROM timeslots WHERE ts_id='$time'";
	$r = @mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$starttime = $row['ts_start'];

	$q = "SELECT m, d FROM dates WHERE date_id='$date'";
	$r = @mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$month = $row['m'];
	$day = $row['d'];
	echo "<h3>$servtitle with $empname at $starttime on $month $day.</h3>";
}

?>