<?php

$page_title = 'Create New Employee Account';
include('includes/header.html');

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$errors = [];

	if(empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = trim($_POST['first_name']);
	}

	if(empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = trim($_POST['last_name']);
	}

	if(empty($_POST['email'])){
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = trim($_POST['email']);
	}

	if(!empty($_POST['pass1'])){
		if($_POST['pass1'] != $_POST['pass2']){
			$errors[] = 'Your password did not match the confirmed password.';
		} else {
			$p = trim($_POST['pass1']);
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}

	if(empty($errors)) {
		require('../mysqli_connect.php');

		$emp = 'emp';

		$q = "INSERT INTO users (first_name, last_name, email, pass, user_type) VALUES ('$fn', '$ln', '$e', SHA2('$p', 512), '$emp')";

		$r = @mysqli_query($dbc, $q);

		if($r){

			$q = "SELECT user_id FROM users WHERE email='$e' LIMIT 1";
			$r = @mysqli_query($dbc, $q);
			$row = mysqli_fetch_array($r);
			$id = $row['user_id'];

			if($r){
				$q = "INSERT INTO employee (user_id) VALUES ('$id')";
				$r = @mysqli_query($dbc, $q);

				if ($r){
					echo '<h1>Success!</h1>
			   		<p>New employee account has been created.</p><p><br></p>';
				}
			}
		} else {
			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';
		}

		mysqli_close($dbc);

		include('includes/footer.html');
		exit();

	} else {
		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br>';
		foreach($errors as $msg){
			echo " - $msg<br>\n";
		}
		echo '</p><p>Please try again.</p><p><br></p>';
	}
}

?>

<h1>Create New Employee Account</h1>
<form action="create_employee.php" method="post">
	<p>First Name: <input type="text" name="first_name" size="15" maxlength="20" value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name']; ?>"></p>
	<p>Last Name: <input type="text" name="last_name" size="15" maxlength="40" value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name']; ?>"></p>
	<p>Email Address: <input type="email" name="email" size="20" maxlength="60" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"></p>
	<p>Password: <input type="password" name="pass1" size="10" maxlength="20" value="<?php if(isset($_POST['pass1'])) echo $_POST['pass1']; ?>"></p>
	<p>Confirm Password: <input type="password" name="pass2" size="20" maxlength="20" value="<?php if(isset($_POST['pass2'])) echo $_POST['pass2']; ?>"></p>
	<p><input type="submit" name="submit" value="Register"></p>
</form>
<?php include('includes/footer.html'); ?>