<?php #Script 10.1 - view_users.php #3

$page_title = 'Manage Users';
include('includes/header.html');
echo '<h1>Registered Users</h1>';

if(isset($_COOKIE['user_type']) && $_COOKIE['user_type'] == 'admin'){

require('../mysqli_connect.php');

$q = "SELECT last_name, first_name, user_type, user_id FROM users ORDER BY user_type ASC";
$r = @mysqli_query($dbc, $q);

$num = mysqli_num_rows($r);
if ($num>0){
	echo "<p>There are currently $num registered users.</p>\n";

	echo '<table width="60%">
	<thead>
	<tr>
	<th align="left"><strong>User Type</strong></th>
	<th align="left"><strong>Last Name</strong></th>
	<th align="left"><strong>First Name</strong></th>
	</tr>
	</thead>
	<tbody>';

	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		if ($row['user_type']=='emp'){
			$type = 'Employee';
		} else if ($row['user_type']=='admin'){
			$type = 'Admin';
		} else {
			$type = 'Client';
		}
		echo '<tr>
		<td align="left">' . $type . '</td>
		<td align="left">' . $row['last_name'] . '</td>
		<td align="left">' . $row['first_name'] . '</td>
		</tr>
		';
	}

	echo '</tbody></table>';
	mysqli_free_result($r);
}else{
	echo '<p class="error">There are currently no registered users.</p>';
}

mysqli_close($dbc);
} else {
	echo '<p class="error">Sorry, you do not have permission to view this page.</p>';
}

include('includes/footer.html');
?>