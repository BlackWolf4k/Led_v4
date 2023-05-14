<?php
	// session_save_path( "/var/www/sessions" ); // remove this in other servers
	session_start();

	/* Error values
		-0: No animation passed
		-1: Some values are wrong
		-2: Pattern not found
		-3: Body does not match phasesfilter
		-4: Wrong number of phases
		-5: Wrong body
		-6: Wrong filename
	*/

	if ( !isset( $_SESSION[ "user_id" ] ) )
	{
		header( "Location: /sign/signin.php" );
		die();
	}

	// Check that all the animation values needed are setted
	if ( !isset( $_SESSION[ "animation" ] ) || !isset( $_POST[ "animation_name" ] ) || !isset( $_POST[ "animation_sub_playlist" ] ) )
	{
		header( "Location: /home/animation_editor/home.php?error=0" );
		die();
	}

	// Decode the animation json as associative array
	$animation = json_decode( $_SESSION[ "animation" ], true );

	// Store the name in the json
	$animation[ "descriptor" ][ "name" ] = $_POST[ "animation_name" ];

	// Delete the animation from the session
	unset( $_SESSION[ "animation" ] );

	// Check that the animation data are all correct
	check_animation( $animation );

	// Connect to the database
	include "../../connection/sleds_connect.php";

	// Get the user name
	$statement = $sleds_database -> prepare( "SELECT username FROM user WHERE id=?" );
	$statement -> bind_param( "i", $_SESSION[ "user_id" ] );
	$statement -> execute();
	$result = $statement -> get_result();

	// Check that the id exists
	if ( mysqli_num_rows( $result ) <= 0 ) // Should never get inside here
	{
		header( "Location: /sign/signin.php" ); // Something strange happened
		die();
	}

	$result = $result -> fetch_assoc();

	// The timestamp is used as filename to avoid concurrency problems
	// The location for the writing operations
	$animation_filename = "../../users/" . $result[ "username" ] . "/" . strval( time() ) . ".dat";

	// The location for the database
	$animation_file_location = $result[ "username" ] . "/" . strval( time() ) . ".dat";

	$binary_animation = "";

	// Convert the animation body to his binary equivalent
	for ( $i = 0; $i < $animation[ "descriptor" ][ "phases" ]; $i++ )
		for ( $j = 0; $j < $animation[ "descriptor" ][ "pixels" ]; $j++ )
			for ( $k = 0; $k < 3; $k++ )
				$binary_animation .= pack( 'C', $animation[ "body" ][ $i ][ $j ][ $k ] ); // Used unsigned char as uint8
	
	// Store the binary animation body in a file
	file_put_contents( $animation_filename, $binary_animation );

	// Get the animation playlist
	$statement = $sleds_database -> prepare( "SELECT sub_playlist.id_playlist FROM sub_playlist WHERE sub_playlist.id=?" );
	$statement -> bind_param( "i", $_POST[ "animation_sub_playlist" ] );
	$statement -> execute();
	$result = $statement -> get_result();

	// Check that a playlist was found
	if ( mysqli_num_rows( $result ) <= 0 ) // This shoul never happen
	{
		header( "Location: /sign/signin.php" ); // Something strange happened
		die();
	}

	$result = $result -> fetch_assoc();

	// Store the id of the playlist
	$id_playlist = $result[ "id_playlist" ];

	// Use transaction to ensure that both data were inserted
	$sleds_database -> begin_transaction();

	try
	{
		// Store the animation
		$statement = $sleds_database -> prepare( "INSERT INTO `animation` ( `id_pattern`, `id_playlist`, `name`, `leds_number`, `phases`, `delay`, `repeat`, `file_name` ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )" );
		$statement -> bind_param( "iisiiiis", $animation[ "descriptor" ][ "pattern" ],
											  $id_playlist,
											  $animation[ "descriptor" ][ "name" ],
											  $animation[ "descriptor" ][ "pixels" ],
											  $animation[ "descriptor" ][ "phases" ],
											  $animation[ "descriptor" ][ "delay" ],
											  $animation[ "descriptor" ][ "repetitions" ],
											  $animation_file_location );
		$statement -> execute();

		// Get the id of the animation just inserted
		$inserted_animation_id = $statement -> insert_id;

		// Insert the animation in the sub playlist
		$statement = $sleds_database -> prepare( "INSERT INTO `relation_animation_sub_playlist` ( `id_animation`, `id_sub_playlist` ) VALUES ( ?, ? )" );
		$statement -> bind_param( "ii", $inserted_animation_id, $_POST[ "animation_sub_playlist" ] );
		$statement -> execute();

		// If no excpetion was thrown everything went fine
		// Commit
		$sleds_database -> commit();
	}
	catch ( mysqli_sql_exception $exception )
	{
		// Roll back before transaction
		$sleds_database -> rollback();

		throw $exception;
	}

	// Everything was fine
	header( "Location: /home/home.php" );
	die();

	// Check an animation json
	// Returns to the home page of animation editor in case of error reporting the error value
	function check_animation( $animation )
	{
		// Connect to the database
		include "../../connection/sleds_connect.php";

		// Animation json values that has to be checked
		$animation_descriptor = [ "pixels", "pattern", "phases", "delay", "repetitions" ];

		// Check the animation descriptor
		foreach ( $animation_descriptor as $value )
		{
			if ( $animation[ "descriptor" ][ $value ] <= 0 )
			{
				header( "Location: /home/animation_editor/home.php?error=" . $value );
				die();
			}

			if ( $value == "repetitions" && $animation[ "descriptor" ][ $value ] > 255 )
			{
				header( "Location: /home/animation_editor/home.php?error=1" );
				die();
			}
		}

		// Check that the animation name was passed
		if ( empty( $animation[ "descriptor" ][ "name" ] ) )
		{
			header( "Location: /home/animation_editor/home.php?error=6" );
			die();
		}

		// Check that the pattern exists
		$statement = $sleds_database -> prepare( "SELECT * FROM pattern WHERE id=?" );
		$statement -> bind_param( "i", $animation[ "descriptor" ][ "pattern" ] );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check that the query returned something
		if ( mysqli_num_rows( $result ) <= 0 )
		{
			header( "Location: /home/animation_editor/home.php?error=2" );
			die();
		}

		// Check the animation body
		// Check that the number of phases is the same in the body
		if ( count( $animation[ "body" ] ) != $animation[ "descriptor" ][ "phases" ] )
		{
			header( "Location: /home/animation_editor/home.php?error=3" );
			die();
		}
		
		// Check that the number of leds per phases is correct
		for ( $i = 0; $i < $animation[ "descriptor" ][ "phases" ]; $i++ )
			if ( count( $animation[ "body" ][ $i ] ) != $animation[ "descriptor" ][ "pixels" ] )
			{
				header( "Location: /home/animation_editor/home.php?error=4" );
				die();
			}
		
		include( "./pattern_check.php" );
		
		// Check that the animation body matches the pattern
		if ( !$pattern_check[ $animation[ "descriptor" ][ "pattern" ] ]( $animation ) )
		{
			header( "Location: /home/animation_editor/home.php?error=5" );
			die();
		}
	}
?>