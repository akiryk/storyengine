<?php

// Make sure everything that's submitted to database is in acceptable format
function mysql_prep($value){
	// old form of handling quotes
	$magic_quotes_active = get_magic_quotes_gpc();
	// id PHP version based on functionality
	$new_enough_php = function_exists("mysql_real_escape_string"); // i.e. PHP >= v4.3

	// Modify $value based on version & configuration
	if ($new_enough_php){
		if ($magic_quotes_active){
			// strip the slashes that would be inserted.
			$value = stripslashes($value);
		}
		// It's a new enough version, so we can use this function
		$value = mysql_real_escape_string($value);

	} else {  // It's an older version of php

		if (!$magic_quotes_active){
			// strip the slashes that would be inserted.
			$value = addslashes($value);
		}
	}
	return $value;
}

// Test if mysql query failed
function confirm_query($result_set) {
	if (!$result_set) {
		die("Query failed: " . mysql_error());
	}
}

// Redirect function
function redirect_to($location = NULL){
	if ($location != NULL){
		header("Location: {$location}");
	}
}

function get_user_name($id){
	global $connection;
	$query = "SELECT * FROM users WHERE users.id = {$id} LIMIT 1";
	$result = mysql_query($query, $connection);
	confirm_query($result);
	if ($user = mysql_fetch_array($result)){ // if $subject is true
		return $user['firstname'];
	} else {
		return NULL;
	}
}


// Find all stories by all users
function get_all_stories() {
	global $connection;
	$query = "SELECT stories.title, stories.id FROM stories";
	$story_set = mysql_query($query, $connection);
	confirm_query($story_set);
	return $story_set;
	// if result set has nothing in it, fetch array will return false

	// find out how many rows are in the table

$r = mysql_fetch_row($story_set);
$numrows = $r[0];
}

// How many stories are there in total
function get_total_stories(){
	$story_set = get_all_stories();
	return  mysql_num_rows($story_set);
}

// Returns a list of all users
function get_users(){
	global $connection;
	$query = "SELECT * FROM users";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	return $result_set;

}

function get_story_title(){
	$story = get_story_by_id($_SESSION['story_id']);
	return $story['title'];
}

function get_parent_option_text($id){
	$option = get_option_by_id($id);
	return $option['content'];
}

// Find all of the logged in user's stories
function user_has_stories($user_id){
	// When we say "global $some_var", we aren't declaring the $some_var variable.
	// Instead, we're simply telling php that it should look for the $some_var that exists globally
	// as opposed to declaring a new local variable of that name.
	global $connection;
	$query = 	"SELECT stories.title, stories.id FROM stories ";
	$query .=	"LEFT JOIN stories_users su ";
	$query .=	"ON stories.id = su.story_id ";
	$query .=	"LEFT JOIN users u ";
	$query .=	"ON u.id = su.user_id ";
	$query .=	"WHERE u.id = {$user_id}";
	$story_set = mysql_query($query, $connection);
	confirm_query($story_set);

	// if result set has nothing in it, fetch array will return false
	if (mysql_num_rows($story_set) > 0){ // if $subject is true
		return $story_set;
	} else {
		return NULL;
	}
}

// Returns ID of story based on GET variable
function find_selected_story(){
	// create global variables so they are accessible outside of this function.
	// Set the selected subject & page.

	if (isset($_GET['story'])){
		$id = (int)$_GET['story'];
		return get_story_by_id($id);
	} else {
		return get_beginning_of_story();
	}

}

// Returns ID of chapter based on GET variable
function find_selected_chapter(){
	// create global variables so they are accessible outside of this function.
	// Set the selected subject & page.

	if (isset($_GET['chapter'])){
		$id = (int)$_GET['chapter'];
		return get_chapter_by_id($id);
	} else if (isset($_GET['story'])){
		$id = (int)$_GET['story'];
		return get_first_chapter_of_story($id);
	} else {
		redirect_to('404.php');
	}

}

/**
 * Get first chapter of a story based on that story's id
 * @param {Number} $id, the story id
 * @return {Array} row of data from the story's first chapter
 */
function get_first_chapter_of_story($id){
	$story = get_story_by_id($id);
	if (!$story['first_chapter']) {
		redirect_to("new_chapter.php?parent_option_id=0&level=0&story_id={$id}");
	}
	return get_chapter_by_id($story['first_chapter']);
}

// Return the total number of chapters in a story -- not the chapters themselves
function get_story_children_n($id){
	global $connection;
	// SELECT DISTINCT removes duplicates from query
	$query = "SELECT DISTINCT chapters.id
	FROM chapters LEFT JOIN story_chapter ON chapters.id = story_chapter.chapter_id
	LEFT JOIN stories
	ON story_chapter.story_id = stories.id
	WHERE stories.id=$id";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	$total = mysql_num_rows($result_set);
	return $total;
}

