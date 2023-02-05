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
		// Display the home page
	}
?>