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
					<input class="form-control" type = "number" id = "pixels_number_input" value = "0" />
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
					<input class="form-control" type = "number" id = "phases_number_input" value = "0" />
				</div>
				<div class = "row" >
					<div class = "col" >
						<label class="form-label" for = "delay_input" >Delay ( milliseconds )</label>
						<input class="form-control" type = "number" id = "delay_input" value = "0" />
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
				<select class="form-control" id = "phase_number" onchange = "display_editor_strip()" >
				</select>
				<!--<hr>
				<div>
					<label class="form-label" for = "pixel_number" >Pixel Number</label>
					<input class="form-control" type = "number" id = "pixel_number" value = "0"/>
				</div>-->
				<hr>
				<div class = "row" >
					<div class = "col" >
						<label class="form-label" for = "from_pixel" >From</label>
						<input class="form-control" type = "number" id = "from_pixel" value = "0"/>
					</div>
					<div class = "col" >
						<label class="form-label" for = "to_pixel" >To</label>
						<input class="form-control" type = "number" id = "to_pixel" value = "0"/>
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
			<div class = "row h-100 flex-column" ><!-- h-100 d-flex align-items-center justify-content-center -->
			<nav class="navbar navbar">
				<div>
					<!--<label class="form-label" for = "pixel_color" >Animation Name</label>-->
					<input class="form-control" type = "text" id = "animation_name" />
				</div>
				<div>
					<!--<label class="form-label" for = "pixel_color" >Color</label>-->
					<button class="btn btn-primary btn-block mb-4" onclick = "save_animation()" id = "button_save_animation" >IDK</button>
				</div>
				<div>
					<form action = "./upload_animation.php" method = "post" >
						<input type = "hidden" value = "1" name = "animation" id = "animation_body_post" >
						<button class="btn btn-primary btn-block mb-4" onclick = "save_animation()" id = "button_save_animation" type = "submit" >Save</button>
					</form>
				</div>
			</nav>
			<hr>
				<div class = "d-flex align-items-center justify-content-center strip col" id = "strip_editor" >
				</div>
				<hr style = "background-color: black;" >
				<div class = "d-flex align-items-center justify-content-center strip col" id = "strip_animation" >
				</div>
			</div>
		</div>
	</body>
</html>
<?php
	}
?>