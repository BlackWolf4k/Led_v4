<?php
	session_start();

	// Check that hidden value is present
	if ( !isset( $_POST[ "signup" ] ) ) // Hidden value not setted
	{
		// Redirect to main page
		header( "Location: signup.php" );
		die();
	}

	// Start connection with the database

	// Check if signing in
	if ( $_POST[ "signup" ] == 0 ) // Signing in
	{}
	else // Signing up
	{}
?>