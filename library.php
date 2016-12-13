<html>
<head>
	<title>Web Development Assignment</title>
	
	<?php
		// Include required functions
		require("libraryapi.php");
		
		// Start session data
		session_start();
		
		// Do not render page if user is not logged in
		if($_SESSION['username'] == NULL || $_SESSION['password'] == NULL)
		{
			echo "Invalid user session";
			die();
		}
		
		
		// Reset page / search details
		$page = 1;
		$RESULTS_PER_PAGE = 5;
		$searchTitle = false;
		$searchAuther = false;
		
		// Delete a reservation if requested
		if(isset($_POST['deleteReservation']))
		{
			deleteReservation($_POST['deleteReservation']);
			$_POST['deleteReservation'] = null;
			$_POST['deleteReservationForm'] = null;
		}
		
	?>
</head>
<body>

	<!-- Link to css file -->
	<link rel="stylesheet" type="text/css" href="library.css" />

	<!-- Header -->
	<div id="header">
		<h2 id="headerText"> Online Library System </h2>
	</div>
	
	<!-- Search bar -->
	<div id="searchBarContainer">
		<form action="library.php" method="POST" name="search" id="searchForm">
			<input id="searchBar" type="search" name="searchBar" size="5"placeholder="Enter Search Here" autofocus/>
			<div id="titleOpt">Title<input type="checkbox" name="searchTitle" value="true" checked></div>
			<div id="autherOpt"> Auther<input type="checkbox" name="searchAuther" value="true" checked> </div>
			
			<select id="categorySelectBox" name="categorySelectBox">
			<?php populateCategoryDropdown(); ?>
			</select>
		</form>

		<button id="searchButton" type="submit" name="searchButton" form="searchForm" value="searchButton">Search</button>
	</div>
	
	<!-- Results Space -->
	
	<div id="searchSpaceContainer">
	
		<?php
			
			if(isset($_POST['reserveElement']) && isset($_SESSION['searchQuery']))
			{
				reserveBook($_POST['reserveElement'], $_SESSION['searchQuery']);
				$_POST['reserveElement'] = NULL;
				$_SESSION['searchQuery'] = NULL;
			}
			
			// Echo out contents of search result
			
			if(isset($_POST['searchButton']) && isset($_POST['searchBar']))
			{
				// Use the previous search query and show next page
				if(isset($_SESSION['searchQuery']) && isset($_POST['currPageNum']))
				{
					$query = $_SESSION['searchQuery'];
				}else
				{			
					if(isset($_POST['searchTitle']))
					{
						$searchTitle = true;
					}
					if(isset($_POST['searchAuther']))
					{
						$searchAuther = true;
					}
					
					// Split up search tokens
					$_POST['searchButton'] = NULL;
					$searchElements = array();
					$searchElements = preg_split('/\s+/', $_POST['searchBar']);
					$numSearchElements = count($searchElements);
					$query = "";
					
					$query = buildQueryString($searchTitle, $searchAuther, $searchElements);
				}
				
				
				$_SESSION['searchQuery'] = $query;
				$page = determinePageNum($query, $RESULTS_PER_PAGE);
				
				$result = mysql_query($query);
				
				if(! $result)
				{
					die(mysql_error());
				}
				
				outputResultTable($page, $RESULTS_PER_PAGE, $result);
			}
		?>
		
		<!-- Page arrows -->
		<form id="pageArrows" name="pageArrows" method="POST" action="library.php">
			<input type="image" height="50px" width="50px" src="leftArrow.svg" name="prevPage" value="set" id="leftArrow"/>
			<input type="image" height="50px" width="50px" src="rightArrow.svg" name="nextPage" value="set" id="rightArrow"/>
			<input type="hidden" name="searchQuery" value=" <?php echo htmlentities($query); ?>" />
			<input type="hidden" name="currPageNum" value=' <?php echo $page; ?> ' />
			<input type="hidden" name="searchButton" value=' <?php echo "Set"; ?> ' />
			<input type="hidden" name="searchBar" value=' <?php echo htmlentities($_POST['searchBar']); ?> ' />
		</form>
		
	</div>
	
	<form id="reserveForm" name="reserveForm" action="library.php" method="POST">
		<input type="hidden" name="searchQuery" value=" <?php echo $query; ?>" />
		<input type="hidden" name="searchButton" value=' <?php echo "Set"; ?> ' />
		<input type="hidden" name="currPageNum" value=' <?php echo $page; ?> ' />
		<input type="hidden" name="reserveElement" id="reserveElement"/>
	</form>
	
	<form id="deleteReservationForm" name="deleteReservationForm" action="library.php" method="POST">
		<input type="hidden" name="deleteReservation" id="deleteReservation"/>
	</form>
	
	<div id="accountPage">
		<h3 id="accountSecHeaderText"> 
			<?php 
				echo ucfirst($_SESSION['username']); 
			?>
		</h3>
		
		<!-- Logout button link -->
		<form id="logoutForm" name="logoutForm" action="index.php" method="POST">
			<input type="submit" name="logout" id="logoutLink" value="logout" />
		</form>
		
		<!-- Reservation list -->
		<?php
			$reserveQuery = "SELECT BookTitle, ReserveDate FROM Reservations JOIN Books USING (IBSN) WHERE username='" . $_SESSION['username'] . "'";
			$reserveResult = mysql_query($reserveQuery);
			
			echo "<table id='reservationTable'>";
			echo "<tr><th>Book</th><th>Date</th><th></th></tr>";
			$count = 1;
			
			if($reserveResult != false && $reserveResult != null)
			{
				while($row = mysql_fetch_assoc($reserveResult))
				{
					$deleteReservationButton = "<button id='deleteReservation' name='deleteReservation' onclick='deleteReservation(" . $count . ")'>Delete</button>";
					echo "<tr>";
					echo "<td>" . htmlentities($row['BookTitle']) . "</td>";
					echo "<td>" . htmlentities($row['ReserveDate']) . "</td>";
					echo "<td>" . $deleteReservationButton . "</td>";
					echo "</tr>";
					$count++;
				} 
			}else
			{
				echo "<tr><td>No results found</td></tr>";
			}
			echo "</table>";
			
		?>
	</div>
	
	<!-- Footer -->
	<div id="footer">
		<div id="footer_topBorder"></div>
		<a id="footer_aboutLink" href="about.html">About</a>
		<a id="footer_contactLink" href="contact.html">Contact</a>
		<a id="footer_homeLink" href="index.php">Login Page</a>
	</div>
	
	<script>
		function handleReservation(count)
		{
			console.log("Working");
			document.getElementById("reserveElement").value = count;
			console.log(document.getElementById("reserveElement").value);
			document.getElementById("reserveForm").submit();
		}
		
		function deleteReservation(count)
		{
			console.log("Deleting reservation");
			document.getElementById("deleteReservation").value = count;
			document.getElementById("deleteReservationForm").submit();
		}
	</script>
</body>
</html>