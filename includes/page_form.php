<!-- This form is inserted in the edit_chapter and new_chapter php files  -->
<div id="chapter-form">
	
<?php
	$chap_content = "";
	if (!empty($select_chapter['content'])){
		$chap_content = trim($select_chapter['content']);
	}
?>
	<!-- Display the text area for chapter content -->
<textarea name="content" id="edit-chapter-text" class="form-story-block form-input"><?php echo $chap_content; ?></textarea>
	
	<!-- Display checkbox for whether this is an endpoint or not -->
<div id="endpoint">
	<?php 
	  if (empty($select_chapter['endpoint'])){ 
		 	// box is checked  ?>
				<input class="endtarget" type="checkbox" name="endpoint" value="0" checked />
			<?php 
		} else {
			if($select_chapter['endpoint'] == 0){
				// box is checked  ?>
				<input class="endtarget" type="checkbox" name="endpoint" value="0" checked />
			<?php } else { ?>
				<?php  //box is not checked  ?>
				<input class="endtarget" type="checkbox" name="endpoint" value="1" />
			<?php }; ?>
		<?php }; ?>
	<label for="endpoint">Keep the story going?</label>
</div><!-- endpoint -->

	<!-- Display the options (js will hide these if chapter is an endpoint) -->
	<div id="edit-options">
		<div class="option">
			<label for="option1">Option 1</label>
				<input type="text" name="option0" size="40" maxlength="100" class="input"
					value="<?php if (!empty($options[0]['content'])) echo $options[0]['content'] ?>" id="option0">
		</div>
		<div class="option">	
			<label for="option2">Option 2 </label>
				<input type="text" name="option1" size="40" maxlength="100" class="input"
					value="<?php if (!empty($options[1]['content'])) echo $options[1]['content'] ?>" id="option1">
		</div>
	</div> <!-- options -->
</div> <!-- chapter-form -->