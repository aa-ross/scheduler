<?php

function edit_user ($dbc, $id, $fn, $ln, $e, $p){
	$q = "SELECT user_id FROM users WHERE email='$e' AND user_id != '$id'";
	$r = @mysqli_query($dbc, $q);

		if (mysqli_num_rows($r) == 0){
			$q = "UPDATE users SET first_name='$fn', last_name='$ln', email='$e', phone='$p'
			WHERE user_id='$id' LIMIT 1";
			$r = @mysqli_query($dbc, $q);
			
			if (mysqli_affected_rows($dbc) == 1){
				return true;
			}
		}
}

function edit_emp ($dbc, $id, $y, $f){
	$q2 = "UPDATE employee SET years='$y', favorite='$f' WHERE user_id='$id' LIMIT 1";
	$r2 = @mysqli_query($dbc, $q);
	if($r2){
		return true;
	}
}

function edit_client ($dbc, $id, $pr, $st, $sc){
	echo "$id // $pr // $st // $sc";
	$q2 = "UPDATE client SET pref='$pr', skintype='$st', skinconcerns='$sc' WHERE user_id='$id' LIMIT 1";
	$r2 = @mysqli_query($dbc, $q);
	if($r2){
		return true;
	}
}

?>