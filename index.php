<html>
<head>
	<title>Web Development Assignment</title>
	
	<?php
		// Connect to the database
		require("connect.php");
		
		// Reset user data if required
		if(isset($_POST['logout']))
		{
			session_start();
			$_SESSION['username'] = NULL;
			$_SESSION['password'] = NULL;
		}
	?>
</head>
<body>

	<!-- Link to css file -->
	<link rel="stylesheet" type="text/css" href="index.css" />

	<!-- Header -->
	<div id="header">
		<h2 id="headerText"> Online Library System </h2>
	</div>
	
	<!-- Register Form -->
	<div id="registerForm">
		<form action="register.php" method="POST" onsubmit="return validateRegisterForm()">
			<fieldset id="registerFieldset">
				<legend><strong>Register</strong></legend>
				<p>Username : <input class="formField" type="text" name="username" id="reg_username" required/> </p>
				<p>Password : <input class="formField" type="password" name="password" id="reg_password" maxlength="6" required/> </p>
				<p>Confirm Password : <input class="formField" type="password" name="confirmPassword" id="reg_password" maxlength="6" required/> </p>
				<p>First Name : <input class="formField" type="text" name="fname" id="reg_fname" required/> </p>
				<p>Surname : <input class="formField" type="text" name="lname" id="reg_lname" required/> </p>
				<p>Address Line 1 : <input class="formField" type="text" name="addr1" id="reg_addr1" required/> </p>
				<p>Address Line 2 : <input class="formField" type="text" name="addr2" id="reg_addr2" required/> </p>
				<p>City : <input class="formField" type="text" name="city" id="reg_city" required /> </p>
				<p>Telephone : <input class="formField" type="text" name="telephone" id="reg_telephone"  maxlength="10" required /> </p>
				<p>Mobile : <input class="formField" type="text" name="mobile" id="reg_mobile" maxlength="10" required /> </p>
				<input type="submit" value="Register" name="Register" />
			</fieldset>
		</form>
	</div>
	
	<!-- Login Form -->
	<div id="loginForm">
		<form action="login.php" method="POST">
			<fieldset id="loginFieldset">
				<legend><strong>Login</strong></legend>
				<p>Username : <input class="formField" type="text" name="username"/> </p>
				<p>Password : <input class="formField" type="password" name="password"/> </p>
				<input type="submit" value="Login" name="Login"/>
			</fieldset>
		</form>
	</div>
	
	<!-- Information Text -->
	<div id="mainTextDiv" >
		<p id="mainText">Please Login or Register to access the library system</p>
	</div>
	
	<!-- Footer -->
	<div id="footer">
		<div id="footer_topBorder"></div>
		<a id="footer_aboutLink" href="about.html">About</a>
		<a id="footer_contactLink" href="contact.html">Contact</a>
		<a id="footer_homeLink" href="index.php">Login Page</a>
	</div>
</body>
</html>