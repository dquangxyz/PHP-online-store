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
	<h1 class='title'>Product Details</h1>
	<?php 
		include('functions.php');
		$currentCart = getShoppingCart();

		if(isset($_GET['ProductID']))
		{		
			$productIDURL = $_GET['ProductID'];	
			
			$dbh = connectToDatabase(); 
			
			$statement = $dbh->prepare('
				SELECT * FROM Products INNER JOIN Brands
				ON Brands.BrandID = Products.BrandID
				WHERE Products.ProductID = ? '); 	
			$statement->bindValue(1,$productIDURL);
			$statement->execute();

			if($row = $statement->fetch(PDO::FETCH_ASSOC))
			{			
				$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8'); 
				$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8'); 
				$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8'); 
				$BrandName = htmlspecialchars($row['BrandName'], ENT_QUOTES, 'UTF-8'); 
				$BrandID = htmlspecialchars($row['BrandID'], ENT_QUOTES, 'UTF-8');
				$Website = htmlspecialchars($row['Website'], ENT_QUOTES, 'UTF-8');
				
				
				
				echo "
				<div class = 'grid-item detailed-item'>
					<p class='description'>$Description</p>
					<img src = '../IFU_Assets/ProductPictures/$productIDURL.jpg' alt= 'productID' />	
					<p class='price'>$ $Price</p> 
					<div class='action'>
				";
				if (strpos($currentCart, $ProductID) !== false) {
					echo "
						<p>This product has already been added in your cart. <p>
						<a href='ViewCart.php'>View Cart</a>
					";
				} else {
					echo "
						<form action='./AddToCart.php?ProductID=$ProductID' method='POST'>
							<button class='buy-button' type='submit' name='BuyButton'><span>Add to cart </span></button>
						</form>
						
					";
				}
				echo "
						
					</div>			
					<p class='brand-detail'>A product made by <a href=$Website> $BrandName</a></p>					
					<img class='brand-photo' src = '../IFU_Assets/BrandPictures/$BrandID.jpg' alt='BrandID' />					
				</div>
			
				";
			}
			else
			{
				echo "Unknown Product ID";
			}
		}
		else
		{
			echo "No ProductID provided!";
		}
	?>
</body>
</html>