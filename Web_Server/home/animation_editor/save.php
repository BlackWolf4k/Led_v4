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

if ( !isset( $_POST[ "animation" ] ) )
{
	// Go to the animation creation page
	header( "Location: ./home.php" );
	die();
}
else
{
	// Store the animation as a session value
	$_SESSION[ "animation" ] = $_POST[ "animation" ];
?>
<!DOCTYPE html>
<html>
<head>
	<link rel = "shortcut icon" href = "/sleds_favicon.ico" type = "image/x-icon" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<section class="vh-100 bg-image" >
	<div class="mask d-flex align-items-center h-100 gradient-custom-3">
		<div class="container h-100">
			<div class="row d-flex justify-content-center align-items-center h-100">
				<div class="col-12 col-md-9 col-lg-7 col-xl-6">
					<div class="card" style="border-radius: 15px;">
						<div class="card-body p-5">
							<h2 class="text-uppercase text-center mb-5">Login</h2>
							<form action = "upload_animation.php" method = "post" >
								<div class="form-outline mb-4">
									<input type="text" name="animation_name" class="form-control form-control-lg" required />
									<label class="form-label" for="animation_name">Animation Name</label>
								</div>
								<div class="form-outline mb-4">
									<select name = "animation_sub_playlist" class="form-control form-control-lg" required>
										<option disabled selected value></option>
										<?php
										?>
									</select>
									<label class="form-label" for="animation_sub_playlist">Playlist</label>
								</div>
								<div class="d-flex justify-content-center">
									<button type="submit" class="btn btn-success btn-block btn-lg text-body">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</section>
	</body>
</html>
<?php
}
?>