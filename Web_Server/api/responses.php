<?php
	class animation_descriptor
	{
		public $leds;
		public $phases;
		public $delay;
		public $repeat;
		public $pattern;
	}

	class animation
	{
		public $animation_descriptor;
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
		$animation -> animation_descriptor = $animation_descriptor;

		// Change light actual animation id
		$statement = $sleds_database -> prepare( "UPDATE light SET id_animation=?" );
		$statement -> bind_param( "i", $next_animation_id );
		$statement -> execute();

		// Read the animation path
		$animation_filename = "./animations/" . $row[ "file_name" ];
		
		// Check the esistence of the file
		if ( file_exists( $animation_filename ) )
		{
			// Open the file
			$animation_file = fopen( $animation_filename, "rb" );

			// Read the file content
			$animation -> body = fread( $animation_file, filesize( $animation_filename ) );

			// Close the file
			fclose( $animation_file );
		}
		else // The file was not found
		{
			// Return an error code
			echo "{}";
		}

		// Return the json containing the response
		echo json_encode( get_object_vars( $animation ) );
	}

	function response_sync_server_board()
	{}

	// Relatation array request-code -> response-function
	$codes = [
		0x0001 => "response_server_board",
		0x0003 => "response_sync_server_board"
	];
?>