<!DOCTYPE html>
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

		// Get the needed informations for the settings
		$statement = $sleds_database -> prepare( "SELECT * FROM user WHERE id=?" );
		$statement -> bind_param( "i", $_SESSION[ "user_id" ] );
		$statement -> execute();
		$result = $statement -> get_result();
		$result = $result -> fetch_assoc();
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Account Settings</title>
	<link rel = "shortcut icon" href = "/sleds_favicon.ico" type = "image/x-icon" />
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./style/style.css">
	<script src = "./script/ajax.js"></script>
	<script src = "./script/script.js"></script>
</head>
<body>
	<section class="py-5 my-5">
		<div class="container">
			<h1 class="mb-5">Account Settings</h1>
			<div class="bg-white shadow rounded-lg d-block d-sm-flex">
				<div class="profile-tab-nav border-right">
					<div class="p-4">
						<div class="img-circle text-center mb-3">
							<img src="./images/led_ring_logo.webp" alt="Image" class="">
						</div>
						<h4 class="text-center"><?php?></h4>
					</div>
					<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						<a class="nav-link active" id="account-tab" data-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="true">
							<i class="fa fa-home text-center mr-1"></i> 
							Account
						</a>
						<a class="nav-link" id="password-tab" data-toggle="pill" href="#password" role="tab" aria-controls="password" aria-selected="false">
							<i class="fa fa-key text-center mr-1"></i> 
							Password
						</a>
						<a class="nav-link" id="security-tab" data-toggle="pill" href="#security" role="tab" aria-controls="security" aria-selected="false">
							<i class="fa fa-user text-center mr-1"></i> 
							Security
						</a>
						<a class="nav-link" id="application-tab" data-toggle="pill" href="#application" role="tab" aria-controls="application" aria-selected="false">
							<i class="fa fa-tv text-center mr-1"></i> 
							Devices
						</a>
						<!--<a class="nav-link" id="notification-tab" data-toggle="pill" href="#notification" role="tab" aria-controls="notification" aria-selected="false">
							<i class="fa fa-bell text-center mr-1"></i> 
							Notification
						</a>-->
					</div>
				</div>
				<div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
					<div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
						<form method = "post" action = "./change_settings.php">
							<h3 class="mb-4">Account Settings</h3>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>UserName</label>
										<input onkeyup = "check_username( this )" id = "username" name = "username" type="text" class="form-control" value="<?php echo $result[ "username" ]?>" >
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Email</label>
										<input onkeyup = "check_email( this )" id = "email" name = "email" type="text" class="form-control" value="<?php echo $result[ "email" ]?>" >
									</div>
								</div>
							</div>
							<script>
								save_original_data();
							</script>
							<div>
								<button type = "submit" class="btn btn-primary">Update</button>
								<button class="btn btn-light">Cancel</button>
							</div>
							<input name = "action" type = "hidden" value = "1" />
						</form>
					</div>
					<div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
						<form method = "post" action = "./change_settings.php">
						<h3 class="mb-4">Password Settings</h3>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								  	<label>Old password</label>
								  	<input name = "old_password" type="password" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								  	<label>New password</label>
								  	<input onkeyup = "check_password_same( this )" id = "password_" name = "new_password" type="password" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								  	<label>Confirm new password</label>
								  	<input onkeyup = "check_password_same( this )" id = "password_confirm" name = "new_password_confirm" type="password" class="form-control">
								</div>
							</div>
						</div>
						<div>
							<button type = "submit" class="btn btn-primary">Update</button>
							<button class="btn btn-light">Cancel</button>
						</div>
						<input name = "action" type = "hidden" value = "2" />
						</form>
					</div>
					<div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
						<h3 class="mb-4">Security Settings</h3>
								<div class="form-group">
								  	<label>Access Token</label>
								  	<input name = "token" type="text" class="form-control" value = "<?php echo $result[ "token" ]?>" disabled>
								</div>
								<div class="form-group">
									<form method = "post" action = "./change_settings.php">
										<label>Change Access Token</label><br>
										<button class="btn btn-primary" type = "submit" >New Token</button>
										<input name = "action" type = "hidden" value = "3" />
									</form>
						</div>
						<div>
						</div>
					</div>
					<div class="tab-pane fade" id="application" role="tabpanel" aria-labelledby="application-tab">
						<h3 class="mb-4">Application Settings</h3>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="" id="app-check">
										<label class="form-check-label" for="app-check">
										App check
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="" id="defaultCheck2" >
										<label class="form-check-label" for="defaultCheck2">
										Lorem ipsum dolor sit.
										</label>
									</div>
								</div>
							</div>
						</div>
						<div>
							<button class="btn btn-primary">Update</button>
							<button class="btn btn-light">Cancel</button>
						</div>
					</div>
					<div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notification-tab">
						<h3 class="mb-4">Notification Settings</h3>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="notification1">
								<label class="form-check-label" for="notification1">
									Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum accusantium accusamus, neque cupiditate quis
								</label>
							</div>
						</div>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="notification2" >
								<label class="form-check-label" for="notification2">
									hic nesciunt repellat perferendis voluptatum totam porro eligendi.
								</label>
							</div>
						</div>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="notification3" >
								<label class="form-check-label" for="notification3">
									commodi fugiat molestiae tempora corporis. Sed dignissimos suscipit
								</label>
							</div>
						</div>
						<div>
							<button class="btn btn-primary">Update</button>
							<button class="btn btn-light">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php
	}
?>