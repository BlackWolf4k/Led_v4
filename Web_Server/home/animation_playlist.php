<?php
	// session_save_path( "/var/www/sessions" ); // remove this in other servers
	session_start();

	// Check if user is signed in
	if ( !isset( $_SESSION[ "user_id" ] ) )
	{
		// Send user to sign in page
		header( "Location: /sign/signin.php" );
		die();
	}
	else // User is signed in
	{
		if ( isset( $_POST[ "new_token" ] ) && $_POST[ "new_token" ] == 1 )
		{
			include "./profile/generate_token.php";

			generate_token();

			unset( $_POST[ "new_token" ] );
		}
		include "../connection/sleds_connect.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel = "shortcut icon" href = "/sleds_favicon.ico" type = "image/x-icon" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel = "stylesheet" href = "./style/style.css" >
		<link rel = "stylesheet" href = "../style/style_navbar.css" >
		<script src="./script/album_home.js"></script>
	</head>
	<body>
	<header>
		<div class="container">
			<!--<i id="logo" class="fa fa-apple" aria-hidden="true"></i>-->
			<img id = "logo" src = "/home/images/pixel.png" >
			<nav>
				<a href="/home/home.php">Home</a>
				<a href="/home/animation_editor/home.php">Editor</a>
				<a href="/home/animation_album.php">Albums</a>
				<a href="/home/animation_playlist.php">Playlists</a>
				<a href="">Shop</a>
				<a href="">About us</a>
				<a href="">Support</a>
				<a href="/home/profile/settings.php">Settings</a>
			</nav>
		</div>
	</header>
	<div class = "album_tables" >
		<?php
		// Get all the playlist of a user
		$statement = $sleds_database -> prepare( "SELECT sub_playlist.id, sub_playlist.name FROM sub_playlist WHERE sub_playlist.id_playlist IN ( SELECT playlist.id FROM playlist JOIN relation_user_cluster ON relation_user_cluster.id_cluster=playlist.id_cluster WHERE relation_user_cluster.id_user=? )" );
		$statement -> bind_param( "i", $_SESSION[ "user_id" ] );
		$statement -> execute();
		$sub_playlist_result = $statement -> get_result();

		// Go throught all the playlists
		while ( $sub_playlist = $sub_playlist_result -> fetch_assoc() )
		{
			// Print a bar to hide the playlist
			?>
			<div class = "table_hide_show">
				<a class = "hide_show_text"><?php echo $sub_playlist[ "name" ]; ?></a>
				<a class = "material-icons hide_show" ><button class = "hide_show_button" id = "<?php echo $sub_playlist[ "name" ]; ?>hide_show_icon" onclick="hide_show_table('<?php echo $sub_playlist[ 'name' ]; ?>')">expand_more</button></a>
			</div>
			<?php
			// Print the head of the table
			?>
			<div id="<?php echo $sub_playlist[ 'name' ]; ?>_table">
				<table class = "table table-hover" >
				<thead>
					<tr>
						<th scope = "col" >Name</th>
						<th scope = "col" >Playlist</th>
						<th scope = "col" >Leds</th>
						<th scope = "col" >Phases</th>
						<th scope = "col" >Delay</th>
						<th scope = "col" >Repetitions</th>
					</tr>
				</thead>
				<tbody>
			<?php
			// Print the content of the table
			// Get all the animations of the playlist
			$statement = $sleds_database -> prepare( "SELECT animation.id, animation.name, animation.leds_number, animation.phases, animation.delay, animation.repeat FROM animation JOIN relation_animation_sub_playlist ON relation_animation_sub_playlist.id_animation=animation.id WHERE relation_animation_sub_playlist.id_sub_playlist=?" );
			$statement -> bind_param( "i", $sub_playlist[ "id" ] );
			$statement -> execute();
			$animations_result = $statement -> get_result();

				// Print all the animatons
				while ( $animation = $animations_result -> fetch_assoc() )
				{
					echo '
					<tr>
						<th scope = "row"><a href = "#' . $animation[ "id" ] . '" >' . $animation[ "name" ] . '</a></th>
						<td>' . $sub_playlist[ "name" ] . '</td>
						<td>' . $animation[ "leds_number" ] . '</td>
						<td>' . $animation[ "phases" ] . '</td>
						<td>' . $animation[ "delay" ] . '</td>
						<td>';
						if ( $animation[ "repeat" ] == 255 )
							echo "Loop";
						else
							echo $animation[ "repeat" ];
						echo '</td>
					</tr>
					';
				}
			?>
				</tbody>
				</table>
			</div>
			<?php
		}
		?>
	</div>
	</body>
</html>
<?php
	}
?>