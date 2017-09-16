<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("helper-functions/delete_functions.php")?>
<?php confirm_logged_in(); // lives on session.php page ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php include_once("helper-functions/form_functions.php"); ?>
<?php

	/*	Get references to the selected story and to its options -->
		$select_story comes from find_selected_story() -->
		$options array comes from get_just_options($id) --> */
	if ($select_chapter = find_selected_chapter()){
		$story = get_story_by_chapter($select_chapter['id']);
		set_chapter_in_session($select_chapter['id']);
		$options = get_just_options($select_chapter['id']);
	} else {
		redirect_to('404.php');
	}

	if (!empty($_GET['parent_id'])){
		$parent_id = intval($_GET['parent_id']);
	}

	if (isset($_POST['submit'])){
		// Get endpoint radio button, since required fields vary depending on this number
		if (isset ($_POST['endpoint'])){
			$endpoint = 1;
		} else {
			$endpoint = 0;
		};
		// Do form validation
		// Form validation
		$errors = array(); // new array
		if (!$endpoint){
			$req_fields = array('content','option0','option1');
		} else {
			$req_fields = array('content');
		}
		foreach ($req_fields as $field){
			if (!isset($_POST[$field]) || empty($_POST[$field])){
				$errors[] = "You can't leave " . $field . " empty."; // add to the end of errors array
			}
		}

		if (empty($errors)){
			// No errors, so update database:
			// Get the variables set in the new_subject.php form and modify them using our
			// mysql_prep() function in functions.php to ensure SQL correctness.
			$get_chapter_id			= 	mysql_prep($_GET['chapter']);
			$chapter_id = intval($get_chapter_id); // make sure it's an integer
			$content	=	mysql_prep($_POST['content']);
			// Execute three queries. One on the chapter table; two on the options table

			// CHAPTER TABLE UPDATE
			$query =   "UPDATE 		chapters
						SET 		content		= 	'{$content}', endpoint = {$endpoint}
						WHERE		id	=	{$chapter_id}";
			$result = mysql_query($query, $connection);
			if (!$result){
				$errors[] = mysql_error();
			}

			// Update or create the options
			$options = array("first" => array(
								"id"=>intval(mysql_prep($options[0]["id"])),
								"content"=>trim(mysql_prep($_POST['option0']))),
							"second" => array(
								"id"=>intval(mysql_prep($options[1]["id"])),
								"content"=>trim(mysql_prep($_POST['option1'])))
							);

			include("includes/edit_options.php");

			// Test for success (make sure 1 row was changed)
			if (count($errors) < 1){
				// success -- redirect to new page
				redirect_to("read_chapter.php?chapter={$chapter_id}");
			} else {
				// failure
				echo count($errors);
				$message = "Gee, we seem to have a problem. Try again? <br />";

			}
		} else {
		// errors in form
			$message = "Looks like you have " . count($errors);
			if (count($errors) > 1){
				$message .= " errors in your form.";
			} else {
				$message .= " error in your form.";
			}
			echo "<br />";
			foreach ($errors as $error){
				echo "-".$error;
			}
		}
	}


	include("includes/header.php");
?>
<?php
	if (!empty($message)) {
		echo "ALERT: {$message} <br />";
	}
?>
	<!-- TITLE -->
		<h2 class="page-title">
			 <a href="read_chapter.php?chapter=<?php print get_first_chapter($story['id']); ?>"><?php print $story['title']; ?></a>
		</h2>

		<?php
			$option = get_option_by_child_chapter_id($parent_id);
			echo "<h3>" . $option['content'] . "</h3>"
		?>

		<!-- Show the form -->
		<form action="edit_chapter.php?chapter=<?php echo urlencode($select_chapter['id']); ?>" method="post" class="edit-form" accept-charset="utf-8">
			<?php include("includes/page_form.php"); ?>
			<div class="clearfix">
				<input type="submit" class="submit" name="submit" value="Update">
				<div class="update-options">
			    <a href="read_chapter.php?chapter=<?php echo $parent_id; ?>">Cancel</a> |
					<a href="delete_chapter.php?chapter=<?php echo urlencode($select_chapter['id']); ?>"; >
					Delete chapter</a>
				</div><!-- update-options -->
			</div>
		</form>
		<script type="text/javascript" src="js/scripts.js"></script>

<?php include("includes/footer.php"); ?>