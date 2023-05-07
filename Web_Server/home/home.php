<?php
	session_save_path( "/var/www/sessions" ); // remove this in other servers
	session_start();

	// SELECT DISTINCT * FROM light RIGHT JOIN board ON light.id_board=board.id RIGHT JOIN cluster ON light.id_cluster=cluster.id RIGHT JOIN sub_playlist ON light.id_sub_playlist = sub_playlist.id;
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
<html>
	<head>
		<link rel = "shortcut icon" href = "/sleds_favicon.ico" type = "image/x-icon" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel = "stylesheet" href = "./style/style.css" >
		<script src="./script/home.js"></script>
	</head>
	<body>
	<nav class="navbar navbar-dark bg-dark">
		<div>
			<a href = "./animation_editor/home.php"><img src = "./images/pixel.png"/></a>
		</div>
		<div>
			<button onclick="log_out()" >LogOut</button>
		</div>
		<div>
			<a href = "./profile/settings.php" ><button>Profile</button></a>
		</div>
		<div>
			<form action="/home/home.php" method="post">
				<input type = "hidden" name="new_token" value="1" />
				<button type="submit">New Token</button>
			</form>
		</div>
	</nav>
	<div class = "lights_tables" >
		<?php
		// Get all the groups of the user
		$statement = $sleds_database -> prepare( "SELECT cluster.* FROM cluster RIGHT JOIN relation_user_cluster ON cluster.id=relation_user_cluster.id_cluster WHERE relation_user_cluster.id_user=?" );
		$statement -> bind_param( "i", $_SESSION[ "user_id" ] );
		$statement -> execute();
		$group_result = $statement -> get_result();

		// Go throught all the groups
		while ( $group = $group_result -> fetch_assoc() )
		{
			// Print a bar to hide the group
			?>
			<div class = "table_hide_show">
				<a class = "hide_show_text"><?php echo $group[ "name" ]; ?></a>
				<a class = "material-icons hide_show" ><button class = "hide_show_button" id = "<?php echo $group[ "name" ]; ?>hide_show_icon" onclick="hide_show_table('<?php echo $group[ 'name' ]; ?>')">expand_more</button></a>
			</div>
			<?php
			// Print the head of the table
			?>
			<div id="<?php echo $group[ 'name' ]; ?>_table">
				<table class = "table table-hover" >
				<thead>
					<tr>
						<th scope = "col" >Name</th>
						<th scope = "col" >Group</th>
						<th scope = "col" >Animation</th>
						<th scope = "col" >Playlist</th>
						<th scope = "col" >Leds</th>
					</tr>
				</thead>
				<tbody>
			<?php
			// Print the content of the table
			// Get the lights of one group
			$statement = $sleds_database -> prepare( "SELECT DISTINCT light.*, board.leds_number as board_leds_number, sub_playlist.name AS sub_playlist_name FROM light RIGHT JOIN board ON light.id_board=board.id RIGHT JOIN sub_playlist ON light.id_sub_playlist=sub_playlist.id WHERE light.id_cluster=?" );
			$statement -> bind_param( "i", $group[ "id" ] );
			$statement -> execute();
			$light_result = $statement -> get_result();

				// Print all the lights and boards of a group
				while ( $light = $light_result -> fetch_assoc() )
				{
					echo '
					<tr>
						<th scope = "row"><a href = "./light/settings.php?light_id=' . $light[ "id" ] . '" >' . $light[ "name" ] . '</a></th>
						<td>' . $group[ "name" ] . '</td>
						<td>' . $light[ "id_animation" ] . '</td>
						<td>' . $light[ "sub_playlist_name" ] . '</td>
						<td>' . $light[ "board_leds_number" ] . '</td>
					</tr>
					';
				}
			?>
				</tbody>
				</table>
			</div>
			<?php
		}

		/*
		// Print the table
		echo '
		<table class = "table table-hover" >
			<thead>
				<tr>
					<th scope = "col" >Name</th>
					<th scope = "col" >Group</th>
					<th scope = "col" >Animation</th>
					<th scope = "col" >Playlist</th>
					<th scope = "col" >Leds</th>
				</tr>
			</thead>
			<tbody>
		';

		// Print all the groups
		while ( $group = $group_result -> fetch_assoc() )
		{
			// Get the lights of one group
			$statement = $sleds_database -> prepare( "SELECT DISTINCT light.*, board.leds_number as board_leds_number, sub_playlist.name AS sub_playlist_name FROM light RIGHT JOIN board ON light.id_board=board.id RIGHT JOIN sub_playlist ON light.id_sub_playlist=sub_playlist.id WHERE light.id_cluster=?" );
			$statement -> bind_param( "i", $group[ "id" ] );
			$statement -> execute();
			$light_result = $statement -> get_result();

				// Print all the lights and boards of a group
				while ( $light = $light_result -> fetch_assoc() )
				{
					echo '
					<tr>
						<th scope = "row"><a href = "./light/settings.php?light_id=' . $light[ "id" ] . '" >' . $light[ "name" ] . '</a></th>
						<td>' . $group[ "name" ] . '</td>
						<td>' . $light[ "id_animation" ] . '</td>
						<td>' . $light[ "sub_playlist_name" ] . '</td>
						<td>' . $light[ "board_leds_number" ] . '</td>
					</tr>
					';
				}

				echo '
				</tbody>
			</table>
				';
		}
		*/
		?>
	</div>
	</body>
</html>
<?php
	}
?>