<?php
	session_save_path( "/var/www/sessions" ); // remove this in other servers
	session_start();

	// Check that the needed variables are setted
	if ( !isset( $_SESSION[ "user_id" ] ) ) // Check if signed in
	{
		// Go to sign in page
		header( "Location: /sign/signin.php" );
		die();
	}
	else if ( !isset( $_GET[ "light_id" ] ) ) // Check if a board was selected
	{
		// Go to the home page
		header( "Location: /home/home.php" );
		die();
	}
	else // Everything is setted
	{
		// Connect to the database
		include "../../connection/sleds_connect.php";

		// SubPlaylists
		/*$statement = $sleds_database -> prepare( "" );
		$statement -> bind_param();
		$statement -> execute();
		$sub_playlist_result = $statement -> get_result();*/

		// Get light and board informations
		$statement = $sleds_database -> prepare( "SELECT * FROM light RIGHT JOIN board ON light.id_board=board.id WHERE light.id=?" );
		$statement -> bind_param( "i", $_GET[ "light_id" ] );
		$statement -> execute();
		$light_result = $statement -> get_result();

		// Check the resoults
		if ( mysqli_num_rows( $light_result ) != 1 )
		{
			// Something is wrong with the light id
			header( "Location: /home/home.php" );
			die();
		}

		$light_result = $light_result -> fetch_assoc();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel = "stylesheet" href = "./style/style.css" >
		<script src = "./script/ajax_script.js"></script> 
	</head>
	<body>
		<div class = "settings" >
			<form method = "post" action = "./change_settings.php" >
				<div class="form-outline mb-4">
					<label class="form-label" for="name">Light Name</label>
					<input type="text" id="name" class="form-control" value = "<?php echo $light_result[ "name" ]; ?>" />
				</div>
				<div class="form-outline mb-4">
					<label class="form-label" for="board_id">Number of Leds</label>
					<input type="number" id="board_id" class="form-control" value = "<?php echo $light_result[ "leds_number" ]; ?>" />
				</div>
				<div class="form-outline mb-4">
					<label class="form-label" for="group">Group</label>
					<select type="number" id="group" class="form-control" value = "Group" onchange = "change_sub_playlists( this.value )">
					<option disabled selected value></option>
					<?php
					// Get all the groups
					$statement = $sleds_database -> prepare( "SELECT * FROM cluster RIGHT JOIN relation_user_cluster ON relation_user_cluster.id_cluster=cluster.id WHERE relation_user_cluster.id_user=?" );
					$statement -> bind_param( "i", $_SESSION[ "user_id" ] );
					$statement -> execute();
					$group_result = $statement -> get_result();

					while ( $group = $group_result -> fetch_assoc() )
						echo '
						<option value="' . $group[ "id" ]. '">' . $group[ "name" ] . '</option>
						';
					?>
					</select>
				</div>
				<div class="form-outline mb-4">
					<label class="form-label" for="playlist">Playlist</label>
					<select type="number" id="playlist" class="form-control" value = "<?php?>">
					</select>
				</div>
				<button type="submit" class="btn btn-primary btn-block mb-4">Change</button>
			</form>
		</div>
	</body>
</html>
<?php
	}
?>