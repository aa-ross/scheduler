<?php

	$page_title = 'Set Services';
	include('includes/header.html');
	echo '<h1>Set Services</h1>';
	require('other_func.php');
	require('../mysqli_connect.php');

if (isset($_COOKIE['user_type']) && $_COOKIE['user_type'] == 'emp'){
		$user = $_COOKIE['user_id'];
		$emp = employee_id($dbc, $user);
		}

if ($_SERVER['REQUEST_METHOD']=='POST'){

	$errors=[];

	$service = trim($_POST['service']);

	$q = "INSERT INTO employee_service (emp_id, serv_id) VALUES ('$emp', '$service')";
	$r = @mysqli_query($dbc, $q);

	if($r){
		echo "<meta http-equiv='refresh' content='0'>";
	}
}
?>

<p></p><h3>Your Services</h3>

	<?php
	$q = "SELECT serv_title FROM service INNER JOIN employee_service ON service.serv_id = employee_service.serv_id WHERE emp_id='$emp' ORDER BY serv_title ASC";
	$r = @mysqli_query($dbc, $q);
		if ($r){
			$num = mysqli_num_rows($r);
			if($num==0){
				echo "None";
			} else {
				while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	 				$s = $row['serv_title'];
	 				echo "<span class='button'>$s </span>";
				}
			}
		} else {
			echo 'Error!';
		}
?>

<p></p><h3>Add Services</h3>
<form action="set_services.php" method="post">
		<?php
		echo '<p>Services:
	 	<select name="service">';
			$q = "SELECT serv_id, serv_title FROM service ORDER BY serv_title ASC";
			$r = @mysqli_query($dbc, $q);
			while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$id = $row['serv_id'];
	 		$serv = $row['serv_title'];
	 		echo "<option value=$id>$serv</option>";
	 		}
	 		echo '</select>';
		?>
	<p><input type="submit" name="submit" value="Add"></p>
</form>

<?php include('includes/footer.html');