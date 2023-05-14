<?php
	// Connects to the database
	$sleds_production_user_name = "sleds";
	$sleds_production_server_name = "127.0.0.1";
	$sleds_production_password = "sleds";
	$sleds_production_database_name = "sleds_production";

	// Connect to the database
	$sleds_production_database = new mysqli( $sleds_production_server_name, $sleds_production_user_name, $sleds_production_password, $sleds_production_database_name );

	// Check that the connection was sucessfull
	if ( $sleds_production_database -> connect_error )
	{
		die( "Connection Failed: " . $sleds_production_database -> connect_error );
	}
?>