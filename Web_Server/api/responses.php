<?php
	class animation_descriptor
	{
		public $leds;
		public $phases;
		public $delay;
		public $repeat;
		public $pattern;
		public $name;
	}

	class animation
	{
		public $descriptor;
		public $body;
	}

	function response_server_board()
	{
		// Conntect to the database
		include "connection/sleds_connect.php";

		// Change content type to json
		header( "Content-Type:application/json" );

		$animation = new animation();

		// Get the light sub_playlist and actual animation id from the board id
		$statement = $sleds_database -> prepare( "SELECT id_animation, id_sub_playlist FROM light WHERE id_board=?" );
		$statement -> bind_param( "i", $_GET[ "board_id" ] );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check if there was output
		if ( mysqli_num_rows( $result ) <= 0 ) // The biard was not found
		{
			// Return error code
			echo "{}";
			die();
		}

		// Store the informations
		$row = $result -> fetch_assoc();
		$actual_animation_id = $row[ "id_animation" ];
		$subplaylist_id = $row[ "id_sub_playlist" ];

		// Get all the animations of the subplaylist
		$statement = $sleds_database -> prepare( "SELECT id_animation FROM relation_animation_sub_playlist WHERE id_sub_playlist=?" );
		$statement -> bind_param( "i", $subplaylist_id );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check if there was output
		if ( mysqli_num_rows( $result ) <= 0 ) // The animation was not found
		{
			// Return error code
			echo "{}";
			die();
		}

		$next_animation_id = 0;

		// Get the next animation id
		if ( mysqli_num_rows( $result ) == 1 ) // Just one row so repeat the animation
		{
			$next_animation_id = $actual_animation_id;
		}
		else // More rows to check
		{
			$found = 0;
			
			/*****************************************************************
			 ********************|||***CHECK*THIS***|||***********************
			*********************vvv****************vvv**********************/
			// Find the following animation
			for ( $i = 0; $i < mysqli_num_rows( $result ); $i++ )
			{
				$row = $result -> fetch_assoc();

				if ( $i == 0 || $found ) // Ensure the circularity of the result
				{
					// Set the id of the next animation
					$next_animation_id = $row[ "id_animation" ];

					// Exit if the previous animation id was the actual animation id
					if ( $found )
						break;
				}

				// Check if the actual row contains the actual animation id
				if ( $row[ "id_animation" ] == $actual_animation_id )
				{
					$found = 1;
				}
			}
		}

		// Get the next animation informations
		$statement = $sleds_database -> prepare( "SELECT * FROM animation WHERE id=?" );
		$statement -> bind_param( "i", $next_animation_id );
		$statement -> execute();
		$result = $statement -> get_result();
		$row = $result -> fetch_assoc();

		// Save the data of the animation descriptor
		$animation_descriptor = new animation_descriptor();
		$animation_descriptor -> leds = $row[ "leds_number" ];
		$animation_descriptor -> phases = $row[ "phases" ];
		$animation_descriptor -> delay = $row[ "delay" ];
		$animation_descriptor -> repeat = $row[ "repeat" ];
		$animation_descriptor -> pattern = $row[ "id_pattern" ];
		$animation_descriptor -> name = $row[ "name" ];
		$animation -> descriptor = $animation_descriptor;

		// Change light actual animation id
		$statement = $sleds_database -> prepare( "UPDATE light SET id_animation=? WHERE id_board=?" );
		$statement -> bind_param( "ii", $next_animation_id, $_GET[ "board_id" ] );
		$statement -> execute();

		// Read the animation path
		$animation_filename = "./users/" . $row[ "file_name" ];

		// Check the esistence of the file
		if ( file_exists( $animation_filename ) )
		{
			// Store the size of the file
			$file_size = filesize( $animation_filename );

			// Open the file
			$animation_file = fopen( $animation_filename, "rb" );

			// Read the file content
			$binary_body = fread( $animation_file, $file_size );

			// Convert the body content
			$unsplitted_body = unpack( sprintf( 'C%d', $file_size ), $binary_body );

			// Split the animation body in phases
			// $animation -> body = array_chunk( $unsplitted_body, $animation -> descriptor -> leds * 3 );
			$splitted_body = array_chunk( $unsplitted_body, $animation -> descriptor -> leds * 3 );

			// Split the leds color in 3 bytes array ( RGB )
			for ( $i = 0; $i < $animation -> descriptor -> phases; $i++ )
				$animation -> body[ $i ] = array_chunk( $splitted_body[ $i ], 3 );

			// Close the file
			fclose( $animation_file );
		}
		else // The file was not found
		{
			// Return an error code
			echo "{}";
			die();
		}

		// Return the json containing the response
		// var_dump( get_object_vars( $animation ) );
		echo json_encode( get_object_vars( $animation ) );
	}

	function response_sync_board_server()
	{
		// The values that a board asks to change
		$sync_values = Array( "board_id", "leds", "token" );

		// Change content type to json
		header( "Content-Type:application/json" );

		// Check that all the values where passed
		while ( $key = $sync_values )
		{
			// Check that the value is setted
			if ( !isset( $_POST[ $key ] ) ) // One values was not setted
			{
				// Return empty json as error
				echo "{}";
				die();
			}
		}

		// Conntect to the database
		include "connection/sleds_connect.php";

		// Check that the token is valid
		$statement = $sleds_database -> prepare( "SELECT token FROM user JOIN relation_user_cluster ON user.id=relation_user_cluster.id_user JOIN cluster ON relation_user_cluster.id_cluster=cluster.id WHERE cluster.id=( SELECT id_cluster FROM light WHERE id_board=? ) AND user.token=?" );
		$statement -> bind_param( "is", $_POST[ "board_id" ], $_POST[ "token" ] );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check that something was returned
		if ( mysqli_num_rows( $result ) <= 0 ) // Nothing returned ( board id or token are wrong )
		{
			// Return empty json as error
			echo "{}";
			die();
		}

		// Change the informations about the board
		$statement = $sleds_database -> prepare( "UPDATE board SET leds_number=? WHERE id_board=?" );
		$statement -> bind_param( "ii", $_POST[ "leds" ], $_POST[ "board_id" ] );
		$statement -> execute();

		// Should return the board changed informations?
	}

	/*function response_sync_board_server()
	{
		// The values that a board asks to change
		$sync_values = Array( "board_id", "leds", "group", "sub_playlist", "token" );

		// Change content type to json
		header( "Content-Type:application/json" );

		// Check that all the values where passed
		while ( $key = $sync_values )
		{
			// Check that the value is setted
			if ( !isset( $_POST[ $key ] ) ) // One values was not setted
			{
				// Return empty json as error
				echo "{}";
				die();
			}
		}

		// Conntect to the database
		include "connection/sleds_connect.php";

		// Check that the token is valid
		$statement = $sleds_database -> prepare( "SELECT token FROM user JOIN relation_user_cluster ON user.id=relation_user_cluster.id_user JOIN cluster ON relation_user_cluster.id_cluster=cluster.id WHERE cluster.id=( SELECT id_cluster FROM light WHERE id_board=? ) AND user.token=?" );
		$statement -> bind_param( "is", $_POST[ "board_id" ], $_POST[ "token" ] );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check that something was returned
		if ( mysqli_num_rows( $result ) <= 0 ) // Nothing returned ( board id or token are wrong )
		{
			// Return empty json as error
			echo "{}";
			die();
		}

		// Change the informations about the board
		$statement = $sleds_database -> prepare( "UPDATE board SET leds_number=? WHERE id_board=?" );
		$statement -> bind_param( "ii", $_POST[ "leds" ], $_POST[ "board_id" ] );
		$statement -> execute();

		// Change the informations about the light
		$statement = $sleds_database -> prepare( "UPDATE light SET id_cluster=?, id_sub_playlist=? WHERE id_board=?" );
		$statement -> bind_param( "iii", $_POST[ "group" ], $_POST[ "playlist" ], $_POST[ "board_id" ] );
		$statement -> execute();

		// Should return the board changed informations?
	}*/

	function response_server_board_specific_animation()
	{
		// Conntect to the database
		include "connection/sleds_connect.php";

		// Change content type to json
		header( "Content-Type:application/json" );

		// Check if a name of animation was passed
		if ( !isset( $_GET[ "animation_name" ] ) || empty( $_GET[ "animation_name" ] ) )
		{
			echo '{ "Error" : "Plase specify an animation name" }';
			die();
		}

		$animation = new animation();

		// Get the light sub_playlist and actual animation id from the board id
		$statement = $sleds_database -> prepare( "SELECT id_sub_playlist FROM light WHERE id_board=?" );
		$statement -> bind_param( "i", $_GET[ "board_id" ] );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check if there was output
		if ( mysqli_num_rows( $result ) <= 0 ) // The board was not found
		{
			// Return error code
			echo "{}";
			die();
		}

		// Store the informations
		$row = $result -> fetch_assoc();
		$actual_animation_id = $row[ "id_animation" ];
		$subplaylist_id = $row[ "id_sub_playlist" ];

		// Check that the asked animation is in the subplaylist
		$statement = $sleds_database -> prepare( "SELECT id_animation FROM relation_animation_sub_playlist JOIN animation ON animation.id=relation_animation_sub_playlist.id_animation WHERE id_sub_playlist=? AND animation.name LIKE ?" );
		$statement -> bind_param( "is", $subplaylist_id, $_GET[ "animation_name" ] );
		$statement -> execute();
		$result = $statement -> get_result();
		$animation_id = ( $result -> fetch_assoc() )[ "id_animation" ];

		// Check if there was output
		if ( mysqli_num_rows( $result ) <= 0 ) // The animation was not found
		{
			// Return error code
			echo "{}";
			die();
		}

		// Get the next animation informations
		$statement = $sleds_database -> prepare( "SELECT * FROM animation WHERE id=?" );
		$statement -> bind_param( "i", $animation_id );
		$statement -> execute();
		$result = $statement -> get_result();
		$row = $result -> fetch_assoc();

		// Save the data of the animation descriptor
		$animation_descriptor = new animation_descriptor();
		$animation_descriptor -> leds = $row[ "leds_number" ];
		$animation_descriptor -> phases = $row[ "phases" ];
		$animation_descriptor -> delay = $row[ "delay" ];
		$animation_descriptor -> repeat = $row[ "repeat" ];
		$animation_descriptor -> pattern = $row[ "id_pattern" ];
		$animation -> descriptor = $animation_descriptor;

		// Change light actual animation id
		$statement = $sleds_database -> prepare( "UPDATE light SET id_animation=? WHERE id_board=?" );
		$statement -> bind_param( "ii", $animation_id, $_GET[ "board_id" ] );
		$statement -> execute();

		// Read the animation path
		$animation_filename = "./users/" . $row[ "file_name" ];

		// Check the esistence of the file
		if ( file_exists( $animation_filename ) )
		{
			// Store the size of the file
			$file_size = filesize( $animation_filename );

			// Open the file
			$animation_file = fopen( $animation_filename, "rb" );

			// Read the file content
			$binary_body = fread( $animation_file, $file_size );

			// Convert the body content
			$unsplitted_body = unpack( sprintf( 'C%d', $file_size ), $binary_body );

			// Split the animation body in phases
			// $animation -> body = array_chunk( $unsplitted_body, $animation -> descriptor -> leds * 3 );
			$splitted_body = array_chunk( $unsplitted_body, $animation -> descriptor -> leds * 3 );

			// Split the leds color in 3 bytes array ( RGB )
			for ( $i = 0; $i < $animation -> descriptor -> phases; $i++ )
				$animation -> body[ $i ] = array_chunk( $splitted_body[ $i ], 3 );

			// Close the file
			fclose( $animation_file );
		}
		else // The file was not found
		{
			// Return an error code
			echo "{}";
			die();
		}

		// Return the json containing the response
		// var_dump( get_object_vars( $animation ) );
		echo json_encode( get_object_vars( $animation ) );
	}

	function response_board_config()
	{
		// Conntect to the database
		include "connection/sleds_connect.php";

		// Change content type to json
		header( "Content-Type:application/json" );

		$statement = $sleds_database -> prepare( "SELECT board.id, light.name, board.leds_number, light.id_animation FROM light JOIN board ON board.id=light.id_board WHERE board.id=?" );
		$statement -> bind_param( "i", $_GET[ "board_id" ] );
		$statement -> execute();
		$result = $statement -> get_result();

		// Board id not found or something went wrong
		if ( mysqli_num_rows( $result ) <= 0 )
		{
			echo "{}";
			die();
		}
		else
		{
			// Convert the result to json and print it
			$board_config = $result -> fetch_assoc();
			echo json_encode( $board_config );
			die();
		}
	}

	// Relatation array request-code -> response-function
	$codes_responses = [
		0x0001 => "response_server_board", // http://192.136.60.75:81/?code=1&board_id=1&token=f49140e28dd84b5e329fb33f02970cbe21bf6edb
		0x0003 => "response_sync_board_server",
		0x0005 => "response_server_board_specific_animation", //
		0x0007 => "response_board_config" // http://192.136.60.75:81/?code=1&board_id=1&token=f49140e28dd84b5e329fb33f02970cbe21bf6edb
	];
?>