<?php

// *************************************************
// **********    DELETING   ************************
// *************************************************
function delete_story($id){
	global $connection;
	// $id is the id of a story
	// First, get all chapters that are in this story
	$chapter_set = get_chapters_in_story($id);
	// Loop through chapters and delete them and all associated references
	while($chapter_id = mysql_fetch_array($chapter_set)){
		delete_chapter($chapter_id[0]);
	}
	// Finally, delete the story
	$query = "DELETE FROM stories WHERE id = {$id} LIMIT 1";
	$result_set = mysql_query($query, $connection);
	 confirm_query($result_set);
}

function delete_chapter($id){
	global $connection;
	// First, delete the options that are connected to the chapter
	delete_options($id);
	// Then delete rows from chapter_option that include this chapter
	delete_from_chapter_option($id);
	// Then nullify the child_chapter row in options table
	nullify_child_chapter($id);
	// Delete rows in story_chapter that include this chapter
	delete_from_story_chapter($id);
	// Then delete chapter
	$query = "DELETE FROM chapters WHERE id = {$id} LIMIT 1";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
}

function delete_options($id){
	global $connection;
	// Delete options by chapter id
	$query = "DELETE options.* FROM options ";
	$query .= "LEFT JOIN chapter_option ";
	$query .= "ON options.id = chapter_option.option_id ";
	$query .= "WHERE chapter_option.chapter_id= " . $id;
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
}

function delete_from_chapter_option($id){
	global $connection;
	$query = "DELETE FROM chapter_option WHERE chapter_id = {$id}";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
}

function nullify_child_chapter($id){
	global $connection;
	// set to null any options that have the chapter as their child
	$query =   "UPDATE 		options
				SET 		child_chapter	= 	NULL
				WHERE		child_chapter	=	{$id}";					
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
}

function delete_from_story_chapter($id){
	global $connection;
	// delete rows from the story_chapter join table that include this chapter
	$query	= "DELETE FROM story_chapter WHERE chapter_id = {$id}";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
}

function delete_from_stories_users($id){
	global $connection;
	$query = "DELETE FROM stories_users WHERE story_id = {$id}";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
}

function delete_user($id){
	global $connection;
	delete_from_story_user($id);
	// Finally, delete the story
	$query = "DELETE FROM users WHERE id = {$id} LIMIT 1";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
}
function delete_from_story_user($id){
	global $connection;
	$query = "DELETE FROM stories_users WHERE user_id = {$id}";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
}

?>