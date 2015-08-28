<?php
function check_required_fields($required_array) {
	$field_errors = array();
	foreach($required_array as $fieldname) {
		if (!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]) && !is_numeric($_POST[$fieldname]))) { 
			$field_errors[] = $fieldname; 
		}
	}
	return $field_errors;
}

function check_max_field_lengths($field_length_array) {
	$field_errors = array();
	foreach($field_length_array as $fieldname => $maxlength ) {
		if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) { $field_errors[] = $fieldname; }
	}
	return $field_errors;
}

function display_errors($error_array) {
	echo "<p class=\"errors\">";
	echo "Please review the following fields:<br />";
	foreach($error_array as $error) {
		echo " - " . $error . "<br />";
	}
	echo "</p>";
}

// retrun true if the username is unique -- we don't want duplicate usernames in the database
function username_is_unique($username){
	global $connection;
	$query = "SELECT * FROM users WHERE username = '{$username}' ";
	$users = mysql_query($query, $connection);
	// test if query failed using function in functions.php
	confirm_query($users);
	if (mysql_num_rows($users) >= 1){
		return false;
	} else {
		return true;
	}
}
?>