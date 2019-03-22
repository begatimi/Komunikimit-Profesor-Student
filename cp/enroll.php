<?php
	require('header.php');
	
	// Mos lejimi i studenteve
	if( $account == "student" ) {
		header("Location: home.php");
		exit;
	}
	
	// Shtimi i nje lende te re
	if ( isset($_POST['btn-submit']) ) {
		
		$code = trim($_POST['code']);
		$code = strip_tags($code);
		
		$semester = trim($_POST['semester']);
		$semester = strip_tags($semester);
		
		$error = false;
		
		if ( empty($class) ) {
			$error = true;
			$errorMsg = "Please enter the class name!";
		} else if (empty($code)) {
			$error = true;
			$errorMsg = "Please enter the class code!";
		} else {
			$errorMsg = "";
		}
		
		if (!$error) {
			$query = "INSERT INTO classes(uid, name, code, semester, year) VALUES ('$university' ,'$class', '$code', '$semester', '".date("Y")."')";
			
			$res = mysqli_query($connect_DB,$query);

			if ($res) {
				unset($class);
				unset($code);
				unset($semester);
			} else {
				$error = true;
				$errorMsg = "<strong>Something went wrong, try again later...</strong>"; 
			}

		}
		
	}
	
	// Dergimi i emailit (invate)
	if ( isset($_POST['btn-invite']) ) {
		
		$sendto = trim($_POST['sendto']);
		$sendto = strip_tags($sender);
		
		$subject = "Hello, you instructor $fullname has invated you!";
		
		$message = "Please register at http://pkps.com so that you instructor can add you to your corresponding class. \n";
		
		$headers = 'From: pkps@gmail.com' . "\r\n" .	'X-Mailer: PHP/' . phpversion();
		
		mail($sendto,$subject,$message,$headers);
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
						echo "<li class=\"active\"><a href=\"enroll.php\"><img src=\"../images/cp/enroll.png\" alt=\"enroll\" class=\"icon\" /><span class=\"nav_item\">Enroll</span></a></li>";
						echo "<li><a href=\"classes.php\"><img src=\"../images/cp/classes.png\" alt=\"classes\" class=\"icon\" /><span class=\"nav_item\">Classes</span></a></li>";
					}
				?>				
				<li><a href="resources.php"><img src="../images/cp/resources.png" alt="resources" class="icon" /><span class="nav_item">Resources</span></a></li>
				<li><a href="config.php"><img src="../images/cp/config.png" alt="config" class="icon" /><span class="nav_item">Configurations</span></a></li>
			</ul>
		</nav>
		<div id="content">
		
			<h1 style="margin-top: 0">Enroll</h1>
			<hr/>
			
			<form method="get" action="enroll.php">
				<?php 
					$query = "SELECT * FROM students WHERE sid IN (SELECT sid FROM enroll WHERE cid='$cid') ";
					$res=mysqli_query($connect_DB, $query);

					if(mysqli_num_rows($res)>0) {
						echo "<h3>Enrolled Students:</h3>";
						echo "<table>";
						echo "<th>Select</th><th>Firstame</th><th>Lastname</th><th>Email</th>";

						while($row = mysqli_fetch_assoc($res)) {
							echo "<tr><td><input type=\"checkbox\" name=\"unenroll-student[]\" value=". $row["sid"] ."></td>". "<td>". $row["firstname"]."</td>". "<td>".$row["lastname"]."</td>". "<td>".$row["email"]."</td></tr>";
						}
						echo "</table>";
						echo "<button type=\"submit\" name=\"btn-unenroll\" style=\"float:right;\">Unenroll</button>";
					}
				?>				
			</form>
			<?php
				if ( isset($_POST['btn-unenroll']) ) {
					if ($error) {
						echo "<p class=\"text-error\">$errorMsg</p>";
					} else {
						echo "<p class=\"text-success\">$errorMsg</p>";
					}
				}
			?>
			
			<form method="get" action="enroll.php">
				<?php 
					$query = "SELECT * FROM students WHERE uid='$university' AND sid NOT IN (SELECT sid FROM enroll WHERE cid = '$cid')";
					$res=mysqli_query($connect_DB, $query);

					if(mysqli_num_rows($res)>0) {
						echo "<h3 style=\"margin-top:50px\">Unenrolled Students:</h3>";
						echo "<table>";
						echo "<th>Select</th><th>Firstame</th><th>Lastname</th><th>Email</th>";

						while($row = mysqli_fetch_assoc($res)) {
							echo "<tr><td><input type=\"checkbox\" name=\"enroll-student[]\" value=". $row["sid"] ."></td>". "<td>". $row["firstname"]."</td>". "<td>".$row["lastname"]."</td>". "<td>".$row["email"]."</td></tr>";
						}
						echo "</table>";
						echo "<button type=\"submit\" name=\"btn-enroll\" style=\"float:right;\">Enroll</button>";
						// Dergimi i emailit per studentet te cilent nuk jane ne list
						echo "<h3 style=\"margin-top:50px\">Invite Students If They Aren't In The Lists:</h3>";
						echo "<from method=\"get\" action=\"enroll.php\"><input type=\"text\" name=\"sendto\"><button type=\"submit\" name=\"btn-invite\" style=\"display:block; margin-top:10px\">Invite!</button></from>";
						echo "<p><small><strong>Note:</strong> You can send multiple email's by seperating them with a (,) comma.</small></p>";
					}
				?>				
			</form>
			<?php
				if ( isset($_POST['btn-enroll']) ) {
					if ($error) {
						echo "<p class=\"text-error\">$errorMsg</p>";
					} else {
						echo "<p class=\"text-success\">$errorMsg</p>";
					}
				}
			?>
		</div>
	</body>
</html>

<?php
	// Regjistrimi i studenteve ne klasa
	if ( isset($_GET['btn-enroll']) ) {
		if(isset($_GET['enroll-student'])) {
			$enroll = $_GET['enroll-student'];
		
			foreach ($enroll as $sid){
				$query = "INSERT INTO enroll (cid, sid) VALUES ('$cid', '$sid');";
				
				$res = mysqli_query($connect_DB,$query);
				
				if ($res) {
					unset($enroll);
					header("Location: enroll.php");
				} else {
					$error = true;
					$errorMsg = "<strong>Something went wrong, try again later...</strong>"; 
				}
			}
		}
	}
	// Fshirja e studenteve nga klasa
	if ( isset($_GET['btn-unenroll']) ) {
		if(isset($_GET['unenroll-student'])) {
			$unenroll = $_GET['unenroll-student'];
		
			foreach ($unenroll as $sid){
				$query = "DELETE FROM enroll WHERE sid='$sid' AND cid='$cid';";
				
				$res = mysqli_query($connect_DB,$query);
				
				if ($res) {
					unset($unenroll);
					header("Location: enroll.php");
				} else {
					$error = true;
					$errorMsg = "<strong>Something went wrong, try again later...</strong>"; 
				}
			}
		}
	}
	mysqli_close($connect_DB);
?>

