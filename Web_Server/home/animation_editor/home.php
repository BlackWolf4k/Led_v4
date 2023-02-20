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
	else
	{
		include "../../connection/sleds_connect.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel = "shortcut icon" href = "/sleds_favicon.ico" type = "image/x-icon" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel = "stylesheet" href = "./style/style.css" >
		<script src = "./script/pixel_script.js"></script>
	</head>
	<body class = "row">
		<div class = "input col-3" >
			<div class = "animation_input" >
				<div class = "form-outline mb-4" >
					<label class="form-label" for = "pixels_number_input" >Number of Pixels</label>
					<input class="form-control" type = "number" id = "pixels_number_input" />
				</div>
				<div class = "form-outline mb-4" >
					<label class="form-label" for = "pattern_input" >Pattern</label>
					<select class="form-control" id = "pattern_input" >
					<?php
						// Print all the patterns
						$statement = $sleds_database -> prepare( "SELECT * FROM pattern" );
						$statement -> execute();
						$pattern_result = $statement -> get_result();
		
						while ( $pattern = $pattern_result -> fetch_assoc() )
						{
							echo "<option value='" . $pattern[ "id" ] . "'> " . $pattern[ "name" ] . "</option>";
						}
					?>
					</select>
				</div>
				<div class = "form-outline mb-4" >
					<label class="form-label" for = "phases_number_input" >Number of Phases</label>
					<input class="form-control" type = "number" id = "phases_number_input" />
				</div>
				<div class = "form-outline mb-4" >
					<label class="form-label" for = "delay_input" >Delay ( milliseconds )</label>
					<input class="form-control" type = "number" id = "delay_input" />
				</div>
				<button class="btn btn-primary btn-block mb-4" onclick = "create_strip()">Create</button>
			</div>
			<div>
				<hr>
			</div>
		</div>
		<div class = "animation col" >
		</div>
	</body>
</html>
<?php
	}
?>