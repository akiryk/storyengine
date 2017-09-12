<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("classes/pager.php"); ?>
<?php include("includes/header.php"); ?>

<?php
	// *****************************************************************************
	// INDEX.PHP. This page lists all stories
	// *****************************************************************************
?>
  <?php
			if(!empty($_GET['new_story'])){
				if ($_GET['new_story'] == 1){
					echo "Your story has just been saved!";
				}
			}
  ?>
  <h2 class='page-title'>All Stories</h2>
	<?php
		global $connection;
		$pager = new Pager($connection, "all-stories");
		$result = $pager->getResult();
		?>

		<ul class="story-list">
			<?php // while there are rows to be fetched...
			while ($story = mysql_fetch_assoc($result)) {
				$total = get_story_children_n($story['id']);
		   	// echo data ?>
		   	<li>
		   		<a href="start_new_story.php?story=<?php echo $story['id'];?>"><?php print $story['title'];?></a>
						<span class="secondary"><?php print $total;?> chapters</span>
						<?php if (is_admin()): ?>
							<div class="delete mini-button"><a href="delete_story.php?story=<?php echo $story['id'];?>">Delete Story</a></div>
						<?php endif; ?>
				</li>
			<?php } // end while ?>
		</ul>
		<a href="new_story.php" class="button new-story">Create a story</a>

		<div class="pagination">
			<?php // $pager->drawPaginationLinks(); ?>
		</div>


<script type="text/javascript" src="js/story-lists.js"></script>
<?php include("includes/footer.php"); ?>
</div> <!-- #wrapper -->
