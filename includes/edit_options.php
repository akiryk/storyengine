<?php 
// Both new_chapter.php and edit_chapter.php call the same functions for adding and editing options
			
// Only UPDATE the first option if it already exists. Otherwise, do a CREATE.

foreach ($options as $option){
	$content = $option['content'];
	if (!isset($option['id']) || empty($option['id'])){
		$query 	= "INSERT INTO options (content)
					VALUES (	'{$content}' )";
		$result = mysql_query($query, $connection);
		if (!$result){
			$errors[] = mysql_error();
		} else {
			// SUCCESS
			// Get a reference to the option_id just created
			$option_id = mysql_insert_id();
			// Now create the option link in join table
			$query 	= "INSERT INTO chapter_option (chapter_id, option_id)
						VALUES (	{$chapter_id}, {$option_id} )";
			$result = mysql_query($query, $connection);
			if (!$result){
				$errors[] = "problem with first insert. Story id: " . $chapter_id . " and option id: " . $option1ID . mysql_error();
			}
		}
		
	} else {
		// There is an option 1, so UPDATE
		// OPTIONS TABLE UPDATE
		$option_id = $option['id'];
		if ($endpoint == 1){
			// End the story here, so remove the options.
			// (Keep child chapters since we may still want to use them);
			delete_options($chapter_id);
			delete_from_chapter_option($chapter_id);
		} else {
			// It's not the end, so keep options
			$query =   "UPDATE 		options
						SET 		content		= 	'{$content}'
						WHERE		id	=	{$option_id}";					
			$result = mysql_query($query, $connection);
			if (!$result){
				$errors[] = mysql_error();
			}
		}
	}
}
		
?>