// Return two variables corresponding to the two options for a given chapter
function get_just_options($chapter_id){
	$i = 0;
	$options = array();
	if ($children = get_options_by_chapter_id($chapter_id)){
		while($child = mysql_fetch_array($children)){
			$options[$i] = $child;
			$i++;
		}
		return $options;
	}
}

function get_story_by_id($id){
	global $connection;
	$query = "SELECT * ";
	$query .= "FROM stories ";
	$query .= "WHERE id= " . $id . " "; // add space between id and LIMIT
	$query .= "LIMIT 1";
	// limit 1 to help us remember that we only want one and for extra certainty that we only get one.
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	// REMEMBER:
	// if result set has nothing in it, fetch array will return false
	if ($story = mysql_fetch_array($result_set)){ // if $subject is true
		return $story;
	} else {
		return NULL;
	}
}

function get_chapter_by_id($id){
	global $connection;
	$query = "SELECT * ";
	$query .= "FROM chapters ";
	$query .= "WHERE id= " . $id . " "; // add space between id and LIMIT
	$query .= "LIMIT 1";
	// limit 1 to help us remember that we only want one and for extra certainty that we only get one.
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	// REMEMBER:
	// if result set has nothing in it, fetch array will return false
	if ($chapter = mysql_fetch_array($result_set)){ // if $subject is true
		return $chapter;
	} else {
		return NULL;
	}
}

function get_user_by_id($id){
	global $connection;
	$query = "SELECT * ";
	$query .= "FROM users ";
	$query .= "WHERE id= " . $id . " "; // add space between id and LIMIT
	$query .= "LIMIT 1";
	// limit 1 to help us remember that we only want one and for extra certainty that we only get one.
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	// if result set has nothing in it, fetch array will return false
	if ($user = mysql_fetch_array($result_set)){ // if $subject is true
		return $user;
	} else {
		return NULL;
	}
}

function get_option_by_id($id){
	global $connection;
	$query = "SELECT * FROM options WHERE id= " . $id . " LIMIT 1";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	// REMEMBER:
	// if result set has nothing in it, fetch array will return false
	if ($option = mysql_fetch_array($result_set)){ // if $subject is true
		return $option;
	} else {
		return NULL;
	}
}

/**
 * Get the option that leads to a chapter
 * e.g. You are on and island and have two options, Go North or Go South.
 * Say you choose to create or edit the chapter for Go North.
 * "Go North" is therefore the parent option.
 * @param {Number} $id, the id of a chapter
 * @return {Result} An record from the options table
 */
function get_option_by_child_chapter_id($id) {
	global $connection;
	$query = "SELECT * FROM options WHERE child_chapter = " . $id;
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	if ($option = mysql_fetch_array($result_set)){
		return $option;
	} else {
		return NULL;
	}
}

// Get the options that are connected to a specified chapter
function get_options_by_chapter_id($id) {
	global $connection;
	$query = "SELECT options.content, options.id, options.child_chapter ";
	$query .= "FROM options ";
	$query .= "LEFT JOIN chapter_option ";
	$query .= "ON options.id = chapter_option.option_id ";
	$query .= "LEFT JOIN chapters ";
	$query .= "ON chapter_option.chapter_id = chapters.id ";
	$query .= "WHERE chapters.id= " . $id;
	$query .= " ORDER BY options.id ASC ";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	if (mysql_num_rows($result_set) > 0){ // if there are rows in $result_set
		return $result_set;
	} else {
		return false;
	}
}

// Get the story id based on a chapter id
function get_story_by_chapter($id){
  global $connection;
	$query = "SELECT stories.id, stories.title, stories.first_chapter FROM stories LEFT JOIN story_chapter ON stories.id = story_chapter.story_id LEFT JOIN chapters ON story_chapter.chapter_id = chapters.id WHERE chapters.id = " . $id;
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	// if result set has nothing in it, fetch array will return false
	if ($story = mysql_fetch_array($result_set)){ // if $subject is true
		return $story;
	} else {
		return NULL;
	}
}

// Get the first chapter of a story and return its ID.
function get_first_chapter($id){
	$story = get_story_by_id($id);
	return $story['first_chapter']; // returns id of the chapter
}

// Get a list of all chapters in a given story
function get_chapters_in_story($id){
	$story = get_story_by_id($id);
	global $connection;
	$query = "SELECT chapters.id, chapters.content ";
	$query .= "FROM chapters ";
	$query .= "LEFT JOIN story_chapter ";
	$query .= "ON chapters.id = story_chapter.chapter_id ";
	$query .= "LEFT JOIN stories ";
	$query .= "ON story_chapter.story_id = stories.id ";
	$query .= "WHERE stories.id= " . $story['id'];
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	return $result_set;
}

