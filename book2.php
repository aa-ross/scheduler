<?php

$page_title = 'Book Appointment';
include('includes/header.html');
echo '<h1>Book Appointment</h1>';
require('book_func.php');
require('includes/login_functions.inc.php');
require('../mysqli_connect.php');

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ){
	$id = $_GET['id'];
} else if ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
	$id = $_POST['id'];
} else {
	echo '<p class="error">This page has been accessed in error.</p>';
	redirect_user('book.php');
}

//view dates
echo '<div id="dates">';
view_dates($dbc, 'October', '2019');
echo '</div>';

echo '<div id="timeslots">';
select_day($dbc, 'October', $id);
echo '</div>';

include('includes/footer.html');
?>