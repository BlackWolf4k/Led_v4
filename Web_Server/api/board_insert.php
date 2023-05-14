<?php
function insert_new_board( $board_code, $user_id )
{
	if ( $board_code != "" )
	{
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
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

		// Use transaction to ensure that both data were inserted
		$sleds_production_database -> begin_transaction();
		$sleds_database -> begin_transaction();

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
			$statement = $sleds_database -> prepare( "INSERT INTO `light`( `id_board`, `name`, `id_cluster`, `id_animation`, `id_sub_playlist`) VALUES ( ?, 'New Light', ?, 0, 0 )" );
			$statement -> bind_param( "ii", $board[ "id" ], $first_cluster_id );
			$statement -> execute();

			// If no excpetion was thrown everything went fine
			// Commit
			$sleds_production_database -> commit();
			$sleds_database -> commit();
		}
		catch ( mysqli_sql_exception $exception )
		{
			echo "aaaaaa";
			// Roll back before transaction
			$sleds_production_database -> rollback();
			$sleds_database -> rollback();

			echo "iiiiiiii";
			//throw $exception;
		}

		// */
		die();
	}
}

// Relatation array request-code -> response-function
$board_insert_codes = [
	0x3001 => "insert_new_board"
];
?>