// Link a new option to an existing story
// This is an alternative to creating a new story for an option
function link_story_to_option($story_id,$option_id){
	$query =   "UPDATE 		options
				SET 		child_chapter	= 	{$story_id}
				WHERE		id	=	{$option_id}";
	$result = mysql_query($query);
	confirm_query($result);
	return true;
}

// After deleting a chapter, send the user to the parent of that chapter
function get_parent_of_chapter($id){
	$query = "SELECT chapter_option.chapter_id FROM chapter_option
				LEFT JOIN options ON chapter_option.option_id =  options.id
				WHERE options.child_chapter = {$id}";
	$result_set = mysql_query($query);
	confirm_query($result_set);
	if ($result = mysql_fetch_array($result_set)){
		return $result;
	} else {
		return NULL;
	}
}

/**
 *  Get a list of all chapters in a story that are missing one or both child_chapters
 * @param {Number} id, the id of the story
 */
function get_orphan_chapters($id){
	$story = get_story_by_id($id);
	global $connection;
	// SELECT DISTINCT removes duplicates from query
	$query = "SELECT DISTINCT chapters.content, chapters.id, chapters.level
	FROM chapters LEFT JOIN story_chapter ON chapters.id = story_chapter.chapter_id
	LEFT JOIN stories
	ON story_chapter.story_id = stories.id
	LEFT JOIN chapter_option ON chapters.id = chapter_option.chapter_id
	LEFT JOIN options ON chapter_option.option_id = options.id
	WHERE stories.id=$id
	AND
	options.child_chapter = 0
	AND
	chapters.endpoint = 0
	ORDER BY chapters.level ASC";
	$result_set = mysql_query($query, $connection);
	confirm_query($result_set);
	return $result_set;
}


// PAGINATION: start by passing the function a result set
function paginate_stories(){
  global $connection;
  $table = "stories";
  $rows = "title, id";
	$sql = "SELECT COUNT(*) FROM " . $table;
	$result = mysql_query($sql, $connection) or trigger_error("SQL", E_USER_ERROR);
	$r = mysql_fetch_row($result);
	$numrows = $r[0];

	// number of rows to show per page
	$rowsperpage =5;
	// find out total pages
	$totalpages = ceil($numrows / $rowsperpage);

	// get the current page or set a default
	if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
	   // cast var as int
	   $currentpage = (int) $_GET['currentpage'];
	} else {
	   // default page num
	   $currentpage = 1;
	} // end if

	// if current page is greater than total pages...
	if ($currentpage > $totalpages) {
	   // set current page to last page
	   $currentpage = $totalpages;
	} // end if
	// if current page is less than first page...
	if ($currentpage < 1) {
	   // set current page to first page
	   $currentpage = 1;
	} // end if

	// the offset of the list, based on current page
	$offset = ($currentpage - 1) * $rowsperpage;

	// get the info from the db
	$sql = "SELECT " . $rows . " FROM " . $table . " LIMIT $offset, $rowsperpage";
	$result = mysql_query($sql, $connection) or trigger_error("SQL", E_USER_ERROR);

	// create the nice html to render
	$output = "<ul class=\"story-list\">";
	// while there are rows to be fetched...
	while ($story = mysql_fetch_assoc($result)) {
		$total = get_story_children_n($story['id']);
	   // echo data
	   $output .= "<li><a href=\"start_new_story.php?story={$story['id']}\">{$story['title']}</a>
					<span class='secondary'>{$total} chapters</span></li>";
	} // end while
	$output .="</ul>"; // close the list
	echo $output;
	echo '<a href="new_story.php" class="button new-story">Create a story</a>';

	/******  build the pagination links ******/
	// range of num links to show
	$range = 3;

	// if not on page 1, don't show back links
	if ($currentpage > 1) {
	   // show << link to go back to page 1
	   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
	   // get previous page num
	   $prevpage = $currentpage - 1;
	   // show < link to go back to 1 page
	   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";
	} // end if

	// loop to show links to range of pages around current page
	for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
	   // if it's a valid page number...
	   if (($x > 0) && ($x <= $totalpages) && ($totalpages > 1)) {
	      // if we're on current page...
	      if ($x == $currentpage) {
	         // 'highlight' it but don't make a link
	         echo " [<b>$x</b>] ";
	      // if not current page...
	      } else {
	         // make it a link
	         echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
	      } // end else
	   } // end if
	} // end for

	// if not on last page, show forward and last page links
	if ($currentpage != $totalpages) {
	   // get next page
	   $nextpage = $currentpage + 1;
	    // echo forward link for next page
	   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
	   // echo forward link for lastpage
	   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>>></a> ";
	} // end if
	/****** end build pagination links ******/

}


?>