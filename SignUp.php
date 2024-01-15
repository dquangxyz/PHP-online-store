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
	
	<?php
		// display any error messages. TODO style this message so that it is noticeable.
		echo $cookieMessage;
	?>

	<div class='sign-up-form'>
		<h1>Sign Up!</h1>
		<form action = 'AddNewCustomer.php' method = 'POST'>
			<!-- 
				TODO make a sign up <form>, don't forget to use <label> tags, <fieldset> tags and placeholder text. 
				all inputs are required.
				
				Make sure you <input> tag names match the names in AddNewCustomer.php
				
				your form tag should use the POST method. don't forget to specify the action attribute.
			-->
			<label for='UserName'>Choose your Username</label>
			<input name = 'UserName' type = 'text' id='UserName' required /><br/>
			<label for='FirstName'>Your First Name</label>
			<input name = 'FirstName' type = 'text' id='FirstName' required /><br/>
			<label for='LastName'>Your Last Name</label>
			<input name = 'LastName' type = 'text' id='LastName' required /><br/>
			<label for='Address'>Your Address</label>
			<input name = 'Address' type = 'text' id='Address' required /><br/>
			<label for='City'>Your City</label>
			<input name = 'City' type = 'text' id='City' required /><br/>

			<button type='submit'>Submit</button>
		</form>
	</div>
</body>
</html>