<?php
function check_new_board( $board_code, $user_id )
{
	if ( $board_code != "" )
	{
		// Connect to the database of production
		include "connection/sleds_production_connect.php";
		include "connection/sleds_connect.php";

		// Check that the board code is in the producted boards and that it is not already paired
		$statement = $sleds_production_database -> prepare( "SELECT paired, id FROM board WHERE code=?" );
		$statement -> bind_param( "i", $board_code );
		$statement -> execute();
		$result = $statement -> get_result();

		// No board found
		if ( mysqli_num_rows( $result ) <= 0 )
		{
			echo '{ "Error": "Invalid Code" }';
			die();
		}

		$board = $result -> fetch_assoc();

		// Board already paired
		if ( $board[ "paired" ] == 1 )
		{
			echo '{ "Error": "Board already Paired" }';
			die();
		}

		// Get the user first cluster
		$statement = $sleds_database -> prepare( "SELECT id_cluster FROM relation_user_cluster WHERE id_user=?" );
		$statement -> bind_param( "i", $user_id );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check that the user has clusters
		if ( mysqli_num_rows( $result ) <= 0 )
		{
			echo '{ "Error": "User has no groups" }';
			die();
		}

		// Store the id of the first cluster
		$first_cluster_id = ( $result -> fetch_assoc() )[ "id_cluster" ];

		// Get the user first sub playlist
		$statement = $sleds_database -> prepare( "SELECT sub_playlist.id FROM sub_playlist WHERE sub_playlist.id_playlist IN ( SELECT playlist.id FROM playlist JOIN relation_user_cluster ON relation_user_cluster.id_cluster=playlist.id_cluster WHERE relation_user_cluster.id_user=? )" );
		$statement -> bind_param( "i", $user_id );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check that the user has sub playlists
		if ( mysqli_num_rows( $result ) <= 0 )
		{
			echo '{ "Error": "User has no playlists" }';
			die();
		}

		// Store the id of the first sub playlist
		$first_sub_playlist_id = ( $result -> fetch_assoc() )[ "id" ];

		// Get the user first animation of the first sub playlist
		$statement = $sleds_database -> prepare( "SELECT relation_animation_sub_playlist.id_animation FROM relation_animation_sub_playlist WHERE relation_animation_sub_playlist.id_sub_playlist=?" );
		$statement -> bind_param( "i", $first_sub_playlist_id );
		$statement -> execute();
		$result = $statement -> get_result();

		// Check that the user has animations
		if ( mysqli_num_rows( $result ) <= 0 )
		{
			echo '{ "Error": "User has no animations" }';
			die();
		}

		// Store the id of the first animation
		$first_animation_id = ( $result -> fetch_assoc() )[ "id_animation" ];

		// Use transaction to ensure that both data were inserted
		$sleds_production_database -> begin_transaction();
		$sleds_database -> begin_transaction();

		$light_id = 0;

		try
		{
			// Lock the board production table
			$sleds_production_database -> query( "LOCK TABLE board WRITE" );

			// Change the paired value of the board
			$statement = $sleds_production_database -> prepare( "UPDATE board SET paired=1 WHERE id=?" );
			$statement -> bind_param( "i", $board[ "id" ] );
			$statement -> execute();

			// Unlock the board production table
			$sleds_production_database -> query( "UNLOCK TABLE board WRITE" );

			// Insert the board
			$statement = $sleds_database -> prepare( "INSERT INTO `board`(`id`, `leds_number`, `notify`) VALUES ( ?, 0, 0 )" );
			$statement -> bind_param( "i", $board[ "id" ] );
			$statement -> execute();

			// Insert the light to the user first cluster
			$statement = $sleds_database -> prepare( "INSERT INTO `light`( `id_board`, `name`, `id_cluster`, `id_animation`, `id_sub_playlist`) VALUES ( ?, 'New Light', ?, ?, ? )" );
			$statement -> bind_param( "iiii", $board[ "id" ], $first_cluster_id, $first_animation_id, $first_sub_playlist_id );
			$statement -> execute();

			$light_id = $statement -> insert_id;

			// If no excpetion was thrown everything went fine
			// Commit
			$sleds_production_database -> commit();
			$sleds_database -> commit();
		}
		catch ( mysqli_sql_exception $exception )
		{
			// Roll back before transaction
			$sleds_production_database -> rollback();
			$sleds_database -> rollback();

			throw $exception;
		}

		// Check that a light was inserted
		if ( $light_id != 0 )
		{
			// Go to light animation settings page
			header( "Location: /home/light/settings.php?light_id=" . $light_id );
			die();
		}

		// If here something went wrong
		die();
	}
}

// Relatation array request-code -> response-function
$board_insert_codes = [
	0x3001 => "check_new_board"
];
?>