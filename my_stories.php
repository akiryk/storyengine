<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php confirm_logged_in(); // lives on session.php page ?>
<?php require_once("classes/pager.php"); ?>
<?php include("includes/header.php"); ?>
<?php
	// *****************************************************************************
	// MYSTORIES.PHP. This page lists all stories that the logged in user has created
	// and provides a link to create a new story.
	// *****************************************************************************
?>
		<div class="alert">
		<?php
			if (isset($_GET['new_story']) && is_numeric($_GET['new_story'])){
     		if ($_GET['new_story'] == 1){
					echo "Your story has just been saved!";
				}
			}

		?>
		</div> <!-- #alert -->
		<h2 class='page-title'>Your Stories</h2>
		<?php
		  $message ="";
			global $connection;
			$pager = new Pager($connection, "user-stories");
			$result = $pager->getResult();
		?>

				<ul class="story-list">
				<?php
				//	Loop through stories to create a list
				while($story = mysql_fetch_array($result)){
					$chapter_id = get_first_chapter($story['id']);	 // Get the specific chapter
					// Find how many chapters are in a story. This is just for fun -- to show how long or full a given story is.
					$total = get_story_children_n($story['id']); ?>
					<li class="my-story">
					  <a href="start_new_story.php?story=<?php echo $story['id']?>&chapter=<?php echo $chapter_id?>"
										class="title"><?php print $story['title']; ?></a> <span class='secondary'><?php print $total;?> chapters</span>
						<div class="story-options">
						  <div class="menu-edit-link mini-button">
						    <a href="orphans.php?story=<?php echo $story['id']; ?>">See orphans</a></div>
							<?php if (is_admin()): ?>
								<div class="delete mini-button"><a href="delete_story.php?story=<?php echo $story['id'];?>">Delete Story</a></div>
							<?php endif; ?>
						</div>
					</li>
			<?php } ?>
				</ul>
		<div class="clearfix"><a class="button" href="new_story.php">Create a new story</a></div>
		<?php
			// $pager->drawPaginationLinks();
		?>
<script type="text/javascript" src="js/story-lists.js"></script>
<?php include("includes/footer.php"); ?>
</div> <!-- #wrapper -->
