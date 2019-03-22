<?php 
	session_start();
	
	if( isset($_SESSION['email']) ){
	  header("Location: home.php");
	}
	
	require_once("DatabaseConfig.php");
	
	$error = false;
	$errorType = "";
	
	if ( isset($_POST['btn-signup']) ) {
		
		$registerAs = $_POST['registerAs'];
		
		$names = explode(" ",$_POST['fullname']); // Perdorimi i explode per ndarjen e stringjeve
		$firstName = htmlspecialchars($names[0]);
		
		if (!empty($names[1])) {
			$lastName = htmlspecialchars($names[1]);
		}
		
		$email = trim($_POST['email']); // Perdorimi i Trim per largimin e hapsirave
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		
		$pass = trim($_POST['password']);
		$pass = strip_tags($pass);
		$pass = htmlspecialchars($pass);
		
		$pass2 = trim($_POST['password2']);
		$pass2 = strip_tags($pass2);
		$pass2 = htmlspecialchars($pass2);
		
		$university = trim($_POST['university']);
		$university = strip_tags($university);
		$university = htmlspecialchars($university);
		
		// Validimi emrit dhe mbiemrit
		if (empty($names[0])) {
			$error = true;
			$errorMsg = "Please enter your full name.";
		} else if (empty($names[1])) {
			$error = true;
			$errorMsg = "Please enter your full name.";
		} else if (strlen($names[0]) < 3 || strlen($names[1]) < 3) {
			$error = true;
			$errorMsg = "Name must have atleat 3 characters.";
		} else if (!preg_match("/^[a-zA-Z ]+$/",$firstName) || !preg_match("/^[a-zA-Z ]+$/",$lastName)) { // perdorimi i preg_match
			$error = true;
			$errorMsg = "Name must contain alphabets and space.";
		} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {    // Validimi i emailit
			$error = true;
			$errorMsg = "Please enter valid email address.";
		} else {
			// Kontrollimi se a ekziston emaili ne databaze
			$query = "SELECT email FROM students WHERE email='$email' UNION SELECT email FROM instructors WHERE email='$email'";
			$result = mysqli_query($connect_DB,$query);
			$count = mysqli_num_rows($result);
			if($count!=0){
				$error = true;
				$errorMsg = "Provided Email is already in use.";
			}
			// Validimi i fjalekalimit
			if (empty($pass)){
				$error = true;
				$errorMsg = "Please enter password.";
			} else if ($pass != $pass2) {
				$error = true;
				$errorMsg = "Your password is not the same with your confirmed password.";
			} else if(strlen($pass) < 6) {
				$error = true;
				$errorMsg = "Password must have atleast 6 characters.";
			} 
		}

		
		// Generimi i hashit per fjalekalimin
		$password = password_hash($pass, PASSWORD_DEFAULT);
		
		// Regjistrimi
		if( !$error ) {
			$query = "INSERT INTO $registerAs(uid,firstName,lastName,email,password) VALUES('$university','$firstName','$lastName','$email','$password')";
			$res = mysqli_query($connect_DB,$query);

			if ($res) {
				setcookie($welcome, "Just registered!", time() + (86400 * 30), "/"); // krijimi i cookiet
				$errorType = "success";
				$errorMsg = "<strong>Successfully registered, you may <a href=\"login.php\">login</a> now!</strong>";
				unset($university);
				unset($email);
				unset($firstName);
				unset($lastName);
				unset($password);
			} else {
				$error = true;
				$errorMsg = "<strong>Something went wrong, try again later...</strong>"; 
			} 
		}
	}
	
?>

<!DOCTYPE html>
<html>
	<head>
		<title>PKPS - Register</title>
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	</head>
	<body id="login">
		<div class="register-page">
			<div class="form">
				<form class="register-form" method="post" action="register.php">
					<select name="registerAs">
						<option value="students">Student</option>
						<option value="instructors">Instructor</option>
					</select>

					<input type="text" name="fullname" placeholder="Full Name"/>
					<input type="text" name="email" placeholder="Email Address"/>
					<input type="password" name="password" placeholder="Password"/>
					<input type="password" name="password2" placeholder="Confirm Password"/>

					<select name="university">
						<?php 
						$query = "SELECT * FROM university";
						$result = mysqli_query($connect_DB,$query);

						while($row = mysqli_fetch_assoc($result)) {
							echo "<option value=". $row["uid"] .">". $row["name"] ."</option>";
						}
						?>
					</select>

					<button type="submit" name="btn-signup">create</button>
					<p class="message">Already registered? <a href="login.php">Sign In</a></p>

					<?php
					if ( isset($errorMsg) ) {
						if($errorType == "success") {
							echo "<p class=\"text-success\">$errorMsg</p>";
						} else {
							echo "<p class=\"text-error\">$errorMsg</p>";
						}
					}
					?>

				</form>
			</div>
		</div>
	</body>
</html>
<?php mysqli_close($connect_DB); ?>