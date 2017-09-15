<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php require_once("classes/pager.php"); ?>
<?php include("includes/header.php"); ?>

<?php
	// *****************************************************************************
	// Select existing chapter.php. Lists all chapters in a story with links to use as an option.
	// The idea is to help a writer reuse chapters rather than create new ones.
	// *****************************************************************************
?>

<h2 class="page-title">Use Existing</h2>
<div id="chapter-block">
  <p class="description">
		Select one of the existing chapters instead of creating a new one. The effect will be to have loops within your story, where a reader might end up back in a chapter they've already read...
  </p>
</div>
<?php
	if (isset($_GET['chapter']) && is_numeric($_GET['chapter'])):
 		$chapterID = intval($_GET['chapter']);
 		$story = get_story_by_chapter($chapterID);
 		$chapter_set = get_chapters_in_story($story['id']);
 	endif;

 	if (isset($_GET['option']) && is_numeric($_GET['option'])):
 		$option_id = intval($_GET['option']);
 	endif;

	// global $connection;
	// $pager = new Pager($connection, "all-chapters");
	// $result = $pager->getResult();

//  Use $story_set data
//	Loop through data to create list of stories
  if (isset($chapter_set)):
		while($chapter = mysql_fetch_array($chapter_set)){ ?>
			<div class="body">
			<?php print $chapter['content']; ?>
			<a href="add_existing.php?option=<?php print $option_id;?>&chapter=<?php print $chapter['id'];?>">Use this</a>

		</div>
		<?php } ?>
	<?php endif; ?>
<?php include("includes/footer.php")?>
