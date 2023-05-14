<?php
	// Main function to handle the request of a client
	function handle_request()
	{
		// Set the header content type as json
		header( "Content-Type:application/json" );

		// Include the responses files
		include "board_server.php";
		include "general.php";
		include "board_insert.php";

		// Check the code of the request
		// There must always be a code
		if ( !isset( $_GET[ "code" ] ) )
		{
			echo '{ "Error": "Invalid Code or Token" }'; // never tell if the token is incorrect
			die();
		}

		// Check that the code is rapresenting any request
		if ( !is_in_array( $_GET[ "code" ], $board_server_codes ) && !is_in_array( $_GET[ "code" ], $general_codes ) && !is_in_array( $_GET[ "code" ], $board_insert_codes ) ) // Code is wrong
		{
			// Return error json
			echo '{ "Error": "Invalid Code or Token" }'; // never tell if the token is incorrect
			die();
		}

		if ( !isset( $_GET[ "token" ] ) )
		{
			// Check if trying to insert a board
			if ( isset( $_GET[ "insert_board" ] ) && is_in_array( $_GET[ "code" ], $board_insert_codes ) )
			{
				session_start();

				// A user is trying to add a new animation
				// Check if signed in
				if ( isset( $_SESSION[ "user_id" ] ) )
				{
					// Call the function to add the board
					$board_insert_codes[ $_GET[ "code" ] ]( $_GET[ "insert_board" ], $_SESSION[ "user_id" ] );
				}
				else
				{
					// Redirect the user to the login page
					header( "Location: /sign/signin.php?insert_board=" . $_GET[ "insert_board" ] ); // transmit the id of the board so that after login the user will be retrasmitted here
				}
			}
			else // Exit
			{
				// Return error json
				echo '{ "Error": "Invalid Code or Token" }'; // never tell if the token is incorrect
				die();
			}
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
			echo '{ "Error": "Invalid Code or Token" }'; // never tell if the token is incorrect
			die();
		}

		// Get the user that is making the request
		$user = $result -> fetch_assoc();

		// Check if a general request
		if ( is_in_array( $_GET[ "code" ], $general_codes ) )
		{
			// Make the general request
			// The user id is passed in order to make less query to the database
			$general_codes[ $_GET[ "code" ] ]( $user[ "id" ] );
		}

		// Check if the selected board is owned by this user
		$statement = $sleds_database -> prepare( "SELECT * FROM light JOIN relation_user_cluster ON light.id_cluster=relation_user_cluster.id_cluster WHERE id_board=? AND relation_user_cluster.id_user=?" );
		$statement -> bind_param( "ii", $_GET[ "board_id" ], $user[ "id" ] );
		$statement -> execute();
		$board_result = $statement -> get_result();

		// Check if a board was found
		if ( mysqli_num_rows( $board_result ) <= 0 ) // No board found
		{
			// Return error json
			echo '{ "Error": "Invalid Code or Token" }'; // never tell if the token is incorrect
			die();
		}

		// Decide what to respond
		$board_server_codes[ $_GET[ "code" ] ]();
	}

	// Make a request to the board
	function make_request( $request_code )
	{
		include "requests.php";

		// Run the request function
		$codes_requests[ $request_code ]();
	}

	// Custom in_array function
	// Is it good enough?
	/*function is_in_array( $key, $array )
	{
		if ( $array[ $key ] == null )
			return false;
		return true;
	}*/
?>