<?php
	// Main function to handle the request of a client
	function handle_request()
	{
		// Set the header content type as json
		// header( "Content-Type:application/json" );

		// Check the values of the request
		if ( !isset( $_GET[ "code" ] ) )
			// Not all values needed are found
			die();
		
		include "responses.php";

		// Check the token
		// Sync the board data with the database
		// Decide what to respond
		//var_dump( $codes );
		$codes[ $_GET[ "code" ] ]();
		//$codes[ $_GET[ "code" ] ]();
		// Ask the database for the data to send
		// Send the data
	}
?>