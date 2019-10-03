<?php

$page_title = 'Book Appointment';
include('includes/header.html');
echo '<h1>Book Appointment</h1>';
require('book_func.php');
require('includes/login_functions.inc.php');
require('../mysqli_connect.php');

if (!isset($_SESSION['user_id'])){
		echo '<p class="error">You must be logged in to schedule an appointment.</p>';
		} else if($_SESSION['user_type']!='client') {
			echo '<p class="error">Please use a client account to schedule an appointment.</p>';
		}else {
			
//all categories
echo '<div id="sep"></div>
<div id="categories">';
$cat = view_cat($dbc, '*', 'category', 'category_name', 'category_name', 'category_id');  //single category
echo '</div>';

echo '<div class="sep"></div>';

//all services
echo'<div id="services">';

for ($id=1; $id<$cat+1; $id++){
	//all services for category x
	echo "<p id='s$id' class='hidden'>";
	//indiviual services
	view_serv($dbc, $id);
	echo '</p>';
}
echo '</div>';

//all service info
echo '<div id="serviceinfo">';

//individual services
serv_info($dbc);

echo '</div>';

//select service
echo '<div id="servselect">';
get_serv($dbc, $id);
echo '</div>';
}

include('includes/footer.html');
?>