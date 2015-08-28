<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php confirm_logged_in(); // lives on session.php page ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php include_once("helper-functions/form_functions.php"); ?>
<?php 		

	/* $variables is an array of data passed through the url
	   $variables['parent_option_id'] is the id of the option that led to this chapter.
	   $variables['parent_id'] is the id of the parent of this chapter.
    */
  $poid="";
  $pid="";
  $lvl="";
  
  if (!empty($_GET['parent_option_id'])){
  	$poid = intval($_GET['parent_option_id']);
  };
  if (!empty($_GET['parent_id'])){
  	$pid = intval($_GET['parent_id']);
  };
  if (!empty($_GET['level'])){
  	$lvl = intval($_GET['level']);
  };
  
	$variables = array(
						"parent_option_id" => $poid, 
						"parent_id" => $pid,
						"level" => $lvl
						);
						
	$parent_option_id = $variables["parent_option_id"];
	// Add one to the level since this is a new chapter and it will be one level greater than it's parent


	// START FORM PROCESSING
	if (isset($_POST['submit'])) { // Form has been submitted.
		$errors = array();
		
		// check for endpoint
		if (isset ($_POST['endpoint'])){
			// false, so keep the story going.
			$endpoint = 0;
		} else {
			// true, so end the story (no options)
			$endpoint = 1;
		};
		// increment level
		$level = $variables["level"] + 1;
		
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
		
		// perform validations on the form data
		// $required_fields = array('content'); 
		// Content is always required
		// The options are only required if this isn't an endpoint
		if ($endpoint==0){
			// $required_fields[] = 'option1';
			// 		$required_fields[] = 'option2';
			$option0 = trim(mysql_prep($_POST['option0']));
			$option1 = trim(mysql_prep($_POST['option1']));
			// $fields_with_lengths = array('option1' => 70, 'option2' => 70);
			// 			$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));
		}
		
		$errors = array_merge($errors, check_required_fields($req_fields, $_POST));
		
		$content = trim(mysql_prep($_POST['content']));
		$story_id = $_SESSION['story_id'];
		// $endpoint = mysql_prep($_POST['endpoint']);
		if ($parent_option_id == 0) { 
			$beginning = 1; // true
		} else {
			$beginning = 0;
		}
		if ( empty($errors) ) {
			// CREATE THE CHAPTER
			$query = "INSERT INTO chapters (
							content, beginning, endpoint, level
						) VALUES (
							'{$content}', {$beginning}, {$endpoint}, {$level}
						)";
			// Confirm if the query is successful.
			$result = mysql_query($query, $connection);
			if (!$result){
				$errors[] = mysql_error();
			} else {
				// CHAPTER WAS CREATED!
				
				// Get a reference to the chapter ID just created:
				$chapter_id = mysql_insert_id();
					
				// Check if this is a first chapter or not
				// 0 means it's a first chapter, so link it to its parent story
				if ($parent_option_id == 0) { 
					$query = 	"UPDATE	stories
								SET		first_chapter	=	{$chapter_id}
								WHERE	id				=	{$story_id}";
					// Confirm if the query is successful.
					$result = mysql_query($query, $connection);
					if (!$result){
						$errors[] = mysql_error();
					}
				} else { // Otherwise, link it to it's parent option
					$query =   "UPDATE 		options
								SET 		child_chapter	= 	{$chapter_id}
								WHERE		id	=	{$parent_option_id}";					
					$result = mysql_query($query, $connection);
					if (!$result){
						$errors[] = mysql_error();
					}	
				}
				
				// Update the story_chapter join table
				$query 		=	"INSERT INTO story_chapter (
									story_id, chapter_id
								) VALUES (
									{$story_id}, {$chapter_id}
								)";	
												
				// Confirm if the query is successful.
				$result = mysql_query($query, $connection);
				if (!$result){
					$errors[] = mysql_error();
				}
				
					
				// Check whether we need options
				if ($endpoint == 0) { // not an endpoint, so insert options	 
					$options = array("first" => array(
										"content"=> $option0),
										"second" => array(
										"content"=> $option1)
									);
					include("includes/edit_options.php");
				}
				
				if ( empty($errors) ) {
					// SUCCESS, Chapter was created
					redirect_to("read_chapter.php?chapter={$chapter_id}");
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
		$options = array();
		// Nothing to set...
	}
?>
<?php include("includes/header.php"); ?>
<?php 

	if (!empty($_GET['story_id'])){
		$story = get_story_by_id($_GET['story_id']);
	} else if (!empty($_GET['parent_id'])){
	  $story = get_story_by_chapter($_GET['parent_id']);
	} else {
		$story = get_story_by_id($_SESSION['story_id']);
	}
	$first_chapter;
	if ($variables["parent_option_id"] == 0){
		$first_chapter=true;
		$message = "Your story has been saved! Get started...";
	} else {
		$first_chapter=false;
	}
?>
		<!-- TITLE -->
		<h2 class="page-title alt">
			 Create chapter for<span> <?php print $story['title']; ?></span> 
		</h2>

			<?php if (!empty($message)) {echo "<div class=\"message\">" . $message . "</div>";} ?>
			<?php if (!empty($errors)) { display_errors($errors); } ?>
			
			<?php if ($variables["parent_option_id"] == 0){ ?>
<!-- 				First chapter in story -->
				<form action=<?php echo "new_chapter.php?parent_option_id=0&level={$variables['level']}"; ?> method="post">
			<?php } else { ?>
				<form action=<?php echo "new_chapter.php?parent_option_id={$variables['parent_option_id']}&level={$variables['level']}"; ?> method="post">
			<?php } ?>
				<div class="new">
				<?php include("includes/page_form.php"); ?>
				</div>
				<input type="submit" name="submit" value="Save Chapter" class="submit"/> 
				<div class="update-options">  
					<a href=<?php echo "read_chapter.php?chapter={$variables["parent_id"]}";?> >Cancel</a>
				</div><!-- update-options -->
			</form>



	<script type="text/javascript" src="js/scripts.js"></script>
<?php include("includes/footer.php")?>
	
	
