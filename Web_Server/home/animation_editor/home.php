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
				<div class = "row" >
					<div class = "col" >
						<label class="form-label" for = "delay_input" >Delay ( milliseconds )</label>
						<input class="form-control" type = "number" id = "delay_input" />
					</div>
					<div class = "col" >
						<label class="form-label" for = "repetitions_input" >Repetitions</label>
						<input class="form-control" type = "range" min = "1" max = "255" value = "255/2" id = "repetitions_input" />
					</div>
				</div>
				<hr>
				<button class="btn btn-primary btn-block mb-4" onclick = "create_strip()">Create</button>
			</div>
			<hr style = "background-color: black;" >
			<div class = "animation_editing">
				<label class="form-label" for = "phase_number" >Number of Phase</label>
				<select class="form-control" id = "phase_number" onchange = "change_phase()" >
				</select>
				<hr>
				<div>
					<label class="form-label" for = "phase_number" >Pixel Number</label>
					<input class="form-control" type = "number" id = "phase_number" />
				</div>
				<hr>
				<div class = "row" >
					<div class = "col" >
						<label class="form-label" for = "from_pixel" >From</label>
						<input class="form-control" type = "number" id = "from_pixel" />
					</div>
					<div class = "col" >
						<label class="form-label" for = "to_pixel" >To</label>
						<input class="form-control" type = "number" id = "to_pixel" />
					</div>
				</div>
				<hr>
				<div>
					<label class="form-label" for = "pixel_color" >Color</label>
					<input class="form-control" type = "color" id = "pixel_color" value = "#ff00ff"/>
				</div>
				<hr>
				<div class = "row" >
					<div class = "col" >
						<button class="btn btn-primary btn-block mb-4" onclick = "change_colors()">Change</button>
					</div>
					<div class = "col" >
						<button class="btn btn-primary btn-block mb-4" onclick = "play_stop()" id = "play" >Play</button>
					</div>
				</div>
			</div>
		</div>
		<div class = "animation col">
			<nav class="navbar navbar">
				<div>
					<!--<label class="form-label" for = "pixel_color" >Animation Name</label>-->
					<input class="form-control" type = "text" id = "animation_name" />
				</div>
				<div>
					<!--<label class="form-label" for = "pixel_color" >Color</label>-->
					<button class="btn btn-primary btn-block mb-4" onclick = "save_animation()" id = "save_animation" >IDK</button>
				</div>
				<div>
				<button class="btn btn-primary btn-block mb-4" onclick = "save_animation()" id = "save_animation" >Save</button>
				</div>
			</nav>
			<hr>
			<div class = "h-100 d-flex align-items-center justify-content-center" id = "strip" >
			</div>
		</div>
	</body>
</html>
<?php
	}
?>