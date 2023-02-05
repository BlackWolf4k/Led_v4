<?php
	// Main function to handle the request of a client
	function handle_request()
	{
		// Set the header content type as json
		header( "Content-Type:application/json" );

		// Check the token
		// Check the values of the request
		// Sync the board data with the database
		// Decide what to respond
		// Ask the database for the data to send
		// Send the data
		$response = json_encode( $_GET );
		echo $response;
	}
?>