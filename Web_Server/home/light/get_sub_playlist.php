<?php
	session_save_path( "/var/www/sessions" ); // remove this in other servers
	session_start();

	if ( !isset( $_SESSION[ "user_id" ] ) )
	{
		// Go to sign in page
		header( "Location: /sign/signin.php" );
		die();
	}
	else if ( !isset( $_GET[ "group_id" ] ) )
	{
		// Go to the light settings page
		header( "Location: /home/light/change_settings.php" );
		die();
	}
	else
	{
		// Connect to the database
		include "../../connection/sleds_connect.php";

		// Get the subplaylist id and name
		$statement = $sleds_database -> prepare( "SELECT sub_playlist.id, sub_playlist.name FROM sub_playlist RIGHT JOIN playlist ON sub_playlist.id_playlist=playlist.id RIGHT JOIN cluster ON playlist.id_cluster=cluster.id WHERE cluster.id=?" );
		$statement -> bind_param( "i", $_GET[ "group_id" ] );
		$statement -> execute();
		$sub_playlist_result = $statement -> get_result();

		if ( mysqli_num_rows( $sub_playlist_result) <= 0 )
		{
			// Return error
			header( "404" );
			die();
		}

		while ( $sub_playlist = $sub_playlist_result -> fetch_assoc() )
			echo $sub_playlist[ "id" ] . ";" . $sub_playlist[ "name" ] . "\n";
	}
?>