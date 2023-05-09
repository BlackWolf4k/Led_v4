<?php
// session_save_path( "/var/www/sessions" ); // remove this in other servers
session_start();

/*
RETURN ERRORS
-0: Not all values setted
-1: Username or email already in use
-2: Password error ( or the new password are different or the old password is incorrect )
*/

// Check the user session
if ( !isset( $_SESSION[ "user_id" ] ) ) // The user is not signed in
{
	header( "Location: /sign/signin.php" );
	die();
}

if ( !isset( $_POST[ "action" ] ) )
{
	header( "Location: ./settings.php?error=0" );
	die();
}

if ( $_POST[ "action" ] == 1 ) // Changing the username or the password
{
	$values_to_check = [ "email", "username" ];

	// Check the values
	foreach( $values_to_check as &$value )
	{
		// Check that all the values are setted
		if ( !isset( $_POST[ $value ] ) )
		{
			header( "Location: ./settings.php?error=0" );
			die();
		}
	}

	// Check if really an email
	if ( !filter_var( $_POST[ "email" ], FILTER_VALIDATE_EMAIL ) )
	{
		header( "Location: ./settings.php?error=1" );
		die();
	}

	include "../../connection/sleds_connect.php";

	// Check that the user name is valid
	$statement = $sleds_database -> prepare( "SELECT username, email FROM user WHERE ( username=? OR email=? ) AND id NOT ?" );
	$statement -> bind_param( "ssi", $_POST[ "username" ], $_POST[ "email" ], $_SESSION[ "user_id" ] );
	$statement -> execute();
	$result = $statement -> get_result();

	// Check if something is wrong
	if ( mysqli_num_rows( $result ) > 0 ) // Username or email already in use
	{
		header( "Location: ./settings.php?error=1" );
		die();
	}

	// Update the users settings
	$statement = $sleds_database -> prepare( "UPDATE user SET username=?, email=? WHERE id=?" );
	$statement -> bind_param( "ssi", $_POST[ "username" ], $_POST[ "email" ], $_SESSION[ "user_id" ] );
	$statement -> execute();
}
else if ( $_POST[ "action" ] == 2 ) // Changing the password
{
	$values_to_check = [ "old_password", "new_password", "new_password_confirm" ];

	// Check the values
	foreach( $values_to_check as &$value )
	{
		// Check that all the values are setted
		if ( !isset( $_POST[ $value ] ) )
		{
			header( "Location: ./settings.php?error=0" );
			die();
		}
	}

	if ( $_POST[ "new_password" ] != $_POST[ "new_password_confirm" ] )
	{
		header( "Location: ./settings.php?error=2" );
		die();
	}

	include "../../connection/sleds_connect.php";

	// Insert the new password into the database
	// At the same time checks if the old password is wright
	$statement = $sleds_database -> prepare( "UPDATE user SET password=? WHERE id=? AND password=?" );
	$old_password = password_hash( $_POST[ "old_password" ], PASSWORD_BCRYPT );
	$new_password = password_hash( $_POST[ "new_password" ], PASSWORD_BCRYPT );
	$statement -> bind_param( "sis", $new_password, $_SESSION[ "user_id" ], $old_password );
	$statement -> execute();
}
else if ( $_POST[ "action" ] == 3 ) // Changing the token
{
	include ( "./generate_token.php" );

	// Generate the new token
	$_POST[ "token" ] = generate_token();
}
?>