<?php
	// Connects to the database
	$user_name = "sleds";
	$server_name = "localhost";
	$password = "sleds";
	$database_name = "sleds";

	// Connect to the database
	$sleds_database = new mysqli( $server_name, $user_name, $password, $database_name );

	// Check that the connection was sucessfull
	if ( $sleds_database -> connect_error )
	{
		die( "Connection Failed: " . $sleds_database -> connect_error );
	}
?>