<?php
// Start session
session_start();

// Unset the variables stored in session from a previous login attempt
unset($_SESSION['SESS_MEMBER_ID']);
unset($_SESSION['SESS_FIRST_NAME']);
unset($_SESSION['SESS_LAST_NAME']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - Student Repeat Management System</title>

	<link rel="shortcut icon" href="main/images/pos.jpg">

	<link href="main/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="main/css/font-awesome.min.css">

	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

	<style>
		/* This ensures the page takes up the full screen and prevents scrolling */
		html,
		body {
			height: 100%;
			margin: 0;
			padding: 0;
			overflow: hidden;
			/* This is the key fix */
		}

		/* General body styling */
		body {
			font-family: 'Poppins', sans-serif;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			display: flex;
			justify-content: center;
			/* Horizontally center */
			align-items: center;
			/* Vertically center */
		}

		/* The main container for the login form */
		.login-container {
			background-color: #ffffff;
			padding: 40px;
			border-radius: 10px;
			box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
			width: 100%;
			max-width: 400px;
			text-align: center;
		}

		/* Form header styling */
		.login-header h2 {
			margin: 0 0 10px 0;
			font-size: 28px;
			color: #333;
			font-weight: 700;
		}

		.login-header p {
			margin: 0 0 30px 0;
			color: #777;
		}

		/* Styling for form input groups */
		.input-group {
			margin-bottom: 20px;
			position: relative;
		}

		.input-group .form-control {
			height: 55px;
			border: 1px solid #ddd;
			border-radius: 8px;
			padding-left: 60px;
			/* ADJUSTED: Increased padding for icon */
			font-size: 16px;
		}

		.input-group .form-control:focus {
			border: 2px solid #667eea;
			box-shadow: none;
			/* Remove default bootstrap shadow */
		}

		/* Icon styling inside input fields */
		.input-group i {
			position: absolute;
			left: 22px;
			/* ADJUSTED: Centered icon in the new padding */
			top: 50%;
			transform: translateY(-50%);
			color: #aaa;
			font-size: 18px;
			/* ADJUSTED: Slightly larger icon */
		}

		/* Login button styling */
		.btn-login {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			border: none;
			border-radius: 8px;
			padding: 15px;
			font-size: 18px;
			font-weight: 500;
			color: #fff;
			width: 100%;
			transition: transform 0.2s;
		}

		.btn-login:hover {
			transform: translateY(-2px);
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
		}

		/* Error message styling */
		.error-message {
			background-color: #f8d7da;
			color: #721c24;
			border: 1px solid #f5c6cb;
			padding: 15px;
			border-radius: 8px;
			margin-bottom: 20px;
			text-align: left;
		}
	</style>
</head>

<body>

	<div class="login-container">
		<div class="login-header">
			<h2>Welcome Back!</h2>
			<p>Please log in to manage student records.</p>
		</div>

		<?php
		// Display login error messages if they exist
		if (isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
			echo '<div class="error-message">';
			foreach ($_SESSION['ERRMSG_ARR'] as $msg) {
				echo '<span>' . htmlspecialchars($msg) . '</span><br>';
			}
			echo '</div>';
			unset($_SESSION['ERRMSG_ARR']); // Clear messages after displaying
		}
		?>

		<form action="login.php" method="post">
			<div class="input-group">
				<i class="icon-user"></i>
				<input type="text" class="form-control" name="username" placeholder="Username" required autocomplete="off">
			</div>

			<div class="input-group">
				<i class="icon-lock"></i>
				<input type="password" class="form-control" name="password" placeholder="Password" required>
			</div>

			<button class="btn btn-large btn-primary btn-login" type="submit">
				<i class="icon-signin"></i> Login
			</button>
		</form>
	</div>

	<script src="main/js/jquery.js"></script>
	<script src="main/js/bootstrap.js"></script>
</body>

</html>