<?php
	// include the file with constants for db connection
	require("./c/constants.php");
	
	// 1. Create a connection to the server. 
	//  mysql_connect(server that we're on, who we are, password)
	$connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
	
	// make sure a connection has been made!
	if (!$connection){
		die("Database connection failed: " . mysql.error());
	}
	
	// 2. Select the database on the server (you don't really have to include second argument, since it's assumed)
	$db_select = mysql_select_db(DB_NAME, $connection);
	if (!$db_select){
		die("Database selection failed: " . mysql.error());
	}
?>