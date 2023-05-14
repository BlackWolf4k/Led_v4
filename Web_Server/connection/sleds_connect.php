<?php
	// Connects to the database
	$sleds_user_name = "sleds";
	$sleds_server_name = "127.0.0.1";
	$sleds_password = "sleds";
	$sleds_database_name = "sleds";

	// Connect to the database
	$sleds_database = new mysqli( $sleds_server_name, $sleds_user_name, $sleds_password, $sleds_database_name );

	// Check that the connection was sucessfull
	if ( $sleds_database -> connect_error )
	{
		die( "Connection Failed: " . $sleds_database -> connect_error );
	}
?>