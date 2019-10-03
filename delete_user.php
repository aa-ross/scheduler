<?php #Script - delete_user.php

require('includes/login_functions.inc.php');
require('other_func.php');
require('../mysqli_connect.php');

$page_title = 'Delete User';
include('includes/header.html');
echo '<h1>Delete User</h1>';

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ){
	$id = $_GET['id'];
	delete_user($dbc, $id);
	//echo ;
} else {
	echo "didn't pass";
}

echo "<p><a href='view_users.php'>Go Back</a></p>";


include('includes/footer.html');
?>