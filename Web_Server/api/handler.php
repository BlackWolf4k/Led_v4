<?php
	// Main function to handle the request of a client
	function handle_request()
	{
		// Set the header content type as json
		header( "Content-Type:application/json" );

		// Check the values of the request
		if ( !isset( $_GET[ "code" ] ) || !isset( $_GET[ "token" ] ) )
		{
			echo '{ "Error": "Invalid Code or Token" }';
			// Not all values needed are found
			die();
		}

		// Connect to the database
		include "connection/sleds_connect.php";

		// Check the token
		$statement = $sleds_database -> prepare( "SELECT * FROM user WHERE token LIKE ?" );
		$statement -> bind_param( "s", $_GET[ "token" ] );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check if a user was found
		if ( mysqli_num_rows( $result ) <= 0 ) // Token is wrong
		{
			// Return error json
			echo '{ "Error": "Invalid Code or Token" }';
			die();
		}

		while ( $row = $result -> fetch_assoc() )
		{
			// Check if the selected board is owned by this user
			$statement = $sleds_database -> prepare( "SELECT * FROM light JOIN relation_user_cluster ON light.id_cluster=relation_user_cluster.id_cluster WHERE id_board=? AND relation_user_cluster.id_user=?" );
			$statement -> bind_param( "ii", $_GET[ "board_id" ], $row[ "id" ] );
			$statement -> execute();
			$board_result = $statement -> get_result();

			// Check if a board was found
			if ( mysqli_num_rows( $board_result ) <= 0 ) // No board found
			{
				// Return error json
				echo '{ "Error": "Invalid Code or Token" }';
				die();
			}
		}

		include "responses.php";

		// Decide what to respond
		$codes_responses[ $_GET[ "code" ] ]();
	}

	// Make a request to the board
	function make_request( $request_code )
	{
		include "requests.php";

		// Run the request function
		$codes_requests[ $request_code ]();
	}
?>