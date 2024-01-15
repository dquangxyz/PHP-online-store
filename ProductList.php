<!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>My E-commerce Website</title>
		<!-- <link rel="stylesheet" type="text/css" href="shopstyle.css" /> -->
		<link rel="stylesheet" type="text/css" href="shopstyle.css?v=<?php echo time(); ?>">
	</head>

	<body>
		<?php include('Header.php'); ?>
		

		<?php
			include('functions.php');
			$dbh = connectToDatabase();

			if(isset($_GET['search'])) {
				$searchString = $_GET['search'];
			} else {
				$searchString = "";
			}

			$safeSearchString = htmlspecialchars($searchString, ENT_QUOTES,"UTF-8");
			$SqlSearchString = "%$safeSearchString%";

			if(isset($_GET['page'])) {
				$currentPage = intval($_GET['page']);
			} else {
				$currentPage = 1;
			}

			//count total number of rows
			$totalRowsStatement = $dbh->prepare('
			SELECT Products.ProductID, Products.Description, Products.Price 
			FROM Products LEFT JOIN OrderProducts
			ON OrderProducts.ProductID = Products.ProductID
			WHERE Products.Description LIKE ?
			GROUP BY Products.ProductID
			ORDER BY COUNT(OrderProducts.OrderID) DESC
			;');
			$totalRowsStatement->bindValue(1,$SqlSearchString); 
			$totalRowsStatement->execute();
			$count = 0;
			while($row = $totalRowsStatement->fetch(PDO::FETCH_ASSOC)) {
				$count++;
			}
			
			if(isset($_POST['option'])) {
				$option = $_POST['option'];
			} else if (isset($_GET['option'])) {
				$option = $_GET['option'];
			} else {
				$option = 'popularity';
			}
			
			
			switch ($option){
				case 'popularity':
					$statement = $dbh->prepare('
						SELECT Products.ProductID, Products.Description, Products.Price 
						FROM Products LEFT JOIN OrderProducts
						ON OrderProducts.ProductID = Products.ProductID
						WHERE Products.Description LIKE ?
						GROUP BY Products.ProductID
						ORDER BY COUNT(OrderProducts.OrderID) DESC
						LIMIT 20
						OFFSET (?-1) * 20
					;');
					break;
				case 'nameAsc':
					$statement = $dbh->prepare('
						SELECT Products.ProductID, Products.Description, Products.Price 
						FROM Products LEFT JOIN OrderProducts
						ON OrderProducts.ProductID = Products.ProductID
						WHERE Products.Description LIKE ?
						GROUP BY Products.ProductID
						ORDER BY Products.Description
						LIMIT 20
						OFFSET (?-1) * 20
					;');
					break;
				case 'nameDesc':
					$statement = $dbh->prepare('
						SELECT Products.ProductID, Products.Description, Products.Price 
						FROM Products LEFT JOIN OrderProducts
						ON OrderProducts.ProductID = Products.ProductID
						WHERE Products.Description LIKE ?
						GROUP BY Products.ProductID
						ORDER BY Products.Description DESC
						LIMIT 20
						OFFSET (?-1) * 20
					;');
					break;
				case 'priceAsc':
					$statement = $dbh->prepare('
						SELECT Products.ProductID, Products.Description, Products.Price 
						FROM Products LEFT JOIN OrderProducts
						ON OrderProducts.ProductID = Products.ProductID
						WHERE Products.Description LIKE ?
						GROUP BY Products.ProductID
						ORDER BY Products.Price
						LIMIT 20
						OFFSET (?-1) * 20
					;');
					break;
				case 'priceDesc':
					$statement = $dbh->prepare('
						SELECT Products.ProductID, Products.Description, Products.Price 
						FROM Products LEFT JOIN OrderProducts
						ON OrderProducts.ProductID = Products.ProductID
						WHERE Products.Description LIKE ?
						GROUP BY Products.ProductID
						ORDER BY Products.Price DESC
						LIMIT 20
						OFFSET (?-1) * 20
					;');
					break;
			}
			
			$statement->bindValue(1,$SqlSearchString);
			$statement->bindValue(2,$currentPage); 
			$statement->execute();
		?>


		<div>
			<h1 class='title'>Products List</h1>
			<form class='search-form'>
				<input class='search-box' name = 'search' type = 'text' /> 
				<button class='search-button' type = 'submit'>Search</button>
			</form>
			<form method='POST' class='search-form'>
				<label for ='option'>Sort by:</label>
				<select name = 'option' id = 'option'>
					<option value = 'popularity'>Popularity</option>
					<option <?php if ($option =='nameAsc') echo 'selected'; ?> value = 'nameAsc'>Name: A to Z</option>
					<option <?php if ($option =='nameDesc') echo 'selected'; ?> value = 'nameDesc'>Name: Z to A</option>
					<option <?php if ($option =='priceAsc') echo 'selected'; ?> value = 'priceAsc'>Price: Low to High</option>
					<option <?php if ($option =='priceDesc') echo 'selected'; ?> value = 'priceDesc'>Price: High to Low</option>
				</select>
				<button class='sort-button' type = 'submit'>Sort</button>
			</form>
		</div>


		<?php

		echo "<div class='grid-container-small'>";		
		while($row = $statement->fetch(PDO::FETCH_ASSOC)) { 
			$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8');
			$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8');
			$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8');
			
			echo "
			<div class = 'grid-item-small'>
				<div class='sale' style='visibility:hidden;'>SALE</div>
				<a href='./ViewProduct.php?ProductID=$ProductID'><img src='../IFU_Assets/ProductPictures/$ProductID.jpg' alt ='product-photo' /></a>
				<p class='description'>$Description</p>
				<p class='price'>$ $Price</p>
				<div class='action'>
					<form class='left-form' action='./ViewProduct.php?ProductID=$ProductID' method='POST'>
						<button class='buy-button' type='submit' name='BuyButton'><span>View Details </span></button>
					</form>
					<form class='right-form' action='./AddToCart.php?ProductID=$ProductID' method='POST'>
						<button class='buy-button' type='submit' name='BuyButton'><span>Add to cart </span></button>
					</form>
				</div>
			</div>
			";

			// echo "
			// <div class = 'grid-item-small'>
			// 	<a href='./ViewProduct.php?ProductID=$ProductID'><img src='../IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' /></a>
			// 	<p>$Description</p>
			// 	<p>$ $Price</p>
			// </div>
			// ";
		}
		echo "</div>";

		$numberOfProduct = $count;
		$maximumPage = intval(($numberOfProduct-1)/20)+1;

		$nextPage =  $currentPage + 1;
		$previousPage =  $currentPage - 1;

		$nextNextPage =  $currentPage + 2;
		$prevPreviousPage =  $currentPage - 2;

		echo "<div class='pageChange'>";
		echo "<span class='end-block'><a href = 'ProductList.php?page=1&search=$safeSearchString&option=$option'>First Page</a></span>";

		if ($previousPage > 0){
			echo "<span class='middle-block'><a href = 'ProductList.php?page=$previousPage&search=$safeSearchString&option=$option'> &#8592 </a></span>";
			if ($prevPreviousPage > 0){
				echo "<span class='middle-block'><a href = 'ProductList.php?page=$prevPreviousPage&search=$safeSearchString&option=$option'>$prevPreviousPage</a></span>";
			}
			echo "<span class='middle-block'><a href = 'ProductList.php?page=$previousPage&search=$safeSearchString&option=$option'>$previousPage</a></span>";
		}

		echo"<span class='middle-block pageCurrent'>$currentPage</span>";

		if($nextPage <= $maximumPage) {				
			echo "<span class='middle-block'><a href='ProductList.php?page=$nextPage&search=$safeSearchString&option=$option'>$nextPage</a></span>";
			if ($nextNextPage <= $maximumPage){
				echo "<span class='middle-block'><a href = 'ProductList.php?page=$nextNextPage&search=$safeSearchString&option=$option'>$nextNextPage</a></span>";
			}
			echo "<span class='middle-block'><a href = 'ProductList.php?page=$nextPage&search=$safeSearchString&option=$option'> &#8594 </a></span>";
		}

		echo"<span class='end-block'><a href = 'ProductList.php?page=$maximumPage&search=$safeSearchString&option=$option'>Last Page</a></span>";
		echo "</div>";
		
		?>
	</body>
</html>
