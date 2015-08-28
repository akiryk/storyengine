<?php require_once("helper-functions/connection.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("helper-functions/delete_functions.php")?>
<?php
	if (intval($_GET['story']) == 0) {
		redirect_to("all_stories.php");
	}
	
	// get the id of the story
	$id = mysql_prep($_GET['story']);
		
	delete_story($id);
	// *** Loop through each child chapter and delete it
	redirect_to("my_stories.php");

?>

<?php mysql_close($connection);