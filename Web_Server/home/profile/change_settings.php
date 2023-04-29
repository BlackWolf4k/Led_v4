<?php
session_save_path( "/var/www/sessions" ); // remove this in other servers
session_start();

// Check the user session
if ( !isset( $_SESSION[ "user_id" ] ) ) // The user is not signed in
{
	header( "Location: /sign/signin.php" );
	die();
}

// All the form values that need to be setted
$values_to_check = [ "action", "email", "username", "old_password", "new_password", "new_password_confirm", "token" ];

foreach( $values_to_check as &$value )
{
	// Check that all the values are setted
	if ( !isset( $_POST[ $value ] ) )
	{
		header( "Location: ./settings.php?error=0" );
		die();
	}
}
// For some values use the filter var
// Check if really an email
if ( !filter_var( $_POST[ "email" ], FILTER_VALIDATE_EMAIL ) )
{
	header( "Location: ./settings.php?error=0" );
	die();
}

include "../../connection/sleds_connect.php";

// Check the values that need to be changed
// Check that the user name is valid
$statement = $sleds_database -> prepare( "SELECT username, email FROM user WHERE ( username=? OR email=? ) AND id NOT ?" );
$statement -> bind_param( "ssi", $_POST[ "username" ], $_POST[ "email" ], $_SESSION[ "user_id" ] );
$statement -> execute();
$result = $statement -> get_result();

// Check if something is wrong
if ( mysqli_num_rows( $result ) > 0 )
{
	header( "Location: ./settings.php?error=1" );
	die();
}

// Check if changin the token
if ( $_POST[ "action" ] == 3 )
{
	include ( "./generate_token.php" );

	// Generate the new token
	$_POST[ "token" ] = generate_token();
}

// Update the users settings
$statement = $sleds_database -> prepare( "UPDATE user SET username=?, password=?, email=?, token=? WHERE id=?" );
$password = password_hash( $_POST[ "password" ], PASSWORD_DEFAULT );
$statement -> bind_param( "ssssi", $_POST[ "username" ], $password, $_POST[ "email" ], $_POST[ "token" ], $_SESSION[ "user_id" ] );
$statement -> execute();
?>