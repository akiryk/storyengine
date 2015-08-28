<?php require_once("helper-functions/connection.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("helper-functions/delete_functions.php")?>
<?php

	if(!empty($_GET['user'])){ 
		$id = $_GET['user'];
	}; 

	// if the id corresponds to an actual chapter, start process
	if ($user = get_user_by_id($id)) {
		// Call function in functions.php
		delete_user($id);
		// If successful, redirect to the parent chapter
		redirect_to("users.php?deleted=1");

	}	
		
?>

<?php mysql_close($connection); ?>