<?php
	// session_save_path( "/var/www/sessions" ); // remove this in other servers
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
		// The url for future request
		$this_server_url = "127.0.0.1:81";

		// Connect to the database
		include "../../connection/sleds_connect.php";

		// Get the user token to use it to get informations about the light
		$statement = $sleds_database -> prepare( "SELECT token FROM user WHERE user.id=?" );
		$statement -> bind_param( "i", $_SESSION[ "user_id" ] );
		$statement -> execute();
		$user_token = ( ( $statement -> get_result() ) -> fetch_assoc() )[ "token" ];

		// Get light and board informations
		$statement = $sleds_database -> prepare( "SELECT light.name, light.id_sub_playlist, board.leds_number, light.id_animation, light.id_cluster FROM light JOIN board ON light.id_board=board.id WHERE light.id=?" );
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
<html lang="en">
	<head>
		<link rel = "shortcut icon" href = "/sleds_favicon.ico" type = "image/x-icon" />
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
					<select type="number" id="group" class="form-control" value = "Group" onchange = "change_sub_playlists( this.value, <?php echo $user_token; ?> )">
					<option disabled selected value></option>
					<script>
						load_groups( "<?php echo $user_token ?>" );
					</script>
					<?php
						/*// Make a request using the general requests
						$curl = curl_init();

						$url = sprintf( "http://%s/?code=%s&token=%s", $this_server_url, "8197", $user_token );

						curl_setopt( $curl, CURLOPT_URL, $url );
						curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

						$result = curl_exec( $curl );

						$result = json_decode( $result, true );

						for ( $i = 0; $i < count( $result[ "groups" ] ); $i++ )
						{
							if ( $result[ "groups" ][ $i ][ "id" ] == $light_result[ "id_cluster" ] )
							{
								echo "<option selected>" . $result[ "groups" ][ $i ][ "name" ] . "</option>";

								// Call the script to load the subplaylist of the group
								echo "<script>change_sub_playlists(" . $light_result[ "id_cluster" ] . ", " . $user_token . ")</script>";
							}
							else
								echo "<option>" . $result[ "groups" ][ $i ][ "name" ] . "</option>";
						}*/
					?>
					</select>
				</div>
				<div class="form-outline mb-4">
					<label class="form-label" for="playlist">Playlist</label>
					<select type="number" id="playlist" class="form-control">
					</select>
				</div>
				<div class="form-outline mb-4">
					<label class="form-label" for="actual_animation">Actual Animation</label>
					<select type="text" id="actual_animation" class="form-control">
						<option disabled selected value></option>
						<?php
						// Get all the animations of the subplaylist
						$statement = $sleds_database -> prepare( "SELECT animation.name, animation.id FROM animation JOIN relation_animation_sub_playlist ON animation.id=relation_animation_sub_playlist.id_animation WHERE relation_animation_sub_playlist.id_sub_playlist=?" );
						$statement -> bind_param( "i", $light_result[ "id_sub_playlist" ] );
						$statement -> execute();
						$animation_result = $statement -> get_result();

						if ( mysqli_num_rows( $animation_result ) > 0 )
						{
							while ( $animation = $animation_result -> fetch_assoc() )
							{
								echo "<option value='" . $animation[ "id" ] . "'";
								if ( $animation[ "id" ] == $light_result[ "id_animation" ] )
									echo "selected";
								echo ">" . $animation[ "name" ] . "</option>";
							}
						}
						?>
					</select>
				</div>
				<div class="form-outline mb-4">
					<label class="form-label" for="actual_animation">Offline Animation ( to do )</label>
					<select type="number" id="actual_animation" class="form-control" value = "">
					</select>
				</div>
				<button type="submit" class="btn btn-primary btn-block mb-4">Change</button>
			</form>
			<a href = "/home/home.php" ><button class="btn btn-primary btn-block mb-4" >Discard</button></a>
		</div>
	</body>
</html>
<?php
	}
?>
