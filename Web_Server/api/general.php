<?php

function general_get_sub_playlists( $user_id )
{
	include "connection/sleds_connect.php";

	// Request for all the sub_playlists of the user
	$statement = $sleds_database -> prepare( "SELECT sub_playlist.id, sub_playlist.name FROM sub_playlist JOIN playlist ON sub_playlist.id_playlist=playlist.id JOIN relation_user_cluster ON relation_user_cluster.id_cluster=playlist.id_cluster WHERE relation_user_cluster.id_user=?" );
	$statement -> bind_param( "i", $user_id );
	$statement -> execute();
	$result = $statement -> get_result();

	if ( mysqli_num_rows( $result ) <= 0 )
	{
		echo '{ "Error" : "Nothing Found" }';
		die();
	}

	// Print the result with a json format
	echo '{ "sub_playlists" : [';
	
	for ( $i = 0; $i < mysqli_num_rows( $result ); $i++ )
	{
		echo json_encode( $result -> fetch_assoc() );
		
		if ( $i < mysqli_num_rows( $result ) - 1 )
			echo ",";
	}

	echo "]}";

	die();
}

function general_get_animations_of_sub_playlist( $user_id )
{
	// Check that the subplaylist id is passed
	if ( !isset( $_GET[ "subplaylist_id" ] ) )
	{
		echo '{ "Error": "No sub-playlist id passed" }';
		die();
	}

	include "connection/sleds_connect.php";

	// Check that the subplaylist is owned by the user
	$statement = $sleds_database -> prepare( "SELECT sub_playlist.id FROM sub_playlist JOIN playlist ON sub_playlist.id_playlist=playlist.id JOIN relation_user_cluster ON relation_user_cluster.id_cluster=playlist.id_cluster WHERE relation_user_cluster.id_user=? AND sub_playlist.id=?" );
	$statement -> bind_param( "ii", $user_id, $_GET[ "subplaylist_id" ] );
	$statement -> execute();
	$result = $statement -> get_result();

	if ( mysqli_num_rows( $result ) <= 0 )
	{
		echo '{ "Error": "Sub-playlist not found" }';
		die();
	}

	// Request for all the sub_playlists of the user
	$statement = $sleds_database -> prepare( "SELECT animation.id, animation.name FROM animation JOIN relation_animation_sub_playlist ON relation_animation_sub_playlist.id_animation=animation.id WHERE relation_animation_sub_playlist.id_sub_playlist=?" );
	$statement -> bind_param( "i", $_GET[ "subplaylist_id" ] );
	$statement -> execute();
	$result = $statement -> get_result();

	if ( mysqli_num_rows( $result ) <= 0 )
	{
		echo '{ "Error" : "Nothing Found" }';
		die();
	}

	// Print the result with a json format
	echo '{ "animations" : [';
	
	for ( $i = 0; $i < mysqli_num_rows( $result ); $i++ )
	{
		echo json_encode( $result -> fetch_assoc() );
		
		if ( $i < mysqli_num_rows( $result ) - 1 )
			echo ",";
	}

	echo "]}";

	die();
}

// Relatation array request-code -> response-function
$general_codes = [
	0x2001 => "general_get_sub_playlists",
	0x2003 => "general_get_animations_of_sub_playlist"
];
?>