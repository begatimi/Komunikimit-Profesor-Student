<?php 

	session_start();
	require_once('../DatabaseConfig.php');

	// Nese nuk eshte bere logini, ridirektohet te faqja logini
	if( !isset($_SESSION['email']) ) {
		header("Location: ../login.php");
		exit;
	}
	
	$email = $_SESSION['email'];
	
	$query = "SELECT * FROM students WHERE email='$email';";
	$res=mysqli_query($connect_DB, $query);
	$row = mysqli_fetch_assoc($res);
	
	$sid = $row['sid'];
	
	if (empty($sid)) {
		$query = "SELECT * FROM instructors WHERE email='$email';";
		$res=mysqli_query($connect_DB, $query);
		$row = mysqli_fetch_assoc($res);
	
		$pid = $row['pid'];
		$account = "instructor"; 
	} else {
		$account = "student";
	}
	
	$fullname = $row['firstname']." ".$row['lastname'];
	$_SESSION['fullname'] = $fullname;
	$university = $row['uid'];
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>PKPS - Platforma Per Komunikim Profesor Student</title>
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	</head>
	<body>
		<header>
				<nav>
					<a href="home.php" id="webname">PKPS - Platforma Per Komunikim Profesor Student</a>
					<div style="margin-right: 50px">
					<ul class="right-nav">
						<li>
							<label style="color:white;">Select class:</label> 
						<li>
						</li>
							<select name="class">
							<?php							
								if( $account == "instructor" ) {
									$query = "SELECT * FROM classes WHERE cid IN (SELECT cid FROM lectures WHERE pid='$pid')";
								} else {
									$query = "SELECT * FROM classes WHERE cid IN (SELECT cid FROM enroll WHERE sid='$sid')";
								}
							
								$res=mysqli_query($connect_DB, $query);

								if(mysqli_num_rows($res)>0) {
									while($row = mysqli_fetch_assoc($res)) {
										$cid = $row["cid"];
										$class = $row["name"];
										$cyear = $row["year"];
										echo "<option value=\"$cid\">". $class ." ". $cyear ."</option>";
									}
									
								}
								
							?>
							</select>
						</li>
						<li style="margin-left: 30px;">
							<form method="get" action="logout.php">
								<button type="submit" name="logout">Logout</button>
							</form>					
						</li>
					</ul>
				</div>
				</nav>
				
			</div>
		</header>