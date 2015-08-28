<!DOCTYPE html>
<html class="no-js">
<head>
  <title>a story-making game</title>
  <!-- Make sure viewport doesn't scale on mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<meta charset="utf-8">
	<link href="stylesheets/styles.css" rel="stylesheet" type="text/css">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/scripts.js"></script>
</head>

<body>

<div id="wrapper">
	<div id="header">
		<div id="header-inner" class="clearfix">
			<div id="brand">
				<h1 class="brandname">
					<a href="index.php">Storili</a>
				</h1>
			</div>	
			<div id="menu-link">
				Menu
			</div>			
			<div id="primary-navigation">
				<ul class="main-menu">
					<?php if (logged_in()){ ?>
						<?php $firstname = get_user_name($_SESSION['user_id']); ?>
						<li class="username first">Hi, <?php print $firstname; ?></li>
						<li><a href="my_stories.php?mystories=1">My stories</a></li>
				  	<li class="last"><a href="index.php?allstories=1">All stories</a></li>
					<?php } else { ?>
						<li class="solo"><a href="index.php">All stories</a></li>
					<?php } ?>
			  </ul>	
			
			  <ul class="login-menu">
					<?php if (logged_in()) { ?>
						<li class="solo"><a href="logout.php">Log out</a></li>
					<?php } else { ?> 
						<li class="first"><a href="login.php">Log in</a></li>
						<li class="last"><a href="new_user.php">Register</a></li>
					<?php } ?>		
	      </ul>
			</div> <!-- #navigation -->	
		</div> <!-- #header-inner -->
	</div> <!-- header -->
<div id="content-wrapper">
<div id="content">
	<div id="content-inner">