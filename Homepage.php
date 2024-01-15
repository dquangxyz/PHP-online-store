<?php // <--- do NOT put anything before this PHP tag
	include('functions.php');
	$cookieMessage = getCookieMessage();

?>
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
	<h1 class='title'>Welcome</h1>
	<?php
		// display any cookie messages. TODO style this message so that it is noticeable.
		$dbh = connectToDatabase();

		echo "<p class='alert'>$cookieMessage</p>";
			
		// TODO put a search box here and a submit button.
		$input="";
		echo"
		<form class='search-form' method='GET' action='ProductList.php' >
				<input class='search-box' name = 'search' type = 'text' /> 
				<button class='search-button' type = 'submit'>Search Products</button>
		</form>
		";

		
		// TODO the rest of this page is your choice, but you must not leave it blank.

		
		//Hot sales - TV
		$statement1 = $dbh->prepare("SELECT * FROM Products WHERE Description LIKE '%tv%'
									ORDER BY Price DESC LIMIT 5;");
		$statement1->execute();
		echo "<h2 class='title'>Hot Sales in TV products</h2>";
		
		echo "<div class='grid-container-small'>";		
		while($row = $statement1->fetch(PDO::FETCH_ASSOC)) {
			$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8');
			$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8');
			$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8');
			
			echo "
			<div class = 'grid-item-small'>
				<div class='sale'>SALE</div>
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
		}
		echo "</div>";	
		echo "<div class='more'><a href='ProductList.php?search=tv'>More Products &#187</a></div>";
				

		//Hot sales - Camera
		$statement2 = $dbh->prepare("SELECT * FROM Products WHERE Description LIKE '%camera%'
									ORDER BY Price DESC LIMIT 5;");
		$statement2->execute();
		echo "<h2 class='title'>Hot Sales in Camera products</h2>";
		
		echo "<div class='grid-container-small'>";		
		while($row = $statement2->fetch(PDO::FETCH_ASSOC)) {
			$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8');
			$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8');
			$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8');
			
			echo "
			<div class = 'grid-item-small'>
				<div class='sale'>SALE</div>
				<a href='./ViewProduct.php?ProductID=$ProductID'><img src='../IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' /></a>
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
		}
		echo "</div>";	
		echo "<div class='more'><a href='ProductList.php?search=camera'>More Products &#187</a></div>";

		//Top Popular Products
		$statement3 = $dbh->prepare('SELECT Products.ProductID, Products.Description, Products.Price 
			FROM Products LEFT JOIN OrderProducts
			ON OrderProducts.ProductID = Products.ProductID
			GROUP BY Products.ProductID
			ORDER BY COUNT(OrderProducts.OrderID) DESC
			LIMIT 5
		;');
		$statement3->execute();
		echo "<h2 class='title'>Top Popular Products</h2>";

		echo "<div class='grid-container-small'>";		
		while($row = $statement3->fetch(PDO::FETCH_ASSOC)) {
		$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8');
		$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8');
		$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8');

		echo "
		<div class = 'grid-item-small'>
		<div class='sale popular'>Popular</div>
		<a href='./ViewProduct.php?ProductID=$ProductID'><img src='../IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' /></a>
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
		}
		echo "</div>";	
		echo "<div class='more'><a href='ProductList.php?search='>More Products &#187</a></div>";
		// Possible ideas:
		// •	List the 10 most recently purchased products.
		// •	Use a CSS Animated Slider.
		// •	Display any sales or promotions (using an image)



	?>
</body>
</html>