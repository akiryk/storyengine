<?php require_once("helper-functions/session.php"); ?>
<?php require_once("helper-functions/functions.php"); ?>
<?php require_once("helper-functions/connection.php"); ?>
<?php 
	// Make sure that this user is registered as an admin in the users table.
	// If they aren't send them to the home page or somewhere else.
	// The logic is in this function, which lives in session.php.
confirm_user_is_admin(); // make sure there is a story in the session. We can't create chapters without a story. ?>

<?php 
	include("includes/header.php");
	$people = get_users(); ?>
	
	<li class="person label">
			<ul class="person">
				<li class="child label">Firstname | Lastname</li>
				<li class="child">Username</li>
				<li class="child numeric">Admin</li>
			</ul>
		</li>
	<?php		
	// foreach ($people as $person){
	while($person = mysql_fetch_array($people)){ ?>
		<li class="person">
			<ul class="person">
				<li class="child"><?php print $person['firstname'] . " " . $person['lastname'];?> </li>
				<li class="child"><?php print $person['username'];?> </li>
				<?php if ($person['admin'] != 1): ?>
					<li class="child numeric"><a href="delete_user.php?user=<?php echo $person['id'];?>">Delete</a></li>
				<?php endif; ?>
			</ul>
		</li>
	<?php }; ?>

	<?php include("includes/footer.php"); ?>