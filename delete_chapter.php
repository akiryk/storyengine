<?php require_once("helper-functions/connection.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("helper-functions/delete_functions.php")?>
<?php
	if (intval($_GET['chapter']) == 0) {
		redirect_to("all_stories.php");
	}
	
	// get the id of the chapter
	$id = mysql_prep($_GET['chapter']);

	// if the id corresponds to an actual chapter, start process
	if ($chapter = get_chapter_by_id($id)) {
		 
		// Capture the parent chapter before deletion (otherwise, you won't be able to find it)
		$parent = get_parent_of_chapter($chapter['id']);
		
		$errors = array();

		// Call function in functions.php
		delete_chapter($id);
		// If successful, redirect to the parent chapter
		redirect_to("read_chapter.php?chapter={$parent['chapter_id']}&deleted=1");

	}	
		
?>

<?php mysql_close($connection); ?>