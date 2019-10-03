<?php

	$page_title = 'Create Service Category';
	include('includes/header.html');

	require('../mysqli_connect.php');
	require('includes/login_functions.inc.php');

if ($_SERVER['REQUEST_METHOD']=='POST'){

	if(empty($_POST['category_name'])){
		echo '<p class="error">You must enter a category name.';
	} else {
		$cn = trim($_POST['category_name']);

		$q = "INSERT INTO category (category_name) VALUES ('$cn')";
		$r = @mysqli_query($dbc, $q);

		if($r){
		echo "<meta http-equiv='refresh' content='0'>";
	}

	}
}

?>

<h1>Create Category</h1>

<h2>Current Categories</h2>

<?php
	$q = "SELECT category_name FROM category ORDER BY category_name ASC";
	$r = @mysqli_query($dbc, $q);
		if ($r){
			$num = mysqli_num_rows($r);
			if($num==0){
				echo "None";
			} else {
				while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	 				$s = $row['category_name'];
	 				echo "<span class='button'>$s</span>";
				}
			}
		}
		echo "<p></p><p></p><p></p><p></p><p></p><p></p>";
?>

<form action="create_category.php" method="post">
	<p>Category Name: <input type="text" name="category_name" size="20" maxlength="60" value="<?php if(isset($_POST['category_name'])) echo $_POST['category_name']; ?>"></p>
	<p><input type="submit" name="submit" value="Create"></p>
</form>

<?php

	include('includes/footer.html');
?>