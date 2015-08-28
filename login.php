<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php
	if (logged_in()){
		redirect_to("index.php");
	}
	include_once("helper-functions/form_functions.php");
		// START FORM PROCESSING
	if (isset($_POST['submit'])) { // Form has been submitted.
		$errors = array();

		// perform validations on the form data
		$required_fields = array('username', 'password');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));

		$fields_with_lengths = array('username' => 30, 'password' => 30);
		$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));

		$username = trim(mysql_prep($_POST['username']));
		$password = trim(mysql_prep($_POST['password']));
		$hashed_password = sha1($password);

		if ( empty($errors) ) {
				$query = "SELECT * ";
				$query .= "FROM users ";
				$query .= "WHERE username = '{$username}' ";
				$query .= "AND hashed_password = '{$hashed_password}' ";
				$query .= "LIMIT 1";
				$result_set = mysql_query($query);
		    confirm_query($result_set);
			if (mysql_num_rows($result_set) == 1) {
				$found_user = mysql_fetch_array($result_set);
				// **** Need to start session at beginning of page -- note the include session.php at top.
				// Store firstname, username and id -- more information now so we make fewer trips to database later.
				set_session_vars($found_user['id'], $found_user['username'], $found_user['admin']); // function is in session.php
				redirect_to("index.php");
			} else {
				$message = "That password or username is incorrect.";
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
		$username = "";
		$password = "";
		if (isset($_GET['logout']) && $_GET['logout']==1){
			$message .= "So, you've logged out, have you? You have. Hmmmmm. ";
		}
		
	}
?>

<!-- PAGE LAYOUT -->
<?php include("includes/header.php"); ?>
		
		<h2 class="page-title alt">Login</h2>
		<?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
		<?php if (!empty($errors)) { display_errors($errors); } ?>
		
		<form action="login.php" method="post" id="login-form" class="login" >
			<input type="text" name="username" class="username" maxlength="30" value="" />
<?php //xecho htmlentities($password); ?>
		<input type="password" name="password" class="password" maxlength="30" value="" />
	<input type="submit" class="submit" name="submit" value="Login" />

	</form>

<script type="text/javascript" src="js/scripts.js"></script>		
	<?php include("includes/footer.php")?>
	
	
