<?php
	// Connect to the database
	
	$db = mysql_connect('localhost', 'root', '') or die(mysql_error());
	mysql_select_db("webdev_assignment") or die(mysql_error());
?>