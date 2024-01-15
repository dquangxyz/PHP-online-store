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
	<h1 class='title'>Customer List</h1>
	<?php 
		include('functions.php');
        
        $dbh = connectToDatabase();


        $statement = $dbh->prepare('SELECT * FROM Customers;');
        $statement->execute();

        echo "
        <table class='tg'>
            <tr>
                <th style='width: 5%;'>CustomerID</th>
                <th style='width: 10%;'>UserName</th>
                <th style='width: 10%;'>FirstName</th>
                <th style='width: 10%;'>LastName</th>
                <th style='width: 10%;'>Address</th>
                <th style='width: 10%;'>City</th>
            </tr>
        ";
        while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $CustomerID = htmlspecialchars($row['CustomerID'], ENT_QUOTES, 'UTF-8');
            $UserName = htmlspecialchars($row['UserName'], ENT_QUOTES, 'UTF-8');
            $FirstName = htmlspecialchars($row['FirstName'], ENT_QUOTES, 'UTF-8');
            $LastName = htmlspecialchars($row['LastName'], ENT_QUOTES, 'UTF-8');
            $Address = htmlspecialchars($row['Address'], ENT_QUOTES, 'UTF-8');
            $City = htmlspecialchars($row['City'], ENT_QUOTES, 'UTF-8');

            echo "
            <tr>
                <td>$CustomerID</td>
                <td>$UserName</td>
                <td>$FirstName</td>
                <td>$LastName</td>
                <td>$Address</td>
                <td>$City</td>
            </tr>
            ";
        }
        echo "</table>";
	?>
</body>
</html>