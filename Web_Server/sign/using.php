<?php
session_save_path( "/var/www/sessions" ); // remove this in other servers
session_start();

// Check that the user is signed in
if ( !isset( $_SESSION[ "user_id" ] ) )
{
	header( "Location: /sign/signin.php" );
	die();
}

include "../connection/sleds_connect.php";

// Check if asking for the username already used ( ajax )
if ( isset( $_GET[ "is_username_already_in_use" ] ) )
{
	$statement = $sleds_database -> prepare( "SELECT username FROM user WHERE username LIKE ? AND id != ?" );
	$statement -> bind_param( "si", $_GET[ "is_username_already_in_use" ], $_SESSION[ "user_id" ] );
	$statement -> execute();
	$result = $statement -> get_result();

	if ( mysqli_num_rows( $result ) > 0 )
	{
		echo "true";
		die();
	}
	else
	{
		echo "false";
		die();
	}
}

// Check if asking for the email already used ( ajax )
if ( isset( $_GET[ "is_email_already_in_use" ] ) )
{
	$statement = $sleds_database -> prepare( "SELECT email FROM user WHERE email LIKE ? AND id != ?" );
	$statement -> bind_param( "si", $_GET[ "is_email_already_in_use" ], $_SESSION[ "user_id" ] );
	$statement -> execute();
	$result = $statement -> get_result();

	if ( mysqli_num_rows( $result ) > 0 )
	{
		echo "true";
		die();
	}
	else
	{
		echo "false";
		die();
	}
}
?>