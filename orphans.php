<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php include("includes/header.php"); ?>

<?php
	// *****************************************************************************
	// ORPHANS.PHP. Lists all orphan chapters in a story with links to edit.
	// The idea is to help a writer fill out the parts of their story that need work.
	// *****************************************************************************
?>
<h2 class="page-title">Edit Orphans</h2>
<div id="chapter-block">
  <p class="description">
	These chapters that are missing one or both options. The body of the chapter is written, but the options don't lead anywhere. You should edit chapters with lower level numbers first because they occur earlier in the story.
  </p>
</div>
<?php
	// Get story id from the URL parameters
 	$story_id = intval($_GET['story']);
	// Get a list of all orphan chapters in this story (chapters that aren't endings but that are missing child-chapters)
	if ($chapter_set = get_orphan_chapters($story_id)){
	//  Use $story_set data
	//	Loop through data to create list of stories
		while($chapter = mysql_fetch_array($chapter_set)){ ?>
			<h4 class="level light-text">Level: <span class="numeric "><?php print $chapter['level'];?></h4>
      <div class="body">
      	<a href="read_chapter.php?chapter=<?php print $chapter['id'];?>">
      	  <?php print $chapter['content'];?>
      	 </a>
      </div>
		<?php }
	} ?>

<?php include("includes/footer.php")?>
