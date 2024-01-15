<!DOCTYPE HTML>
<html>
<head>
	<title>My E-commerce Website</title>
	<!-- <link rel="stylesheet" type="text/css" href="shopstyle.css" /> -->
	<link rel="stylesheet" type="text/css" href="shopstyle.css?v=<?php echo time(); ?>">
	<meta charset="UTF-8" /> 
</head>
<body>
<?php include('Header.php'); ?>

<?php
// did the user provided an OrderID via the URL?
if(isset($_GET['OrderID']))
{
	include('functions.php');
	$cookieMessage = getCookieMessage();
	if ($cookieMessage == "Order Success!!"){
		echo "<h2 class='alert'>$cookieMessage</h2>";
	}
	$UnsafeOrderID = $_GET['OrderID'];
	
	
	$dbh = connectToDatabase();
	
	// select the order details and customer details. (you need to use an INNER JOIN)
	// but only show the row WHERE the OrderID is equal to $UnsafeOrderID.
	$statement = $dbh->prepare('
		SELECT * 
		FROM Orders 
		INNER JOIN Customers ON Customers.CustomerID = Orders.CustomerID 
		WHERE OrderID = ? ; 
	');
	$statement->bindValue(1,$UnsafeOrderID);
	$statement->execute();
	
	// did we get any results?
	if($row1 = $statement->fetch(PDO::FETCH_ASSOC))
	{
		// Output the Order Details.
		$FirstName = makeOutputSafe($row1['FirstName']); 
		$LastName = makeOutputSafe($row1['LastName']); 
		$OrderID = makeOutputSafe($row1['OrderID']); 
		$UserName = makeOutputSafe($row1['UserName']);
		$Address = makeOutputSafe($row1['Address']); 
		$City = makeOutputSafe($row1['City']);
		$TimeStamp = makeOutputSafe($row1['TimeStamp']); 
		
		// display the OrderID
		echo "<h1 class='title'>OrderID: $OrderID</h1>";
		
		// its up to you how the data is displayed on the page. I have used a table as an example.
		// the first two are done for you.
		echo "<table class='tg tg-detail'>";
		echo "<tr><th>UserName</th><td>$UserName</td></tr>";
		echo "<tr><th>Customer Name</th><td>$FirstName $LastName </td></tr>";
		echo "<tr><th>Address</th><td>$Address</td></tr>";	
		//TODO show the Customers Address and City.
		//TODO show the date and time of the order.
		echo "<tr><th>City</th><td>$City</td></tr>";
		echo "<tr><th>Date Time</th><td>$TimeStamp</td></tr>";			
		
		
		// TODO: select all the products that are in this order (you need to use INNER JOIN)
		// this will involve three tables: OrderProducts, Products and Brands.
		
		$statement2 = $dbh->prepare('
			SELECT *
			FROM Products
			INNER JOIN OrderProducts ON Products.ProductID = OrderProducts.ProductID
			INNER JOIN Brands ON Products.BrandID = Brands.BrandID
			WHERE OrderProducts.OrderID = ? ; 
		');
		$statement2->bindValue(1,$UnsafeOrderID);
		$statement2->execute();
		
		$totalPrice = 0;
		echo "<tr><th>Order Details</th>";
		echo "<td style='padding:10px;'>";
		echo "
		<table class='nested-table'>
			<tr>
				<th style='width: 10%;'>Item No.</th>
				<th style='width: 30%;'>Product Description</th>
				<th style='width: 15%;'>Brand</th>
				<th style='width: 15%;'>Unit Price</th>
				<th style='width: 15%;'>Quantity</th>
				<th style='width: 15%;'>Total</th>
			</tr>
		";
		// loop over the products in this order.
		$itemNumber=1; 
		while($row2 = $statement2->fetch(PDO::FETCH_ASSOC))
		{
			//NOTE: pay close attention to the variable names.
			$ProductID = makeOutputSafe($row2['ProductID']); 
			$Description = makeOutputSafe($row2['Description']);
			$Price = makeOutputSafe($row2['Price']);
			$Quantity = makeOutputSafe($row2['Quantity']);   
			$BrandName = makeOutputSafe($row2['BrandName']);
			$BrandID = makeOutputSafe($row2['BrandID']);
			$Website = makeOutputSafe($row2['Website']);  

			$itemPrice = (int)$Price*(int)$Quantity;
			$totalPrice = $totalPrice + (int)$Price*(int)$Quantity;
			
			// TODO show the Products Description, Brand, Price, Picture of the Product and a picture of the Brand.
			// TODO The product Picture must also be a link to ViewProduct.php.
			echo "
			<tr>
				<td>$itemNumber</td>
				<td>
					<div><a href='./ViewProduct.php?ProductID=$ProductID'><img src='../IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' /></a></div>
					<div>$Description</div>
				</td>
				<td><a href=$Website>$BrandName</a></td>
				<td>$ $Price</td>
				<td>$Quantity</td>
				<td>$ $itemPrice</td>
			</tr>
			";
			$itemNumber ++;
			// TODO add the price to the $totalPrice variable.
		}		
		
		//TODO display the $totalPrice
		echo "
			<tr class='summary-row'>
				<td colspan='5' style='text-align: right;'>Total price of the cart</td>
				<td>$ $totalPrice</td>
			</tr>
		";
		echo "</table></td></tr>";		
		echo "</table>";
	}
	else 
	{
		echo "System Error: OrderID not found";
	}
}
else
{
	echo "System Error: OrderID was not provided";
}
?>
</body>
</html>
