<?php

/**
* A class for creating pagination
*
* @author Adam Kiryk
*/

class Pager
{
  /**
  * Private variables
  *
  * @var $connection stores our global database connection
  * @var $userID
  * @var $rowsperpage
  */
  private $connection;
	private $userID;
	private $rowsperpage;
	private $totalpages;
	private $filter;
	private $offset;

	public function __construct($connection, $filter="all-stories")
    {
    	$this->connection = $connection;
    	$this->filter = $filter; // determines the wording of DB queries
			$numrows = $this->getRowCount();

			// number of rows to show per page
			$this->rowsperpage = 2;
			// find out total pages
			$this->totalpages = ceil($numrows / $this->rowsperpage);
			$this->offset = $this->getOffset();

  }

	/**
  * Return the number of rows in a table
  *
  * @return int number of rows
  */
	public function getRowCount(){
	  switch($this->filter) {
			case "all-stories":
				$query = "SELECT COUNT(*) FROM stories";
				break;

			case "all-chapters":
				if (isset($_GET['chapter']) && is_numeric($_GET['chapter'])):
 					$chapterID = intval($_GET['chapter']);
 					$story = get_story_by_chapter($chapterID);
 				endif;
				$query = "SELECT COUNT(*) ";
				$query .= "FROM chapters ";
				$query .= "LEFT JOIN story_chapter ";
				$query .= "ON chapters.id = story_chapter.chapter_id ";
				$query .= "LEFT JOIN stories ";
				$query .= "ON story_chapter.story_id = stories.id ";
				$query .= "WHERE stories.id= " . $story['id'];
				$result_set = mysql_query($query, $this->connection);
				confirm_query($result_set);
				return $result_set;
				break;

			case "user-stories":
				if(isset($_SESSION['user_id'])):
  				$this->userID	= $_SESSION['user_id'];
					$query = "SELECT COUNT(*) FROM stories
										LEFT JOIN stories_users su
										ON stories.id = su.story_id
										LEFT JOIN users u
										ON u.id = su.user_id
										WHERE u.id = {$this->userID}";
				endif;
				break;

			default:
				return;
			}
		$result = mysql_query($query, $this->connection) or trigger_error("SQL", E_USER_ERROR);
	  $r = mysql_fetch_row($result);
		return $r[0];

	}

	/**
  * Return the current page number
  *
  * @return int
  */
	private function getCurrentPage(){
		// get the current page or set a default
		if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
		   // cast var as int
		   $currentpage = (int) $_GET['currentpage'];
		} else {
		   // default page num
		   $currentpage = 1;
		} // end if

		// if current page is greater than total pages...
		if ($currentpage > $this->totalpages) {
		   // set current page to last page
		   $currentpage = $this->totalpages;
		} // end if
		// if current page is less than first page...
		if ($currentpage < 1) {
		   // set current page to first page
		   $currentpage = 1;
		} // end if
		return $currentpage;
	}

	/**
  * Return the offset for query based on current page our of the total rows per page
  *
  * @return int
  */
	private function getOffset(){
	  $currentpage = $this->getCurrentPage();
		// the offset of the list, based on current page
		$offset = ($currentpage - 1) * $this->rowsperpage;
		return $offset;
	}

	/**
  * Return result of a DB query. This varies depending on our filter
  *
  * @return int number of rows
  */
	public function getResult(){
		switch($this->filter) {
			case "all-stories":
				$query = "SELECT title, id FROM stories";
				break;
			case "all-chapters":
				break;
			case "user-stories":
				if(isset($_SESSION['user_id'])):
  				$this->userID	= $_SESSION['user_id'];
					$query = "SELECT stories.title, stories.id FROM stories
										LEFT JOIN stories_users su
										ON stories.id = su.story_id
										LEFT JOIN users u
										ON u.id = su.user_id
										WHERE u.id = {$this->userID}";
					endif;
				break;
			default:
				return;
			}
		$query .= " LIMIT $this->offset, $this->rowsperpage";
		$result_set = mysql_query($query, $this->connection);
		confirm_query($result_set);
		return $result_set;
	}

	/**
  * Draws the pagination links (< 1,2,3 >).
  *
  * @return void
  */
	public function drawPaginationLinks(){
		/******  build the pagination links ******/
		// range of num links to show
		$range = 3;
		$currentpage = $this->getCurrentPage();
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
		   if (($x > 0) && ($x <= $this->totalpages) && ($this->totalpages > 1)) {
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
		if ($currentpage != $this->totalpages) {
		   // get next page
		   $nextpage = $currentpage + 1;
		    // echo forward link for next page
		   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
		   // echo forward link for lastpage
		   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$this->totalpages'>>></a> ";
		} // end if
	}
}