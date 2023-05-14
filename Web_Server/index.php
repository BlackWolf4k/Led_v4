<?php
	// Check if passing a token and a code ( means something is making a request )
	if ( isset( $_GET[ "code" ] ) )
	{
		// A board connected
		include "./api/handler.php";

		// Handle the request of the board
		handle_request();
	}
	else // A client is connecting
	{
		session_start();
?>
<!DOCTYPE html>
<html lang = "en">
	<head>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel = "stylesheet" href = "./style/style_navbar.css" >
		<link rel = "stylesheet" href = "./style/style.css" >
		<link rel = "stylesheet" href = "./style/colors.css" >
		<link rel = "shortcut icon" href = "/sleds_favicon.ico" type = "image/x-icon" />
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
				<a href="./sign/signin.php">Sign</a>
			</nav>
		</div>
		</header>


		<div class = "text-center head" >
			<div>
				<!--<p class = "text_white text_bigger" >SLEDS</p>-->
			</div>
			<div>
				<!--<p class = "text_white text_big" >Simple, Better</p>-->
				<!--<p>Think about what you dream in your led illumination!<br>Now forget <u>everthing</u>, becouse this is better</p>-->
			</div>
		</div>
		<div class = "text-center row" >
			<div class = "col boxes" >
				<div class = "box_one" >
					<h2>Semplicity</h2>
					<p>Embrace the Elegance of Ease!</p>
				</div>
			</div>
			<div class = "col boxes">
				<div>
					<h2>Efficency</h2>
					<p>Unlocking the Power of Productivity!</p>
				</div>
			</div>
		</div>
		<div class = "text-center sub_head" >
			<div>
				<h1>Our products</h1>
				<p>Nothing</p>
			</div>
		</div>
		<div class = "text-center row" >
			<div class = "col boxes dress_box" >
				<div>
					<h2>Dress</h2>
					<p>Is amagzing</p>
				</div>
			</div>
			<div class = "col boxes">
				<div>
					<h2>Others</h2>
					<p>Is amagzing</p>
				</div>
			</div>
		</div>
	</body>
</html>
<!--<html lang="en">
	<head>
		<link rel = "shortcut icon" href = "../sleds_favicon.ico" type = "image/x-icon" />
	</head>
	<body>
		<div>
			<?//php echo $_SERVER["REQUEST_URI"]; ?>
			<a href = "./sign/signin.php" >Sign In</a>
			<a href = "./sign/signup.php" >Sign Up</a>
		</div>
	</body>
</html>-->
<?php
	}
?>