<html>
<head>
</head>
<body>
	<form action="index.php" method="POST">

<?php
	/* Register.php Validate and insert new user information into the database */
	
	require("connect.php");

	if(isset($_POST['Register']))
	{
		$username = htmlentities($_POST['username']);
		$password = htmlentities($_POST['password']);
		$confirmPassword = htmlentities($_POST['confirmPassword']);
		$fname = htmlentities($_POST['fname']);
		$lname = htmlentities($_POST['lname']);
		$addr1 = htmlentities($_POST['addr1']);
		$addr2 = htmlentities($_POST['addr2']);
		$city = htmlentities($_POST['city']);
		$telephone = htmlentities($_POST['telephone']);
		$mobile = htmlentities($_POST['mobile']);
		
		// Complete validations
		if(strlen($mobile) != 10 || strlen($telephone) != 10)
		{
			echo "Phone and mobile numbers must be 10 digits long";
			echo "<input type='submit' name='registrationError' value='return'/>";
			echo $mobile . " ; " . $telephone;
			return;
		}
		
		if(ctype_digit($mobile) == false || ctype_digit($telephone) == false)
		{
			echo "Phone and mobile numbers must be fully numeric";
			echo "<input type='submit' name='registrationError' value='return'/>";
			return;
		}
		
		if($password != $confirmPassword)
		{
			echo "Passwords do not match";
			echo "<input type='submit' name='registrationError' value='return'/>";
			return;
		}
		
		if(strlen($password) != 6)
		{
			echo "Password must be 6 charactors in length";
			echo "<input type='submit' name='registrationError' value='return'/>";
			return;
		}
		
		// Add new user to the database
		$query = "INSERT INTO USERS (Username, Password, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile) VALUES( '$username', '$password', '$fname', '$lname', '$addr1', '$addr2', '$city', '$telephone', '$mobile')";
			
		if(mySql_query($query) == FALSE)
		{
			echo mysql_error();
			sleep(4);
			header('Location: index.php');
			die();
		}else
		{
			echo "Successfully added to the database";
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
	</form>
</body>
</html>