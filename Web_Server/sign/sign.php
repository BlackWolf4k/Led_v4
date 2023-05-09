<?php
	// session_save_path( "/var/www/sessions" ); // remove this in other servers
	session_start();

	// Check that hidden value is present
	if ( !isset( $_POST[ "signup" ] ) ) // Hidden value not setted
	{
		// Redirect to main page
		header( "Location: signup.php" );
		die();
	}

	// Field names of the forms
	$signup_fields = array( "email", "username", "password", "password_confirm" );
	$signin_fields = array( "username", "password" );

	// Start connection with the database
	include "../connection/sleds_connect.php";

	// Check if signing up
	if ( $_POST[ "signup" ] == 1 ) // Signing up
	{
		// Check all fields
		foreach( $signup_fields as $field )
		{
			// Check if setted
			if ( empty( $_POST[ $field ] ) )
			{
				// Something is empty
				header( "Location: ./signup.php?error=0" );
				die();
			}
		}

		// Check if really an email
		if ( !filter_var( $_POST[ "email" ], FILTER_VALIDATE_EMAIL ) )
		{
			header( "Location: ./settings.php?error=0" );
			die();
		}

		// Check the password are the same
		if ( $_POST[ "password" ] != $_POST[ "password_confirm" ] )
		{
			header( "Location: ./signup.php?error=1" );
			die();
		}

		// Check if the email was already used
		$statement = $sleds_database -> prepare( "SELECT * FROM user WHERE email=? OR username=?" );
		$statement -> bind_param( "ss", $_POST[ "email" ], $_POST[ "username" ] );
		$statement -> execute();
		$result = $statement -> get_result();

		// Return error code for email taken
		if ( mysqli_num_rows( $result ) > 0 )
		{
			$result = $result -> fetch_assoc();

			// Display message based on if email or username was already taken
			if ( $result[ "email" ] == $_POST[ "email" ] )
				header( "Location: ./signup.php?error=2" );
			else
				header( "Location: ./signup.php?error=3" );
			die();
		}

		// Insert the user to the database
		$statement = $sleds_database -> prepare( "INSERT INTO user ( email, username, password ) VALUES ( ?, ?, ? )" );
		$password = password_hash( $_POST[ "password" ], PASSWORD_BCRYPT );
		$statement -> bind_param( "sss", $_POST[ "email" ], $_POST[ "username" ], $password );
		$statement -> execute();

		// Create a directory for the user

		// Redirect to login page
		header( "Location: ./signin.php" );
		die();
	}
	else // Signing in
	{
		// Check all fields
		foreach( $signin_fields as $field )
		{
			// Check if setted
			if ( empty( $_POST[ $field ] ) )
			{
				// Something is empty
				header( "Location: ./signin.php?error=4" );
				die();
			}
		}

		// Check the user credentialities
		$statement = $sleds_database -> prepare( "SELECT id, password FROM user WHERE username=?" );
		$password = password_hash( $_POST[ "password" ], PASSWORD_BCRYPT );
		$statement -> bind_param( "s", $_POST[ "username" ] );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check if a result was found
		if ( mysqli_num_rows( $result ) == 0 ) // Nothing found
		{
			header( "Location: ./signin.php?error=1" );
			die();
		}

		$result = $result -> fetch_assoc();

		// Check the password
		if ( !password_verify( $_POST[ "password" ], $result[ "password" ] ) )
		{
			header( "Location: ./signin.php?error=1" ); // Error code is the same as username not found
			die();
		}

		// Set the session user id
		$_SESSION[ "user_id" ] = $result[ "id" ];

		// Go to the home page
		header( "Location: /home/home.php" );
		die();
	}
?>