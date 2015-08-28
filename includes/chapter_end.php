<div id="story-end">
	<p class="unfinished">Sorry, you've reached an end point.
	<?php if(!empty($_SESSION['story_id'])):
		  $first_chapter = get_first_chapter($_SESSION['story_id']); ?>
		  <a href="read_chapter.php?chapter=<?php print $first_chapter ?>">Start again?</a>
	  <?php endif; ?>
	</p>
</div> <!-- story end -->