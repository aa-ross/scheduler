<?php #Script - Populate Cal

$page_title = 'Populate Calendar';
include('includes/header.html');

if($_SERVER['REQUEST_METHOD']=='POST'){

	require('../mysqli_connect.php');

		$yr = trim($_POST['yr']);

		$q = "SELECT date_id FROM dates WHERE y=$yr LIMIT 1";
		$r = @mysqli_query($dbc, $q);

		if($r){
			$num = mysqli_num_rows($r);
			if($num>0){
				echo '<p class="error">You have already populated the calendar with dates for this year: </p>';
			} else {
				$dws = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
				$months = ['Null', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
				if($yr==2018){
					$i=0;
				} else{
					$add=$yr-2018;
					$i=$add;
				}
				for($m=1;$m<13;$m++){
					for($d=1;$d<32;$d++){
						if ($i==7){
							$i=0;
						}
						if($yr%4==0 && $m==2){
							if($d==30){
								$m=3;
								$d=1;
							}
						} else if ($m==2 && $d==29) {
							$m=3;
							$d=1;
						} else if (($m==4 || $m==6 || $m==9 || $m==11) && ($d==31)){
							$m=($m+1);
							$d=1;
						}
						$dw = $dws[$i];
						$q2 = "INSERT INTO dates (dw, d, m, y) VALUES ('$dw', '$d', '$months[$m]', '$yr')";
						$r2 = @mysqli_query($dbc, $q2);

						if($r2){
						//	echo '<h1>Worked!</h1>';
						}
					
						$i++;
					}
				}
			}
		} else {
			echo '<h1>Error!</h1>';
		}
	mysqli_close($dbc);
	//include('includes/footer.html');
}

?>


<h1>Populate Calendar</h1>
<form action="populatecal.php" method="POST">
	<p>Year to Add:</p><select name="yr">
		<option value="2018">2018<?php if(isset($_POST['yr']) && ($_POST['yr']=='2018')) echo 'selected="selected"'; ?></option>
		<option value="2019">2019<?php if(isset($_POST['yr']) && ($_POST['yr']=='2019')) echo 'selected="selected"'; ?></option>
		<option value="2020">2020<?php if(isset($_POST['yr']) && ($_POST['yr']=='2020')) echo 'selected="selected"'; ?></option>
	</select>
	<p><input type="submit" name="submit" value="Populate!"></p>
</form>
	<?php include('includes/footer.html'); ?>
