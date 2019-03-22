<?php
	require('header.php');
	
	$error = false;
	$errorType = "";
	
	if ( isset($_POST['btn-change-pass']) ) { 
		
		$vpass = trim($_POST['verify-password']);
		$vpass = strip_tags($vpass);
		$vpass = htmlspecialchars($vpass);
		
		$npass = trim($_POST['new-password']);
		$npass = strip_tags($npass);
		$npass = htmlspecialchars($npass);
		
		$npass2 = trim($_POST['new-password2']);
		$npass2 = strip_tags($npass2);
		$npass2 = htmlspecialchars($npass2);
		
		if (empty($npass)){
			$error = true;
			$errorMsg = "Please enter password.";
		} else if ($npass != $npass2) {
			$error = true;
			$errorMsg = "Your password is not the same with your confirmed password.";
		} else if(strlen($npass) < 6) {
			$error = true;
			$errorMsg = "Password must have atleast 6 characters.";
		}

		if (!$error) {
			if ($account == "student") {
				$query = "SELECT email, password FROM students WHERE email='$email'";
			} else {
				$query = "SELECT email, password FROM instructors WHERE email='$email'";
			}
			
			$res=mysqli_query($connect_DB,$query);
			$row=mysqli_fetch_array($res);
			
			if( password_verify($vpass, $row['password']) ) {
				$password = password_hash($npass, PASSWORD_DEFAULT);
				if ($account == "student") {
					$query = "UPDATE students SET password='$password' WHERE sid='$sid';";
				} else {
					$query = "UPDATE instructors SET password='$password' WHERE pid='$pid';";
				}
				
				$res = mysqli_query($connect_DB,$query);

				if ($res) {
					$errorType = "success";
					$errorMsg = "<strong>Password has been changed successfully!";
					unset($password);
					unset($vpass);
					unset($npass);
					unset($npass2);
				} else {
					$error = true;
					$errorMsg = "<strong>Something went wrong, try again later...</strong>"; 
				} 
			} else {
				$error = true;
				$errorMsg = "<strong>Incorrect Password, Try again...</strong>";
			}
		}
	}
?>
		<!-- page content -->
		<nav class="sidenav">
			<img src="../images/find_user.png" alt="userpic" style="display: block; padding: 15px; margin: auto" />
			<h3 class="user-info"><?php echo $fullname; ?></h3>
			<h6 class="user-info"><?php echo $email; ?></h6>
			<ul>
				<li><a href="home.php"><img src="../images/cp/dashboard.png" alt="dashboard" class="icon" /><span class="nav_item">Dashboard</span></a></li>
				<li><a href="discussions.php"><img src="../images/cp/discussion.png" alt="discussions" class="icon" /><span class="nav_item">Discussions</span></a></li>
				<?php 
					if ($account == "instructor") {
						
						echo "<li><a href=\"enroll.php\"><img src=\"../images/cp/enroll.png\" alt=\"enroll\" class=\"icon\" /><span class=\"nav_item\">Enroll</span></a></li>";
						echo "<li><a href=\"classes.php\"><img src=\"../images/cp/classes.png\" alt=\"classes\" class=\"icon\" /><span class=\"nav_item\">Classes</span></a></li>";
					}
				?>				
				<li><a href="resources.php"><img src="../images/cp/resources.png" alt="resources" class="icon" /><span class="nav_item">Resources</span></a></li>
				<?php 
					if ($account == "student") {
						echo "<li><a href=\"game.php\"><img src=\"../images/cp/game.png\" alt=\"game\" class=\"icon\" /><span class=\"nav_item\">Game</span></a></li>";
					}
				?>	
				<li class="active"><a href="config.php"><img src="../images/cp/config.png" alt="config" class="icon" /><span class="nav_item">Configurations</span></a></li>
			</ul>
		</nav>
		<div id="content" style="height:500px;">
			<h1 style="margin-top: 0">Configurations</h1>
			<hr/>
			
			<form method="post" action="config.php" class="change-pass">
				<h3 style="margin-top: 0px">Change Password</h3>
				<input type="password" name="verify-password" placeholder="Verify Password"/>
				<input type="password" name="new-password" placeholder="New Password"/>
				<input type="password" name="new-password2" placeholder="Confirm Password"/>
				
				<button type="submit" name="btn-change-pass">Change Password</button>
				
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
	</body>
</html>
<?php mysqli_close($connect_DB); ?>