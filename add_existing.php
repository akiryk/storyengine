<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php
	// *****************************************************************************
	// ADD EXISTING.
	// This adds a chapter to a particular option. It is called by 'select_existing_chapter.php'
	// when a writer wants to reuse a chapter rather than write a new one.
	// *****************************************************************************
?>
<?php
	$option_id = intval($_GET['option']);
	$chapter_id =intval($_GET['chapter']);

	if (link_story_to_option($chapter_id,$option_id)){
		if ($id = get_latest_chapter()){ // gets parent chapter from the session
			redirect_to("read_chapter.php?chapter={$id}");
		} else {
			redirect_to("read_chapter.php?chapter={$chapter_id}");
		}
	} else {
		echo "doesn't link story.";	
	};
?>
<?php include("includes/footer.php")?>
