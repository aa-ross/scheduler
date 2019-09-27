<?php

	$page_title = 'Manage Services';
	include('includes/header.html');
	echo '<h1>Manage Services</h1>';

	require('../mysqli_connect.php');

if ($_SERVER['REQUEST_METHOD']=='POST'){

	$desc = trim($_POST['description']);

	$errors=[];

	$c = trim($_POST['category']);

	if(empty($_POST['title'])) {
		$errors[] = 'You must enter a name for the service.';
	} else {
		$t = trim($_POST['title']);
	}

	if(empty($_POST['duration'])) {
		$errors[] = 'You must enter a duration for the service.';
	} else {
		$d = trim($_POST['duration']);
	}

	if(empty($_POST['cost'])) {
		$errors[] = 'You must enter a cost for the service.';
	} else {
		$co = trim($_POST['cost']);
	}

	if(empty($errors)){
		$desc = trim($_POST['description']);

		$q = "INSERT INTO service (serv_title, serv_duration, serv_cost, serv_category) VALUES ('$t', '$d', '$co', '$c')";
		$r = @mysqli_query($dbc, $q);

		if($r){
			echo "<meta http-equiv='refresh' content='0'>";
		}else {
			echo 'An error occurred.';
		}
	} else {
		echo 'An error occurred.';
	}
}

?>
<h2>Current Services</h2>

<?php
	$q = "SELECT serv_title FROM service ORDER BY serv_title ASC";
	$r = @mysqli_query($dbc, $q);
		if ($r){
			$num = mysqli_num_rows($r);
			if($num==0){
				echo "None";
			} else {
				while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	 				$s = $row['serv_title'];
	 				echo "<span class='button'>$s</span>";
				}
			}
		} else {
			echo 'Error!';
		}
		echo "<p></p><p></p><p></p><p></p><p></p><p></p>";
?>
<p></p><h2>Create New Service</h2>


<form action="manage_services.php" method="post">

<?php
$q = "SELECT category_id, category_name FROM category ORDER BY category_name ASC";
	$r = @mysqli_query($dbc, $q);

	$num = mysqli_num_rows($r);
	if($num>0){
	 	echo '<p>Category:
	 	<select name="category">';
	 	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	 		$nm = $row['category_name'];
	 		$cid = $row['category_id'];
	 	echo "<option value=$cid>$nm</option>";
	 }
	 	echo '</select>';
	 	mysqli_free_result($r);
	}
?>

	<p>Title: <input type="text" name="title" size="20" maxlength="60" value="<?php if(isset($_POST['title'])) echo $_POST['title']; ?>"></p>
	<p>Cost: <input type="number" name="cost" min="1" step="0.01"></p>
	<p>Duration (in minutes): <input type="number" name="duration" min="15" max="120" step="15"></p>
	<p>Description: <textarea name='description' rows='2' columns='5' maxlength="500"></textarea></p>
	<p><input type="submit" name="submit" value="Create"></p>
</form>

<?php

	include('includes/footer.html');
?>