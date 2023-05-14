<!DOCTYPE html>
<html lang="en">
<head>
	<link rel = "shortcut icon" href = "/sleds_favicon.ico" type = "image/x-icon" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel = "stylesheet" href = "./style/sign.css" >
	<script src = "./script/ajax.js"></script>
	<script src = "./script/script.js"></script>
</head>
<body>
<section class="vh-100 bg-image signup_body">
	<div class="mask d-flex align-items-center h-100 gradient-custom-3">
		<div class="container h-100">
			<div class="row d-flex justify-content-center align-items-center h-100">
				<div class="col-12 col-md-9 col-lg-7 col-xl-6">
					<div class="card" style="border-radius: 15px;">
						<div class="card-body p-5">
							<h2 class="text-uppercase text-center mb-5">Create an account</h2>	
							<?php
							if ( isset( $_GET[ "error" ] ) && $_GET[ "error" ] == 0 )
								echo "<a style = 'color: red;' >Plase fill all the fields</a><br><br>";
							?>
							<form action = "sign.php" method = "post">
								<div class="form-outline mb-4">
									<input onkeyup="check_username( this )" type="text" id = "username" name="username" class="form-control form-control-lg" required
									<?php if ( isset( $_GET[ "error" ] ) && $_GET[ "error" ] == 3 )
										echo " placeholder = 'Username already in use' ";
									?> />
									<label class="form-label" for="username">Your Username</label>
								</div>
								<div class="form-outline mb-4">
									<input onkeyup="check_email( this )" type="email" name="email" id = "email" class="form-control form-control-lg" required
									<?php if ( isset( $_GET[ "error" ] ) && $_GET[ "error" ] == 2 )
										echo " placeholder = 'Email already in use' ";
									?> />
									<label class="form-label" for="email">Your Email</label>
								</div>
								<div class="form-outline mb-4">
									<input onkeyup="check_password_same()" type="password" id = "password" name="password" class="form-control form-control-lg" required
									<?php if ( isset( $_GET[ "error" ] ) && $_GET[ "error" ] == 1 )
										echo " placeholder = 'The passwords are not the same' ";
									?> />
									<label class="form-label" for="password">Password</label>
								</div>
								<div class="form-outline mb-4">
									<input onkeyup="check_password_same()" type="password" id = "password_confirm" name="password_confirm" class="form-control form-control-lg" required
									<?php if ( isset( $_GET[ "error" ] ) && $_GET[ "error" ] == 1 )
										echo " placeholder = 'The passwords are not the same' ";
									?> />
									<label class="form-label" for="password_confirm">Repeat your password</label>
								</div>
								<!--<div class="form-check d-flex justify-content-center mb-5">
									<input class="form-check-input me-2" type="checkbox" value="" id="form2Example3cg" />
									<label class="form-check-label" for="form2Example3g">I agree all statements in <a href="#!" class="text-body"><u>Terms of service</u></a>
									</label>
								</div>-->
								<div class="d-flex justify-content-center">
									<button type="submit" id = "signup_button" class="btn btn-success btn-block btn-lg text-body sign_button">SignUp</button>
								</div>
								<p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="signin.php" class="fw-bold text-body"><u>Login here</u></a></p>	
								<input type = "hidden" value = "1" name = "signup" >
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