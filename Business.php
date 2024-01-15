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
	<h1 class='title'>Business Overview</h1>
	<?php 
		include('functions.php');

        $dbh = connectToDatabase();


        $statement = $dbh->prepare('
            SELECT *, COUNT(OrderProducts.OrderID) AS Popularity, (Products.Price*OrderProducts.Quantity) AS Revenue
            FROM Products
            INNER JOIN OrderProducts ON Products.ProductID = OrderProducts.ProductID
            INNER JOIN Brands ON Products.BrandID = Brands.BrandID
            GROUP BY Products.ProductID
        ') ; 
        $statement->execute();

        echo "
        <table class='tg'>
            <tr>
                <th style='width: 10%;'>Product ID</th>
                <th style='width: 20%;'>Description</th>
                <th style='width: 10%;'>Brand Name</th>
                <th style='width: 10%;'>Price</th>               
                <th style='width: 10%;'>Quantity</th>
                <th style='width: 10%;'>Revenue</th>
                <th style='width: 10%;'>Popularity</th>
            </tr>
        ";
        $SumRevenue = 0;
        while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8');
            $Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8');
            $Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8');
            $BrandName = htmlspecialchars($row['BrandName'], ENT_QUOTES, 'UTF-8');
            $Popularity = htmlspecialchars($row['Popularity'], ENT_QUOTES, 'UTF-8');
            $Quantity = htmlspecialchars($row['Quantity'], ENT_QUOTES, 'UTF-8');
            $Revenue = htmlspecialchars($row['Revenue'], ENT_QUOTES, 'UTF-8');

            $SumRevenue = $SumRevenue + (int)$Revenue;
                       
            echo "
            <tr>
                <td><a href='./ViewProduct.php?ProductID=$ProductID'>$ProductID</a></td>
                <td>$Description</td>
                <td>$BrandName</td>
                <td>$Price</td>
                <td>$Quantity</td>
                <td>$Revenue</td>
                <td>$Popularity</td>
            </tr>
            ";
        }
        echo "</table>";

        echo "<h1 class='title'>Total Revenue: $ $SumRevenue</h1>";
	?>
</body>
</html>