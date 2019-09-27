<?php 

$page_title = 'Edit Profile';
include('includes/header.html');
echo '<h1>Edit Profile</h1>';
require('profile_func.php');
require('../mysqli_connect.php');

if(!isset($_COOKIE['user_id'])){
	echo 'You must be logged in to view this page.';
} else {
	$id = $_COOKIE['user_id'];


if($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = [];

	if(empty($_POST['first_name'])){
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}

	if (empty($_POST['last_name'])){
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}

	if (empty($_POST['email'])){
		$errors[] = 'You forgot to enter an email address.';
	} else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}

	$p = mysqli_real_escape_string($dbc, trim($_POST['phone']));

	if (empty($errors)) {
		edit_user($dbc, $id, $fn, $ln, $e, $p);
			if(isset($_COOKIE['user_type']) && $_COOKIE['user_type'] == 'emp'){
				$y = mysqli_real_escape_string($dbc, trim($_POST['years']));
				$f = mysqli_real_escape_string($dbc, trim($_POST['favorite']));
				edit_emp($dbc, $id, $y, $f);
			} else if(isset($_COOKIE['user_type']) && $_COOKIE['user_type'] == 'client'){
				$pr = mysqli_real_escape_string($dbc, trim($_POST['pref']));
				$st = mysqli_real_escape_string($dbc, trim($_POST['st']));
				$sc = mysqli_real_escape_string($dbc, trim($_POST['sc']));
				edit_client($dbc, $id, $pr, $st, $sc);
			}

			if('edit_user' || 'edit_emp'){
				echo '<p>Your profile has been edited.</p>';
			} else {
			echo '<p class="error">Could not be edited due to a system error. We apologize for any inconvenience.</p>';
			echo '<p>' . mysqli_error($dbc) . '<br>Query: ' . $q . '</p>';
			}
	} else {
		echo '<p class="error">The following error(s) occurred:<br>';
		foreach ($errors as $msg) {
			echo " . $msg<br>\n";
		}
		echo '</p><p>Please try again.</p>';
	}
}


echo '<form action="edit_profile.php" method="post">';

$q = "SELECT first_name, last_name, email, phone FROM users WHERE user_id=$id";
$r = @mysqli_query($dbc, $q);

if($r){

	if (mysqli_num_rows($r) == 1){
		$row = mysqli_fetch_array($r, MYSQLI_NUM);

		echo '
		<p>First Name: <input type="text" name="first_name" size="15" maxlength="15" value="' . $row[0] . '"></p>
		<p>Last Name: <input type="text" name="last_name" size="15" maxlength="30" value="' . $row[1] . '"></p>
   		 <p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="' . $row[2] . '"></p>
    	<p>Phone: <input type="phone" name="phone" size="20" maxlength="60" value="' . $row[3] . '"></p>
    	';
	} else {
		echo '<p class="error">This page has been accessed in 	error</p>';
	}
} else {
	echo "Query failed: $q";
}

if(isset($_COOKIE['user_type']) && $_COOKIE['user_type'] == 'emp'){
	$q = "SELECT years, favorite FROM employee WHERE user_id=$id";
	$r = @mysqli_query($dbc, $q);
	if($r){
	if (mysqli_num_rows($r) == 1){
		$row = mysqli_fetch_array($r, MYSQLI_NUM);
		echo '
		<p>Years of Experience: <input type="text" name="years" size="2" maxlength="2" value="' . $row[0] . '"></p>
		<p>Favorite Service: <input type="text" name="favorite" size="20" maxlength="60" value="' . $row[1] . '"></p>';
	}
}
	

} else if(isset($_COOKIE['user_type']) && $_COOKIE['user_type'] == 'admin'){
	
	//sel user prof to edit
	//det sel user id type
	//show form app
	//run func

} else {
	$q = "SELECT pref, skintype, skinconcerns FROM client WHERE user_id=$id";
	$r = @mysqli_query($dbc, $q);
	if($r){
	if (mysqli_num_rows($r) == 1){
		$row = mysqli_fetch_array($r, MYSQLI_NUM);
		echo '
		<p>Contact Preference: <select name="pref">
		<option value="Phone"';if($row[0]=='Phone') echo 'selected="selected"'; echo'>Phone</option>
		<option value="Email"';if($row[0]=='Email') echo 'selected="selected"'; echo '>Email</option>
		</select></p>';
		echo '
		<p>Contact Preference: <select name="st">
		<option value="Combination"';if($row[1]=='Combination') echo 'selected="selected"'; echo'>Combination</option>
		<option value="Oily"';if($row[1]=='Oily') echo 'selected="selected"'; echo '>Oily</option>
		<option value="Dry"';if($row[1]=='Dry') echo 'selected="selected"'; echo'>Dry</option>
		</select></p>';
		//<input type="text" name="pref" size="2" maxlength="2" value="' . $row[0] . '"></p>
		echo '<p>Skin Concerns: <input type="text" name="sc" size="20" maxlength="160" value="' . $row[2] . '"></p>';
	}
}
}

echo '<p><input type="submit" name="submit" value="Submit"></p>
  	  <p><input type="hidden" name="id" value="' . $id . '">
   	 </form>';
}
include('includes/footer.html');
?>