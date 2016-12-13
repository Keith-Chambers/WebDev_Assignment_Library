<?php
	/* Login.php Validate user details */

	require("connect.php");

	if(isset($_POST['Login']))
	{
		// Remove html elements
		$username = htmlentities($_POST['username']);
		$password = htmlentities($_POST['password']);
		
		// Assume all is set
		
		$query = "SELECT Username, Password FROM USERS WHERE Username='$username' AND Password='$password'";
		$result = mySql_query($query);
		if(mysql_num_rows($result) == 0)
		{
			// Display error message and then redirect back to login / register page
			echo "Username and password do not belong to any registered user.";
			sleep(4);
			header('Location: index.php');
			die();
		}else
		{
			echo "Login successful";
			/* Save login details so they can be accessed from other pages */
			session_start();
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
			header('Location: library.php');
			die();
		}
	}else
	{
		echo "No POST information passed";
		sleep(4);
		header('Location: index.php');
		die();
	}
?>