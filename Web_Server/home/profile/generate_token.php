<?php
	// Generate a random token
	// PARAMS ( none )
	// RETURNS ( none )
	function generate_token()
	{
		// Connect to the database
		include "../../connection/sleds_connect.php";

		$token = "";
		$statement = "";

		// Generate tokens
		do
		{
			// Generate 20 random bytes
			$phrase = random_bytes( 20 );

			// Generate the token
			$token = sha1( $phrase );

			// Checks that there are no two same tokens
			$statement = $sleds_database -> prepare( "SELECT token FROM user WHERE token=?" );
			$statement -> bind_param( "s", $token );
			$statement -> execute();
		} while ( mysqli_num_rows( $statement -> get_result() ) > 0 );

		// Insert the token in the database
		echo $token;
		$statement = $sleds_database -> prepare( "UPDATE user SET token=? WHERE id=?" );
		$statement -> bind_param( "si", $token, $_SESSION[ "user_id" ] );
		$statement -> execute();
	}
?>