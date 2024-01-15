<?php // <--- do NOT put anything before this PHP tag

include('functions.php');

// get the cookieMessage, this must be done before any HTML is sent to the browser.
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
	<h1 class='title'>Your Cart</h1>
	<?php

	if ($cookieMessage == "User name Not found!"){
		echo "<h2 class='alert'>$cookieMessage Please <a href ='SignUp.php'>sign up</a> to continue.</h2>";
	}

	// does the user have items in the shopping cart?
	if(isset($_COOKIE['ShoppingCart']) && $_COOKIE['ShoppingCart'] != '')
	{
		// the shopping cart cookie contains a list of productIDs separated by commas
		// we need to split this string into an array by exploding it.
		$productID_list = explode(",", $_COOKIE['ShoppingCart']);
		
		// remove any duplicate items from the cart. although this should never happen we 
		// must make absolutely sure because if we don't we might get a primary key violation.
		$productID_list = array_unique($productID_list);
		
		$dbh = connectToDatabase();

		// create a SQL statement to select the product and brand info about a given ProductID
		// this SQL statement will be very similar to the one in ViewProduct.php
		$statement = $dbh->prepare('
			SELECT * FROM Products INNER JOIN Brands
			ON Brands.BrandID = Products.BrandID
			WHERE Products.ProductID = ? ');	

		echo "
		<table class='tg tg-detail nested-table'>
			<tr>
				<th style='width: 10%;'>Item No.</th>
				<th style='width: 30%;'>Product Description</th>
				<th style='width: 15%;'>Brand</th>
				<th style='width: 15%;'>Unit Price</th>
				<th style='width: 15%;'>Quantity</th>
				<th style='width: 15%;'>Total</th>
			</tr>
		";
		$totalPrice = 0;
		$itemNumber = 1;
		// loop over the productIDs that were in the shopping cart.
		foreach($productID_list as $productID)
		{
			// great thing about prepared statements is that we can use them multiple times.
			// bind the first question mark to the productID in the shopping cart.
			$statement->bindValue(1,$productID);
			$statement->execute();
			
			// did we find a match?
			if($row = $statement->fetch(PDO::FETCH_ASSOC))
			{				
				//TODO Output information about the product. including pictures, description, brand etc.				
				//TODO add the price of this item to the $totalPrice
				$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8'); 
				$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8'); 
				$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8'); 
				$BrandName = htmlspecialchars($row['BrandName'], ENT_QUOTES, 'UTF-8'); 
				$BrandID = htmlspecialchars($row['BrandID'], ENT_QUOTES, 'UTF-8');
				$Website = htmlspecialchars($row['Website'], ENT_QUOTES, 'UTF-8');
				$Quantity = 1;

				$itemPrice = (int)$Price*(int)$Quantity;
				$totalPrice = $totalPrice + (int)$Price;		
				
				echo "
				<tr>
					<td>$itemNumber</td>
					<td>
						<img src = '../IFU_Assets/ProductPictures/$productID.jpg' alt= 'productID' />
						<div>$Description</div>
					</td>
					<td><a href=$Website>$BrandName</a></td>
					<td>$ $Price</td>
					<td>$Quantity</td>
					<td>$ $itemPrice</td>
				</tr>
				";
				$itemNumber++;
			}
		}

		// TODO: output the $totalPrice.
		echo "
		<tr class='summary-row'>
			<td colspan='5' style='text-align: right;'>Total price of the cart</td>
			<td>$ $totalPrice</td>
		</tr>
		</table>
		";
		// if we have any error messages echo them now. TODO style this message so that it is noticeable.
		// echo "$cookieMessage";
		
		// you are allowed to stop and start the PHP tags so you don't need to use lots of echo statements.
		?>
			<form class='empty' action = 'EmptyCart.php' method = 'POST'>
				<input type = 'submit' name = 'EmptyCart' value = 'Empty Shopping Cart' id = 'EmptyCart' />
			</form>


			<form class='proceed' action = 'ProcessOrder.php' method = 'POST'>
			
				<!-- TODO put a text input here so the user can type in their UserName.
					 this input tag MUST have its name attribute set to 'UserName' -->
				<div>
					<label for='UserName'>Please enter your username:</label>
					<input type = 'text' name='UserName' id='UserName' required />
				</div>
				<!-- TODO put a submit button so the user can submit the form -->
				<!-- <div><input type = 'submit' name = 'ConfirmOrder' value = 'Proceed to payment' id = 'ConfirmOrder' /></div> -->
				<div>	
					<button class='buy-button' type='submit' name='ConfirmOrder' id = 'ConfirmOrder'><span>Proceed to payment </span></button>
				</div>
			</form>
			
			
		<?php 		
	}
	else
	{
		// if we have any error messages echo them now. TODO style this message so that it is noticeable.
		echo "<div class='alert'>$cookieMessage</div> <br/>";		
		echo "<div class='alert'>You have no items in your cart!</div>";
	}
	?>
</body>
</html>
