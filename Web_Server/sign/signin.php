<html>
	<head>
		<link rel = "stylesheet" href = "./style/sign.css" >
		<link rel = "shortcut icon" href = "../favicon.ico" type = "image/x-icon" />
		<title>sLeds SignIn</title>
	</head>
	<body class = "signin_body" >
		<div class = "sign" >
			<a class = "sign_title">Sign In</a><br><br>
			<form action = "sign.php" method = "post" class = "sign_form" >
				<a>Username</a><br>
				<input type = "text" name = "username" class = "sign_input" ><br><br>
				<a>Password</a><br>
				<input type = "password" name = "password" class = "sign_input" ><br><br>
				<input type = "submit" value = "Sign In" class = "sign_button" ><br>
				<input type = "hidden" value = "0" name = "signup" >
			</form>
			<div class = "question" >
				<a>Don't have an account? </a><a class = "question_link" href = "./signup.php" >Sign Up</a>
			</div>
		</div>
	</body>
</html>