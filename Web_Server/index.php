<?php
	// Check if a board is connecting or a normal client
	// Check if a board is making a request
	if ( isset( $_GET[ "board_id" ] ) && $_GET[ "board_id" ] != "" )
	{
		// A board connected
		include "./api/requests.php";

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