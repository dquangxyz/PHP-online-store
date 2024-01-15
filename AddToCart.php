<?php // <--- do NOT put anything before this PHP tag

include('functions.php');


// Did the user click the buy button AND did they provide a ProductID?
if(!isset($_POST['BuyButton'])) 
{
	echo "BuyButton not provided, the submit button should be named BuyButton and the form method should be POST"; 
}
elseif(!isset($_GET['ProductID']))
{
	echo "ProductID not provided, make sure you have passed the ProductID in the URL"; 
}
else 
{
	$productToBuy = trim($_GET['ProductID']);

	$Quantity = trim($_POST['Quantity']);
	
	
	// add the product to the shopping cart cookie
	// if the cookie is defined AND not an empty string
	if(isset($_COOKIE['ShoppingCart']) && $_COOKIE['ShoppingCart'] != "")
	{
		// TODO: Get the list of items in the shopping cart.
		// and then add the $productToBuy to the end of the comma separated list.
		// --> you may want to check lecture 14. <--
		
		// TODO: set the "ShoppingCart" cookie (notice the capital letters).
		// setcookie(...);
		$currentCart = getShoppingCart();
		$currentCart = $currentCart . "," . $productToBuy;
		setcookie("ShoppingCart", $currentCart);
		setCookieMessage($productToBuy);
	}
	else 
	{
		// add this productID to the shopping cart.
		// because this is the first item in the cart no commas are required.

		// TODO: set the "ShoppingCart" cookie (notice the capital letters).
		setcookie("ShoppingCart", $productToBuy);
		setCookieMessage($productToBuy);
	}
	
	// redirect the user back to ViewProduct.php 
	redirect("ViewProduct.php?ProductID=$productToBuy");
}
