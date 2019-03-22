<?php
	require('header.php');
	
	$statsArray = array("postNum"=>"posts", "studentNum"=>"enroll", "instructorNum"=>"lectures"); //,"reply_pist", "questions","unAsnweredQuestions");
	
	foreach ( $statsArray as $x => $x_value ) {
		$query = "SELECT count(*) AS count FROM $x_value WHERE cid='$cid';";
		$res=mysqli_query($connect_DB, $query);
		
		if(mysqli_num_rows($res)>0) {
			$row = mysqli_fetch_assoc($res);
			
			if ($x == "postNum") {
				$postNum = $row["count"];
			} else if ($x == "studentNum") {
				$studentNum = $row["count"];
			} else if ($x == "instructorNum") {
				$instructorNum = $row["count"];
			} 
		}
	}
	
		
	$query = "SELECT count(*) AS count FROM post_reply WHERE sid IN (SELECT sid FROM posts WHERE cid='$cid');";
	$res=mysqli_query($connect_DB, $query);
	
	if(mysqli_num_rows($res)>0) {
		$row = mysqli_fetch_assoc($res);
		$replyNum = $row["count"];
	}
	
	$query = "SELECT count(*) AS count FROM posts WHERE cid='$cid' AND ptype='question';";
	$res=mysqli_query($connect_DB, $query);
	
	if(mysqli_num_rows($res)>0) {
		$row = mysqli_fetch_assoc($res);
		$questionNum = $row["count"];
	}
	
	$query = "SELECT count(*) AS count FROM posts WHERE cid='$cid' AND ptype='question' AND postid NOT IN (SELECT postid FROM post_reply)";
	$res=mysqli_query($connect_DB, $query);
	
	if(mysqli_num_rows($res)>0) {
		$row = mysqli_fetch_assoc($res);
		$unAnsQuesNum = $row["count"];
	}
	
?>
		<!-- page content -->
		<nav class="sidenav">
			<img src="../images/find_user.png" alt="userpic" style="display: block; padding: 15px; margin: auto" />
			<h3 class="user-info"><?php echo $fullname; ?></h3>
			<h6 class="user-info"><?php echo $email; ?></h6>
			<ul>
				<li class="active"><a href="home.php"><img src="../images/cp/dashboard.png" alt="dashboard" class="icon" /><span class="nav_item">Dashboard</span></a></li>
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
				<li><a href="config.php"><img src="../images/cp/config.png" alt="config" class="icon" /><span class="nav_item">Configurations</span></a></li>
			</ul>
		</nav>
		<div id="content">
			<h1 style="margin-top: 0">Dashboard</h1>
			<p>
				<small>
						<?php 
							if(isset($welcome)) {
								echo "Welcome " . $fullname . "!";
							} else {
								echo "Welcome back ". $fullname . "!";
							}
						?>
				</small>
			</p>
			<hr/>
			<div class="stats">
				<div class="stat-column">
					<img src="../images/cp/discussion.png" alt="discussions" class="icon" />
					<h2>Number of Total Posts</h2>
					<h1><?php echo $postNum; ?></h1>
				</div>
				<div class="stat-column">
					<img src="../images/cp/enroll.png" alt="discussions" class="icon" />
					<h2>Number of Enrolled Students</h2>
					<h1><?php echo $studentNum; ?></h1>
				</div>
				<div class="stat-column">
					<img src="../images/cp/instructor.png" alt="discussions" class="icon" />
					<h2>Number of Instructors</h2>
					<h1><?php echo $instructorNum; ?></h1>
				</div>
			</div>
			<div class="stats">
				<div class="stat-column">
					<img src="../images/cp/reply.png" alt="discussions" class="icon" />
					<h2>Total Number of Replies</h2>
					<h1><?php echo $replyNum; ?></h1>
				</div>
				<div class="stat-column">
					<img src="../images/cp/question.png" alt="discussions" class="icon" />
					<h2>Total Number of Questions</h2>
					<h1><?php echo $questionNum; ?></h1>
				</div>
				<div class="stat-column">
					<img src="../images/cp/noanswer.png" alt="discussions" class="icon" />
					<h2>Unasnwered Questions</h2>
					<h1><?php echo $unAnsQuesNum; ?></h1>
				</div>
			</div>
		</div>
	</body>
</html>
<?php mysqli_close($connect_DB); ?>