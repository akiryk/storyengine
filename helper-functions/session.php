<?php
	// Always have to start a session with session_start()
	session_start();

	// Session related function can go in functions.php or here.
	// Put them here for ease of maintenance.
	function logged_in(){
		return isset($_SESSION['user_id']);
	}
	function confirm_logged_in(){
		if (!logged_in()){
			redirect_to("new_user.php");
		}
	}

	function start_new_story(){
		// clear session of previous story id,
		// update with new story id,
		// then redirect to first chapter of the story
		if (isset($_GET['story']) && intval($_GET['story']) > 0){
				// The story variable is set, meaning we're starting a new story.
				// This variable is only set when clicking a story from a story list (e.g. my_stories or index)
				$_SESSION['story_id'] = intval($_GET['story']);
				redirect_to("read_chapter.php?story=" . $_SESSION['story_id']);
			} else {
				redirect_to("index.php");
			}
	}

	function is_admin(){
		if ($_SESSION['admin'] == 1){
			return true;
		} else {
			return false;
		}
	}

	function confirm_user_is_admin(){
		confirm_logged_in();
		if ($_SESSION['admin'] != 1){
			redirect_to("index.php");
		}
	}

	function set_session_vars($id, $username, $admin){
		$_SESSION['user_id'] = $id;
		$_SESSION['username'] = $username;
		$_SESSION['admin'] = $admin;
	}

	function clear_story_session(){
		unset($_SESSION['story_id']);
	}

	function set_chapter_in_session($id){
		$_SESSION['current_chapter'] = $id;
	}

	function get_latest_chapter(){
		if (isset($_SESSION['current_chapter'])){
			return $_SESSION['current_chapter'];
		} else {
			return false;
		}
	}
?>