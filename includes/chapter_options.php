<ul id="options">
	<?php
	  $unfinished=0;
		$newlevel=$select_chapter['level'] + 1;
		foreach ($options as $option) {
			if ($option['child_chapter'] != 0){ ?>
				<!-- If a child chapter exists, display a link to that chapter -->
				<li><a href="read_chapter.php?chapter=<?php print $option['child_chapter'];?>&option=<?php print $option['id']?>"><?php print $option['content']; ?> </a>
				</li>
			<?php } else { ?>
				<!--  Otherwise, display a link to create that chapter -->
					<li class="unfinished"> <?php print $option['content']; ?>
					<?php $unfinished++; // increment variable to track whether there are zero finished options // ?>
					<?php if (logged_in()): ?>
				    <span class="option-meta">/ 
						<a href="new_chapter.php?parent_id=<?php print $select_chapter['id'];?>&parent_option_id=<?php print $option['id'];?>&level=<?php print $select_chapter['level'];?> "> Create</a>  /  
						<a href="select_existing_chapter.php?chapter=<?php print $select_chapter['id'];?>&option=<?php print $option['id'];?>"> Use existing</a></span>
				  <?php endif; ?>
				  </li>
			<?php } ?>
		
		<?php } ?>
	</ul>
	
	<?php 
	  if ($unfinished==2):
		  // no options to pursue
		  include("includes/chapter_end.php");
		endif;
  ?>
