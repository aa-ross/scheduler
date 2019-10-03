<?php

$page_title = 'Book Appointment';
include('includes/header.html');
echo '<h1>Book Appointment</h1>';
require('book_func.php');
require('includes/login_functions.inc.php');
require('../mysqli_connect.php');

if ( (isset($_GET['serv'])) && (is_numeric($_GET['serv'])) ){
	$s = $_GET['serv'];
	if ( (isset($_GET['emp'])) && (is_numeric($_GET['emp'])) ){
		$e = $_GET['emp'];
		if( (isset($_GET['time'])) && (is_numeric($_GET['time'])) ){
			$t = $_GET['time'];
			if( (isset($_GET['date'])) && (is_numeric($_GET['date'])) ){
				$d = $_GET['date'];
			}
		}
	}
} else if ( (isset($_POST['serv'])) && (is_numeric($_POST['serv'])) ) {
	$s = $_POST['serv'];
	if ( (isset($_POST['emp'])) && (is_numeric($_POST['emp'])) ){
		$e = $_POST['emp'];
		if( (isset($_POST['time'])) && (is_numeric($_POST['time'])) ){
			$t = $_POST['time'];
			if( (isset($_POST['date'])) && (is_numeric($_POST['date'])) ){
				$d = $_POST['date'];
			}
		}
	}


} else {
	echo '<p class="error">This page has been accessed in error.</p>';
	redirect_user('book.php');
}

if (isset($_SESSION['user_id'])){
		$u = $_SESSION['user_id'];
		schedule($dbc, $s, $u, $e, $t, $d);
		//echo "<h3>Your appointment has been scheduled successfully.</h3>";
		} else {
			echo '<p class="error">You must be logged in to schedule an appointment.</p>';
		}

?>