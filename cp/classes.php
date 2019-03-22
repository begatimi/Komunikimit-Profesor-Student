<?php
	require('header.php');
	
	$cookies = array();
	
	// Mos lejimi i studenteve
	if( $account == "student" ) {
		header("Location: home.php");
		exit;
	}

	// Shtimi i nje lende te re
	if ( isset($_POST['btn-submit']) ) {
		$class = trim($_POST['class']);
		$class = strip_tags($class);
		
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
				$cookiename = $class;
				$cookievalue = $pid;
				
				setcookie($cookiename, json_encode($cookievalue), time() + (86400 * 30), "/");
				$cookies += array($cookiename => json_decode($_COOKIE[$cookiename], true));
				unset($class);
				unset($code);
				unset($semester);
			} else {
				$error = true;
				$errorMsg = "<strong>Something went wrong, try again later...</strong>"; 
			}

		}
		
	}
	
	// Fshirja e lendes
	
	
	

	
	if ( isset($_GET['btn-delete']) ) {
		if(isset($_GET['delete-class'])) {
			$deleteClass = $_GET['delete-class'];
		
			foreach ($deleteClass as $id){
				$query = "SELECT * FROM classes WHERE cid='$id';";
				$res=mysqli_query($connect_DB, $query);

				if(mysqli_num_rows($res)>0) {
					while($row = mysqli_fetch_assoc($res)) {
						
						$semester = $row["semester"];
						
						if( $semester == "Summer" && date('n') >= 1 && date('n') <= 5 ) {
							$deleteError = true;
							$deleteErrorMsg = "You can't delete a class that isn't finished";
						} else if ( $semester == "Winter" && date('n') >= 9 && date('n') <= 12 ) {
							$deleteError = true;
							$deleteErrorMsg = "You can't delete a class that isn't finished";
						} else {
							$query = "DELETE FROM classes WHERE cid='$id';";
							$res = mysqli_query($connect_DB,$query);
							
							if ($res) {
								setcookie("$class", "", time() - 3600, "/");
								unset($deleteClass);
							} else {
								$error = true;
								$errorMsg = "<strong>Something went wrong, try again later...</strong>"; 
							}
						}
					}
					
				}
			}
		} else {
			$deleteError = true;
			$deleteErrorMsg = "Select the class that you want to delete!";
		}
	}
	
	// Zgjedhja e lendes
	if ( isset($_GET['btn-select']) ) {
		if(isset($_GET['select-class'])) {
			$selectClass = $_GET['select-class'];
		
			foreach ($selectClass as $id){
				$query = "INSERT INTO lectures (cid, pid) VALUES ('$id', '$pid')";
				$res = mysqli_query($connect_DB,$query);
				
				if ($res) {
					unset($selectClass);
					header("Location: classes.php");
				} else {
					$error = true;
					$errorMsg = "<strong>Something went wrong, try again later...</strong>"; 
				}
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
						echo "<li class=\"active\"><a href=\"classes.php\"><img src=\"../images/cp/classes.png\" alt=\"classes\" class=\"icon\" /><span class=\"nav_item\">Classes</span></a></li>";
					}
				?>				
				<li><a href="resources.php"><img src="../images/cp/resources.png" alt="resources" class="icon" /><span class="nav_item">Resources</span></a></li>
				<li><a href="config.php"><img src="../images/cp/config.png" alt="config" class="icon" /><span class="nav_item">Configurations</span></a></li>
			</ul>
		</nav>
		<div id="content">
			<h1 style="margin-top: 0">Clases</h1>
			<hr/>
			<div class="inline-row">
				<form method="post" action="classes.php">
					<label>Class Name:</label> 
					<input type="text" name="class" />
					<label>Class Code:</label> 
					<input type="text" name="code" />
					<label>Semester:</label> 
					<select name="semester">
						<?php
							if (date('n') >= 1 && date('n') <= 5 ) {
								echo "<option value=\"Summer\">Summer</optiom>";
							} else if (date('n') >= 9 && date('n') <= 12 ) {
								echo "<option value=\"Winter\">Winter</optiom>";
							} else {
								echo "<option value=\"Summer\">Summer</optiom>";
								echo "<option value=\"Winter\">Winter</optiom>";
							}
						?>
					</select>
					<label>Year:</label>
					<span><?php echo date("Y"); ?></span>
					<button type="submit" name="btn-submit">Add Class</button>					
				</form>
			</div>
			<hr/>
			<?php
				if ( isset($_POST['btn-submit']) ) {
					if ($error) {
						echo "<p class=\"text-error\">$errorMsg</p>";
					} else {
						echo "<p class=\"text-success\">$errorMsg</p>";
					}
				}
			?>
			<!-- Paraqitja e te gjitha klasave te universitetit -->
			<form method="get" action="classes.php">
				<?php 
					$query = "SELECT * FROM classes where uid='$university'";
					
					$res=mysqli_query($connect_DB, $query);
					if(mysqli_num_rows($res)>0) {
						echo "<h3>Manage Clases Registered In Your University:</h3>";
						echo "<table>";
						echo "<th>Select</th><th>Name</th><th>Code</th><th>Semester</th><th>Year</th>";

						while($row = mysqli_fetch_assoc($res)) {
							echo "<tr><td><input type=\"checkbox\" name=\"delete-class[]\" value=". $row["cid"] ."></td>". "<td>". $row["name"]."</td>". "<td>".$row["code"]."</td>". "<td>".$row["semester"]."</td>". "<td>".$row["year"]."</td></tr>";
						}
						echo "</table>";
						echo "<button type=\"submit\" name=\"btn-delete\" style=\"float:right;\">Delete Class</button>";
					}
					
					if ( isset($_GET['btn-delete']) ) {
						if ($deleteError) {
							echo "<p class=\"text-error\">$deleteErrorMsg</p>";
						} 
					}
				?>				
			</form>
			
			<form method="get" action="classes.php">
				<?php 
					$query = "SELECT * FROM classes WHERE cid NOT IN (SELECT cid FROM lectures WHERE pid='$pid')";
					
					$res=mysqli_query($connect_DB, $query);
					if(mysqli_num_rows($res)>0) {
						echo "<h3 style=\"margin-top:50px\">Select Classes That You Are Teaching:</h3>";
						echo "<table>";
						echo "<th>Select</th><th>Name</th><th>Code</th><th>Semester</th><th>Year</th>";

						while($row = mysqli_fetch_assoc($res)) {
							echo "<tr><td><input type=\"checkbox\" name=\"select-class[]\" value=". $row["cid"] ."></td>". "<td>". $row["name"]."</td>". "<td>".$row["code"]."</td>". "<td>".$row["semester"]."</td>". "<td>".$row["year"]."</td></tr>";
						}
						echo "</table>";
						echo "<button type=\"submit\" name=\"btn-select\" style=\"float:right;\">Select</button>";
					}
					mysqli_close($connect_DB);
				?>				
			</form>
			
			
			<?php 
			// Klasa edhe RegX
			class InstructorClass {
				public $firstname;
				public $lastname;
				private $university;

				// konstruktori
				function __construct($email) {
					$array = preg_split('/\.|@/', $email);
					$this->firstname = $array[0];
					$this->lastname = $array[1];
					$this->university = preg_replace("/[a-zA-z\-\.@]+/", "Universiteti i Prishtines", $email);
				}
				
				function getUniversity(){
					echo $this->university ."<br/>";
				}
				
				
				function setUniversity($par){
					$this->university = $par;
				}
				
				// destruktori
				function __destruct() {
					
				}
			}
			
			$email = "Emri.Mbiemri@uni-pr.edu";
			$a = new InstructorClass($email);
			//echo $a->firstname."<br/>";
			//echo $a->lastname."<br/>";
			//echo $a->getUniversity()."<br/>";


			?>
		</div>
	</body>
</html>