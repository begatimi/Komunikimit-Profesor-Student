<?php 
	session_start();
	
	if( isset($_SESSION['user']) ){
	  header("Location: home.php");
	}
	
	require_once("DatabaseConfig.php");
	
	$error = false;
	
	if ( isset($_POST['btn-login']) ) { 
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);

		$password = trim($_POST['password']);
		$password = strip_tags($password);
		$password = htmlspecialchars($password);
		
		if(empty($email)){
			$error = true;
			$errorMsg = "Please enter your email address.";
		} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$errorMsg = "Please enter valid email address.";
		}

		if (empty($password)) {
			$error = true;
			$errorMsg = "Please enter password.";
		} else if(strlen($password) < 6) {
			$error = true;
			$errorMsg = "Password must have atleast 6 characters.";
		}

		if (!$error) {
			
			$query = "SELECT email, password FROM students WHERE email='$email' UNION SELECT email, password FROM instructors WHERE email='$email'";
			
			$res=mysqli_query($connect_DB,$query);
			$row=mysqli_fetch_array($res);
			$count = mysqli_num_rows($res);
			
			if( $count == 1 && password_verify($password, $row['password']) ) {
				$_SESSION['email'] = $row['email'];
				header("Location: cp/home.php");
			} else {
				$error = true;
				$errorMsg = "Incorrect Credentials, Try again...";
			}
		}
	}
	
	
?>

<!DOCTYPE html>
<html>
	<head>
		<title>PKPS - Control Panel Login</title>
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	</head>
	<body id="login">
		<div class="login-page">
		  <div class="form">
				<form class="login-form" method="post" action="login.php">
			
					<input type="text" name="email" placeholder="Email"/>
					<input type="password" name="password" placeholder="Password"/>
			  
					<button type="submit" name="btn-login">login</button>
					<p class="message">Not registered? <a href="register.php">Create an account</a></p>
			  
					<?php 
					if($error) 
						echo "<p class=\"text-error\">$errorMsg</p>";
					?>
			  
				</form>
		  </div>
		</div>
	</body>
</html>
<?php mysqli_close($connect_DB); ?>