<?php
	// Check if passing a token and a code ( means something is making a request )
	if ( isset( $_GET[ "token" ] ) && isset( $_GET[ "code" ] ) )
	{
		// A board connected
		include "./api/handler.php";

		// Handle the request of the board
		handle_request();
	}
	else // A client is connecting
	{
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<link rel = "shortcut icon" href = "../sleds_favicon.ico" type = "image/x-icon" />
		</head>
		<body>
			<div>
				<?php echo $_SERVER["REQUEST_URI"]; ?>
				<a href = "./sign/signin.php" >Sign In</a>
				<a href = "./sign/signup.php" >Sign Up</a>
			</div>
		</body>
	</html>
	<?php
	}
?>