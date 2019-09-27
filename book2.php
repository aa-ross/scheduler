<?php

$page_title = 'Book Appointment';
include('includes/header.html');
echo '<h1>Book Appointment</h1>';
require('book_func.php');
require('../mysqli_connect.php');

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ){
	$id = $_GET['id'];
} else if ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
	$id = $_POST['id'];
} else {
	echo '<p class="error">This page has been accessed in error.</p>';
}

//view dates
echo '<div id="dates">';
view_dates($dbc, 'May', '2018');
echo '</div>';

echo '<div id="timeslots">';
select_day($dbc, 'May', $id);
echo '</div>';

include('includes/footer.html');
?>