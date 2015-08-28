<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php include_once("helper-functions/form_functions.php"); ?>
<?php 		
	// START FORM PROCESSING
	if (isset($_POST['submit'])) { // Form has been submitted.
		$errors = array();

		// perform validations on the form data
		$required_fields = array('username', 'password');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));

		$fields_with_lengths = array('username' => 30, 'password' => 30);
		$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));
		
		$firstname = trim(mysql_prep($_POST['firstname']));
		$lastname = trim(mysql_prep($_POST['lastname']));
		$username = trim(mysql_prep($_POST['username']));
		$password = trim(mysql_prep($_POST['password']));
		$hashed_password = sha1($password);
		
		// Make sure that this user name is unique
		if (!username_is_unique($username)){
			$errors[]="The user name you selected is already in use, so please try another.";
		}
	
		if ( empty($errors) ) {
			$query = "INSERT INTO users (
							firstname, lastname, username, hashed_password
						) VALUES (
							'{$firstname}', '{$lastname}', '{$username}', '{$hashed_password}'
						)";
			$result = mysql_query($query, $connection);
			if ($result) {
				// Get a reference to the user ID just created:
				$user_id = mysql_insert_id();
				set_session_vars($user_id, $username, $firstname);
				redirect_to("index.php?newuser=1");
			} else {
				$message = "The user could not be created.";
				$message .= "<br />" . mysql_error();
			}
		} else {
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
	} else { // Form has not been submitted.
		$firstname = "";
		$lastname = "";
		$username = "";
		$password = "";
	}
?>
<?php include("includes/header.php"); ?>
			<h2 class="page-title">Register Now. Here!</h2>
			<?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
			<?php if (!empty($errors)) { display_errors($errors); } ?>
			<form action="new_user.php" method="post">
			 	
					<label>First Name:</label>
					<input type="text" name="firstname" maxlength="30" value="<?php echo htmlentities($firstname); ?>" />
				
				
					<label>Last Name:</label>
					<input type="text" name="lastname" maxlength="30" value="<?php echo htmlentities($lastname); ?>" />
				
				
					<label>Username:</label>
					<input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" />
				
				
					<label>Password:</label>
					<input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />

		<input type="submit" name="submit" value="Login" />

		
<?php include("includes/footer.php")?>
	
	
