<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php
	if ($select_chapter = find_selected_chapter()){
		$story = get_story_by_chapter($select_chapter['id']);
		set_chapter_in_session($select_chapter['id']);
		$options = get_just_options($select_chapter['id']);
	} else {
		redirect_to('index.php');
	}	
?>
<?php include("includes/header.php"); ?>

<?php 
	if(!empty($_GET['deleted'])){ 
		if (intval($_GET['deleted']) == 1) { 
			 $message="You've deleted a chapter.";
    }; 
}; ?>
	
	
		<!-- TITLE -->
		<h2 class="page-title">
			 <a href="read_chapter.php?chapter=<?php print get_first_chapter($story['id']); ?>"><?php print $story['title']; ?></a>
		</h2>
		
		<?php if (!empty($message)) {echo "<div class=\"message\">" . $message . "</div>";} ?>
		
		<div id="chapter-block">
					<!-- CONTENT -->
			<div id="body-text" class="edit_area">
			
				<?php echo nl2br($select_chapter['content']); ?>
				
			</div> <!-- end chapter-text -->
			
		</div> <!-- end chapter-block -->
		<!-- EDIT CHAPTER -->

		<?php
			// OPTIONS or END OF CHAPTER
			if ($select_chapter['endpoint'] == 1){
					include("includes/chapter_end.php"); 
			} else {
					include("includes/chapter_options.php");
			}
		?>
		
		<?php if (logged_in()){ ?>	
				<div id="edit-chapter" class="edit-link">
					<a class="light-text" href=<?php echo "edit_chapter.php?chapter={$select_chapter['id']}&parent_id={$select_chapter['id']}";?>>Edit chapter</a>
				</div> <!-- end edit-chapter -->
		  <?php }; ?>

<?php include("includes/footer.php")?>
