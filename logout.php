<?php require_once ("helper-functions/functions.php"); ?>
<?php
	// To log out we use a four step process
	// 1. Find the session using session_start();
	session_start();
	
	// 2. Clear out the session of all variables
	$_SESSION = array();
	
	// 3. Remove the cookie
	if (isset($_COOKIE[session_name()])){
		setcookie(session_name(), '', time() - 42000, '/');
	}
	
	// 4. Destroy the session
	session_destroy();
	
	redirect_to("index.php");

?>