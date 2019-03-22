<!DOCTYPE html>
<html>
	<head>
		<title>PKPS - Platforma Per Komunikim Profesor Student</title>
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	</head>
	<body>
		<script>
			$(function(){
			  $('a').each(function() {
				if ($(this).prop('href') == window.location.href){
					$('li a').removeClass('current');
					$(this).addClass('current');
				}
			  });
			});
		</script>
		<header>
				<nav>
					<a href="home.php" id="webname">PKPS</a>
					<ul>
						<li><a href="home.php" class="current">Home</a></li>
						<li><a href="about.php">About</a></li>
						<li><a href="contact.php">Contact</a></li>
					</ul>
				</nav>
				<div style="float: right; margin-right: 50px">
					<ul>
						<li><a href="login.php"><img src="images/sign_in.png" alt="sign_in" width="15" height="15" /> Sign In</a></</li>
						<li><a href="register.php"><img src="images/sign_up.png" alt="sign_up" width="15" height="15" /> Sign Up</a></</li>
					</ul>
				</div>
			</div>
		</header>