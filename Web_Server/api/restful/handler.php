<?php

// Functions for the animations
function get_animation( $id )
{
	// Connect to the database
	include "../connection/sleds_connect.php";

	// Get the animation and check the token
	$statement = $sleds_database -> prepare( "SELECT animation.id, animation.name, animation.leds_number, animation.phases, animation.delay, animation.repeat, playlist.name as playlist_name, pattern.name as pattern_name FROM animation JOIN playlist ON animation.id_playlist=playlist.id JOIN pattern ON pattern.id=animation.id_pattern JOIN relation_user_cluster ON playlist.id_cluster=relation_user_cluster.id_cluster JOIN user ON relation_user_cluster.id_user=user.id WHERE animation.id=? AND user.token=?" );
	$statement -> bind_param( "is", $id, $GLOBALS[ "auth" ] );
	$statement -> execute();
	$result = $statement -> get_result();

	// Check if something returned
	if ( mysqli_num_rows( $result ) <= 0 )
	{
		echo '{ "Error": "No animation found" }';
		die();
	}
	
	// Print the animation as json
	echo json_encode( ( $result -> fetch_assoc() ) );
	die();
}

function delete_animation( $id )
{
	// Connect to the database
	include "../connection/sleds_connect.php";

	// Delete the animation and check the token
	$statement = $sleds_database -> prepare( "DELETE nmtn FROM animation nmtn JOIN playlist ON playlis.id=animation.id_playlist JOIN relation_user_cluster ON relation_user_cluster.id_cluster=playlist.id_cluster JOIN user ON user.id=relation_user_cluster.id_user WHERE nmtn.id=? AND user.token=?" );
	$statement -> bind_param( "is", $id, $GLOBALS[ "auth" ] );
	$statement -> execute();
	$result = $statement -> affected_rows();

	// Check if something returned
	if ( $result <= 0 )
	{
		echo '{ "Error": "No animation found" }';
		die();
	}

	echo '{ "Success": "Animation deleted" }';
}

// Functions for the boards
function get_board( $id )
{
	// Connect to the database
	include "../connection/sleds_connect.php";

	// Get the board and check the token
	$statement = $sleds_database -> prepare( "SELECT light.id as light_id, board.id as board_id, board.leds_number, light.name as light_name, cluster.name as cluster_name, sub_playlist.name as sub_playlist_name, animation.id as animation_id FROM board JOIN light ON board.id=light.id_board JOIN cluster ON cluster.id=light.id_cluster JOIN animation ON animation.id=light.id_animation JOIN sub_playlist ON sub_playlist.id=light.id_sub_playlist JOIN relation_user_cluster ON relation_user_cluster.id_cluster=light.id_cluster JOIN user ON relation_user_cluster.id_user=user.id WHERE board.id=? AND user.token=?" );
	$statement -> bind_param( "is", $id, $GLOBALS[ "auth" ] );
	$statement -> execute();
	$result = $statement -> get_result();

	// Check if something returned
	if ( mysqli_num_rows( $result ) <= 0 )
	{
		echo '{ "Error": "No board found" }';
		die();
	}
	
	// Print the animation as json
	echo json_encode( ( $result -> fetch_assoc() ) );
	die();
}

function add_board( $board_code )
{
	// Connect to the database
	include "../connection/sleds_connect.php";

	/// Get the id of the user
	$statement = $sleds_database -> prepare( "SELECT user.id FROM user WHERE user.token LIKE ?" );
	$statement -> bind_param( "s", $GLOBALS[ "auth" ] );
	$statement -> execute();
	$result = $statement -> get_result();

	// Check if a user was found
	if ( mysqli_num_rows( $result ) <= 0 ) // Token is wrong
	{
		// Return error json
		echo '{ "Error": "Not authorized" }';
		die();
	}

	// Get the user that is making the request
	$user = $result -> fetch_assoc();

	include "board_insert.php";

	$board_insert_codes[ 0x3001 ]( $board_code, $_SESSION[ "user_id" ] );
}

function update_board()
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
			echo '{ "Error": "Not all needed values where passed" }';
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
		echo '{ "Error": "Not authorized" }';
		die();
	}

	// Change the informations about the board
	$statement = $sleds_database -> prepare( "UPDATE board SET leds_number=? WHERE id_board=?" );
	$statement -> bind_param( "ii", $_POST[ "leds" ], $_POST[ "board_id" ] );
	$statement -> execute();

	echo '{ "Success": "Successfully updated animation" }';
	die();
}

$GLOBALS[ "calls" ] = $restful_calls = [
	"animation" => [
		"get" => "get_animation",
		// "add" => "add_animation", // this should not be implemented
		"delete" => "delete_animation"
		// "update" => "update_animation" // this should not be implemented
	],
	"board" => [
		"get" => "get_board",
		"add" => "add_board",
		"update" => "update_board",
		// "delete" => "delete_board" // this should not be implemented
	]
];

// Ensure that the funciton 
function is_restful_call( $url )
{
	$uri = parse_url( $url, PHP_URL_PATH );
	$uri = explode( "/", $uri );

	$uri_length = count( $uri ) - 1;

	$offset = 1;

	// Must be at least 2 long and max 3 long
	// /board/add ( 2 length )
	// /board/get/1 ( 3 length )
	if ( $uri_length < 2 || $uri_length > 3 )
		return false;

	// The first must indicate the type of element
	if ( !is_in_array( $uri[ $offset + 0 ], $GLOBALS[ "calls" ] ) )
		return false;

	// Check that is a request valid for that element
	if ( !is_in_array( $uri[ $offset + 1 ], $GLOBALS[ "calls" ][ $uri[ $offset + 0 ] ] ) )
		return false;
	
	// Check if asking for a id and it is not specified
	if ( ( $uri[ $offset + 1 ] == "get" || $uri[ $offset + 1 ] == "delete" ) && $uri_length == 2 )
		return false;
	
	// Everything is fine
	return true;
}

function handle_resteful_request( $url )
{
	$uri = parse_url( $url, PHP_URL_PATH );
	$uri = explode( "/", $uri );

	$uri_length = count( $uri ) - 1;

	$offset = 1;

	// Call the handling function
	if ( $uri_length == 2 )
		$GLOBALS[ "calls" ][ $uri[ $offset + 0 ] ][ $uri[ $offset + 1 ] ]();
	else if ( $uri_length == 3 )
		$GLOBALS[ "calls" ][ $uri[ $offset + 0 ] ][ $uri[ $offset + 1 ] ]( $uri[ $offset + 2 ] );
}

function is_in_array( $key, $array )
{
	if ( $array[ $key ] == null )
		return false;
	return true;
}
?>