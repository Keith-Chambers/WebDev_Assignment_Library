<html>
<head>
	<title>Web Development Assignment</title>
	
	<?php
		require("libraryapi.php");
		session_start();
		$page = 1;
		$RESULTS_PER_PAGE = 5;
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
	
			// Echo out contents of search result
			
			if(isset($_POST['searchButton']))
			{
				
				if(strlen($_POST['searchBar']) <= 0)
				{
					return;
				}
				
				$searchTitle = false;
				$searchAuther = false;
				
				if(isset($_POST['searchTitle']))
				{
					$searchTitle = true;
				}
				if(isset($_POST['searchAuther']))
				{
					$searchAuther = true;
				}
				
				$_POST['searchButton'] = NULL;
				$searchElements = array();
				$searchElements = preg_split('/\s+/', $_POST['searchBar']);
				$numSearchElements = count($searchElements);
				$query = "";
				
				// Use the previous search query and show next page
				if(isset($_POST['searchQuery']) && isset($_POST['currPageNum']))
				{
					$query = $_POST['searchQuery'];
					if(isset($_POST['nextPage']))
						$page = $_POST['currPageNum'] + 1; 
					else if(isset($_POST['prevPage']) && $page > 1)
						$page = $_POST['currPageNum'] - 1;
					else
						die("Neither next nor previous page buttons set");
					
					assert($page > 0);
				}else
				{
					if($searchTitle == true && $searchAuther == true)
					{
						$query = "SELECT Auther, BookTitle, Edition, Year, CategoryDesc, Reserved FROM Books JOIN Category ON (Category.CategoryID=Books.Category) WHERE (Auther LIKE '%" . $searchElements[0] . "%' OR BookTitle LIKE '%" . $searchElements[0] . "%'";
					
						for($i = 0; $i < $numSearchElements - 1; $i++)
						{
							$query = $query . " OR Auther LIKE '%" . $searchElement[$i] . "%' OR BookTitle LIKE '%" . $searchElement[$i] . "%'";
						}
						
					}else if($searchTitle == true)
					{
						$query = "SELECT Auther, BookTitle, Edition, Year, CategoryDesc, Reserved FROM Books JOIN Category ON (Category.CategoryID=Books.Category) WHERE (BookTitle LIKE '%" . $searchElements[0] . "%'";
					
						for($i = 0; $i < $numSearchElements - 1; $i++)
						{
							$query = $query . " OR BookTitle LIKE '%" . $searchElement[$i] . "%'";
						}
					}else if($searchAuther == true)
					{
						$query = "SELECT Auther, BookTitle, Edition, Year, CategoryDesc, Reserved FROM Books JOIN Category ON (Category.CategoryID=Books.Category) WHERE (Auther LIKE '%" . $searchElements[0] . "%'";
					
						for($i = 0; $i < $numSearchElements - 1; $i++)
						{
							$query = $query . " OR Auther LIKE '%" . $searchElement[$i] . "%'";
						}
					}else
					{
						echo "Please tick either the Auther or Title checkboxes to search.";
					}
					
					$query =  $query . ")";
					
					if($_POST['categorySelectBox'] != '-1')
					{
						$query = $query . " AND Books.Category=" . $_POST['categorySelectBox'];
					}
				}
				
				$result = mysql_query($query);
				
				if(! $result)
				{
					echo "Error";
					echo mysql_error();
					return;
				}
				
				$count = 1*$page;
				echo "<table border='0' id='resultTable'>";
				echo "<tr><th>#</th><th>Auther</th><th>Title</th><th>Year</th><th>Category</th><th>Reserved</th><th></th></tr>";
				
				while(($row = mysql_fetch_assoc($result)) && $count <= 5*$page && $count >= 1 + 5*($page - 1))
				{
					$reserveString;
					if($row['Reserved'] == 'N')
					{
						$reserveString = "<button type='button' onClick='reserveBook(" . $count . ")' name='reserveButton'>Reserve</button>"; 
					}else
					{
						$reserveString = "";
					}
					
					echo "<tr>";
					echo "<td>" . $count . "</td>";
					echo "<td>" . $row['Auther'] . "</td>";
					echo "<td>" . $row['BookTitle'] . " Edition " .$row['Edition'] ."</td>";
					echo "<td>" . $row['Year'] . "</td>";
					echo "<td>" . $row['CategoryDesc'] . "</td>";
					echo "<td>" . $row['Reserved'] . "</td>";
					echo "<td>" . $reserveString . "</td>"; 
					echo "</tr>";
					$count++;
				}
				echo "</table>";

				
			}else
			{
				echo "Still waiting..";
			}
		?>
		
		<form id="pageArrows" name="pageArrows" method="POST" action="library.php">
			<input type="image" height="50px" width="50px" src="leftArrow.svg" name="prevPage" value="set" id="leftArrow"/>
			<input type="image" height="50px" width="50px" src="rightArrow.svg" name="nextPage" value="set" id="rightArrow"/>
			<input type="hidden" name="searchQuery" value=" <?php echo htmlentities($query); ?>" />
			<input type="hidden" name="currPageNum" value=' <?php echo $page; ?> ' />
			<input type="hidden" name="searchButton" value=' <?php echo "Set"; ?> ' />
			<input type="hidden" name="searchBar" value=' <?php echo htmlentities($_POST['searchBar']); ?> ' />
		</form>
		
	</div>
	
	<div id="accountPage">
		<h3 id="accountSecHeaderText"> 
			<?php 
				echo ucfirst($_SESSION['username']); 
			?>
		</h3>
	</div>
	
	<!-- Footer -->
	<div id="footer">
		<div id="footer_topBorder"></div>
		<a id="footer_aboutLink" href="https://google.com">About</a>
		<a id="footer_contactLink" href="google.com">Contact</a>
		<a id="footer_homeLink" href="google.com">Home</a>
	</div>
</body>
</html>