<?php #Script - delete_service.php

require('includes/login_functions.inc.php');
require('other_func.php');
require('../mysqli_connect.php');

$page_title = 'Delete Service';
include('includes/header.html');
echo '<h1>Delete Service</h1>';

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ){
	$id = $_GET['id'];
	delete_service($dbc, $id);
	//echo ;
} else {
	echo "didn't pass";
}

echo "<p><a href='manage_services.php'>Go Back</a></p>";


include('includes/footer.html');
?>