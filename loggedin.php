<?php 
if (!isset($_COOKIE['user_id'])) {
	require('includes/login_functions.inc.php');
	require('../mysqli_connect.php');
	redirect_user();
}
$page_title = 'Options';
include('includes/header.html');

if(isset($_COOKIE['user_id'])){
	$user = ($_COOKIE['user_id']);

if(isset($_COOKIE['user_type']) && $_COOKIE['user_type'] == 'admin'){
	echo '<h1>Admin Options</h1>';
	echo '<ul>
	<li><a href="create_employee.php">Create New Employee Account</a></li>
				<li><a href="view_users.php">View All Users</a></li>
				<li><a href="populatecal.php">Populate Calendar</a></li>
				<li><a href="sethours.php">Set Business Hours</a></li>
				<li><a href="create_category.php">Create Category</a></li>
				<li><a href="manage_services.php">Manage Services</a></li>
		</ul>';
} else if(isset($_COOKIE['user_type']) && $_COOKIE['user_type'] == 'emp'){
	echo '<h1>Employee Options</h1>';
	echo '<ul><li><a href="setavail.php">Set Availability</a></li>
		<li><a href="set_services.php">Set Services</a></li></ul>';
} else {
	echo '<h1>Client Options</h1>';
	$q = "SELECT avail_id, serv_id FROM appt INNER JOIN client ON appt.client_id = client.client_id WHERE user_id='$user'";
	$r = @mysqli_query($dbc, $q);

	if($r){
		$row = mysqli_fetch_array($r);
		$a = $row['avail_id'];
		$s = $row['s_id'];
		
		$q2 = "SELECT time_id, date_id, emp_id FROM avail WHERE avail_id='$a'";
		$r2 = @mysqli_query($dbc, $q2);

		if($r2){
			$row = mysqli_fetch_array($r, MYSQL_ASSOC);
			$e = $row['emp_id'];
			$t = $row['time_id'];
			$d = $row['date_id'];
			echo '<h2>Upcoming Appointments</h2>';
			scheduled_appt($dbc, $s, $e, $t, $d);
		}
} else {
	echo 'You have no scheduled appointments at this time.';
}
	
}

echo "<p><a href=\"logout.php\">Logout</a></p>";
}
include('includes/footer.html');
?>