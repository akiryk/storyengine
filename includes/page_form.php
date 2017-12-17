<!-- This form is inserted in the edit_chapter and new_chapter php files  -->
<div id="chapter-form">

<?php
	$chap_content = "";
	$endpoint = 0;
	$options_display = "display: block";

	if (isset($select_chapter['endpoint']) && $select_chapter['endpoint'] == 1){
		$options_display = "display: none";
		$endpoint = 1;
	}

	if (!empty($select_chapter['content'])){
		$chap_content = trim($select_chapter['content']);
	}
?>
	<!-- Display the text area for chapter content -->
<textarea name="content" id="edit-chapter-text" class="form-story-block form-input"><?php echo $chap_content; ?></textarea>

	<!-- Display checkbox for whether this is an endpoint or not -->
<?php include("confirm_continue.php"); ?>

	<!-- Display the options (js will hide these if chapter is an endpoint) -->
	<div id="edit-options" style=<?php echo $options_display; ?> >
		<div class="option">
			<label for="option1">Option 1</label>
				<input type="text" name="option0" size="60" maxlength="60" class="input"
					value="<?php if (!empty($options[0]['content'])) echo $options[0]['content'] ?>" id="option0">
		</div>
		<div class="option">
			<label for="option2">Option 2 </label>
				<input type="text" name="option1" size="60" maxlength="60" class="input"
					value="<?php if (!empty($options[1]['content'])) echo $options[1]['content'] ?>" id="option1">
		</div>
	</div> <!-- options -->
</div> <!-- chapter-form -->