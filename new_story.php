<?php require_once("helper-functions/connection.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("helper-functions/session.php"); ?>
<?php confirm_logged_in(); // lives on session.php page ?>
<?php include_once("helper-functions/form_functions.php"); ?>
<?php
	// START FORM PROCESSING
	if (isset($_POST['submit'])) { // Form has been submitted.
		$errors = array();

		// perform validations on the form data
		$required_fields = array('title');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));

		$fields_with_lengths = array('title' => 200);
		$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));

		$title = trim(mysql_prep($_POST['title']));

		if ( empty($errors) ) {
			// CREATE THE STORY
			$query = "INSERT INTO stories (
							title
						) VALUES (
							'{$title}'
						)";
			// Confirm if the query is successful.
			$result = mysql_query($query, $connection);

			if (!$result){
				$errors[] = mysql_error();
			} else {
				// STORY WAS CREATED!
				// UPDATE THE JOIN TABLE:
				// First, get a reference to the story ID just created:
				$story_id = mysql_insert_id();
				// update the join table
				$query 		=	"INSERT INTO stories_users (
									story_id, user_id
								) VALUES (
									{$story_id}, {$_SESSION['user_id']}
								)";
				// Confirm if the query is successful.
				$result = mysql_query($query, $connection);
				if (!$result){
					$errors[] = mysql_error();
				}

				if ( empty($errors) ) {
					// SUCCESS!
					// create a session variable to keep track of the current story
					$_SESSION['story_id'] = $story_id;
					$_SESSION['story_title'] = $title;
					// Send user to a new chapter page and flag it with parent_option_id=0.
					// This will let us know that the chapter should be flagged as a first chapter (that is, beginning=1 in db)
					redirect_to("new_chapter.php?success=1&parent_option_id=0&level=0&story_id={$story_id}");
				} else {
					$message = "Unfortunately, the story could not be created at this time. Try again?";
				}
			}

		} else {
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
	} else {
		// Form has not been submitted, so set form variable(s) to empty strings.
		// That is, the user will be seeing this page for first time, so we show them the empty form
		$title = "";
	}
?>
<?php include("includes/header.php"); ?>
<div id="page">

	<div id="page-content">

			<h2 class="page-title">Create a new story</h1>
			<?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
			<?php if (!empty($errors)) { display_errors($errors); } ?>
			<form id="new-story" action="new_story.php" method="post">
				<label for="title">Your title</label>
				<input type="text" class="form-title" id="new-story" name="title" maxlength="200" size="35" value="<?php echo htmlentities($title); ?>" />
				<input type="submit" name="submit" value="Create Story" class="submit"/>


	</div> <!-- end page-content -->
</div> <!-- end page -->
<?php include("includes/footer.php")?>


