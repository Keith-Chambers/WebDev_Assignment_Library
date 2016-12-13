<?php
	require("connect.php");

	function populateCategoryDropdown()
	{
		// Populate dropdown from mysql database
		$query = "SELECT CategoryDesc, CategoryID FROM Category";
		$result = mysql_query($query);
		
		if(! $result )
		{
			return;
		}
		
		echo "<option value='-1'>All</option>";
		
		while($row = mysql_fetch_array($result))
		{
			echo "<option value='$row[1]'>" . $row[0] . "</option>";
		}
	}
	
	function determinePageNum($query, $perPage)
	{
		$MAXPAGE = determineMaxPageSize($query, $perPage);
		$pageNum = 1;
		
		if(isset($_POST['currPageNum']))
		{
			if(isset($_POST['nextPage']))
			{
				$pageNum = $_POST['currPageNum'] + 1;
			}else if(isset($_POST['prevPage']))
			{
				$pageNum = $_POST['currPageNum'] - 1;
			}else
			{
				$pageNum = $_POST['currPageNum'];
			}
			
			if($pageNum > $MAXPAGE || $pageNum < 1)
			{
				$pageNum = $_POST['currPageNum'];
			}
		}
		
		return $pageNum;
	}
	
	function determineMaxPageSize($query, $perPage)
	{
		if($query == "")
			return 0;
		
		$result = mysql_query($query);
		return ceil(mysql_num_rows($result) / $perPage);
	}
	
	function buildQueryString($searchTitle, $searchAuther, $searchElements)
	{
		$queryString;
		$numSearchElements = count($searchElements);
		
		if($searchTitle == true && $searchAuther == true)
		{
			$queryString = "SELECT IBSN, Auther, BookTitle, Edition, Year, CategoryDesc, Reserved FROM Books JOIN Category ON (Category.CategoryID=Books.Category) WHERE (Auther LIKE '%" . $searchElements[0] . "%' OR BookTitle LIKE '%" . $searchElements[0] . "%'";
		
			for($i = 0; $i < $numSearchElements - 1; $i++)
			{
				$queryString = $queryString . " OR Auther LIKE '%" . $searchElements[$i] . "%' OR BookTitle LIKE '%" . $searchElements[$i] . "%'";
			}
			
		}else if($searchTitle == true)
		{
			$queryString = "SELECT IBSN, Auther, BookTitle, Edition, Year, CategoryDesc, Reserved FROM Books JOIN Category ON (Category.CategoryID=Books.Category) WHERE (BookTitle LIKE '%" . $searchElements[0] . "%'";
		
			for($i = 0; $i < $numSearchElements - 1; $i++)
			{
				$queryString = $queryString . " OR BookTitle LIKE '%" . $searchElements[$i] . "%'";
			}
		}else if($searchAuther == true)
		{
			$queryString = "SELECT IBSN, Auther, BookTitle, Edition, Year, CategoryDesc, Reserved FROM Books JOIN Category ON (Category.CategoryID=Books.Category) WHERE (Auther LIKE '%" . $searchElements[0] . "%'";
		
			for($i = 0; $i < $numSearchElements - 1; $i++)
			{
				$queryString = $queryString . " OR Auther LIKE '%" . $searchElements[$i] . "%'";
			}
		}else
		{
			echo "Please tick either the Auther or Title checkboxes to search.";
			return "";
		}
		
		$queryString =  $queryString . ")";
		
		if($_POST['categorySelectBox'] != '-1')
		{
			$queryString = $queryString . " AND Books.Category=" . $_POST['categorySelectBox'];
		}
		
		return $queryString;
	}
	
	function outputResultTable($page, $perPage, $result)
	{
		$count = ($perPage * ($page - 1)) + 1;
		$loopCounter = 1;
		$max = $count + $perPage;
		echo "<table border='0' id='resultTable'>";
		echo "<tr><th>#</th><th>Auther</th><th>Title</th><th>Year</th><th>Category</th><th>Reserved</th><th></th></tr>";
		
		while(($row = mysql_fetch_assoc($result)) && $count < $max)
		{
			if($loopCounter < $count)
			{
				$loopCounter++;
				continue;
			}
			$loopCounter++;
			
			$reserveString;
			if($row['Reserved'] == 'N')
			{
				$reserveString = "<button type='button' name='reserveButton' onclick='handleReservation(" . $count . ")' value='" . $count . "'>Reserve</button>"; 
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
	}
	
	function reserveBook($count, $query)
	{
		$result = mysql_query($query);
		
		if(! $result )
			die("Failed to execute query in reserveForm()");
		
		$curr = 1;
		
		if($count > mysql_num_rows($result))
			die("Attempt to reserve a book with an invalid index");
		
		while($row = mysql_fetch_assoc($result))
		{
			if($curr++ != $count)
				continue;
			else
			{
				$toReserve = $row;
				break;
			}
		}
		
		$updateQuery = "UPDATE books SET Reserved='Y' WHERE IBSN='" . $toReserve['IBSN'] . "'";
		$reservationQuery = "INSERT INTO Reservations (Username, IBSN, ReserveDate) VALUES 
		('" . $_SESSION['username'] . "', '" . $toReserve['IBSN'] . "', CURDATE())";
		$result = mysql_query($updateQuery);
		
		if(! $result)
			die("Failed to execute update query");
		else
			$result = mysql_query($reservationQuery);
		
		if(! $result)
		{
			echo "Failed to update Reservation table";
			$revertQuery = "UPDATE Books SET Reserved='N' WHERE IBSN='" . $toReserve['IBSN'] . "'";
			$revertResult = mysql_query($revertQuery);
			if(! $revertQuery)
				die("Failed to keep database integrety, Book table must manually revert last change");
		}
	}
	
	function deleteReservation($number)
	{
		$query = "SELECT * FROM Reservations WHERE Username='" . $_SESSION['username'] ."'";
		$result = mysql_query($query);
		if(! $result)
			die("Failed to execute query");
		
		$curr = 1;
		$toDelete;
		
		while($row = mysql_fetch_assoc($result))
		{
			if($curr++ != $number)
				continue;
			
			$toDelete = $row;
			break;
		}
		
		$query = "DELETE FROM Reservations WHERE username='" . $_SESSION['username'] . "' AND IBSN='" . $toDelete['IBSN'] . "'";
		$result = mysql_query($query);
		
		if(! $result)
			die("Failed to delete entry from reservation table");
		
		$query = "UPDATE Books SET Reserved='N' WHERE IBSN='" . $toDelete['IBSN'] . "'";
		$result = mysql_query($query);
		
		if(! $result)
			die("Failed to update Books table");
	}
	
?>