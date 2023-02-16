<?php
// SELECT * FROM light RIGHT JOIN board ON light.id_board=board.id RIGHT JOIN cluster ON light.id_cluster=cluster.id RIGHT JOIN sub_playlist ON light.id_sub_playlist = sub_playlist.id;
	if ( !isset( $_SESSION[ "user_id" ] ) )
	{
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>
	<body>
		<?php
		// Get all the groups of the user
		// $statement = $sleds_database -> prepare( "SELECT id, name FROM cluster LEFT JOIN user ON cluster.id=?.id" );
		// $statement -> bind_param( "s", $token );
		// $statement -> execute();

		// Print all the groups
		while ( $group = $group_result -> fetch_assoc() )
		{
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

				// Select all the lights and boards of a group
				while ( $light = $light_result -> fetch_assoc() )
				{
					echo '
					<tr>
						<th scope = "row" ></th>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					';
				}

				echo '
				</tbody>
			</table>
				';
		}
		?>
	</body>
</html>
<?php
	}
?>