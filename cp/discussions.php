<?php
	require('header.php');
	// Shtimi i nje postimi te ri
	if ( isset($_POST['btn-newpost']) ) {
	
		$postType = $_POST['post-type'];		
		$postTo = $_POST['post-to'];
		
		$title = trim($_POST['title']);
		$title = strip_tags($title);
		$title = htmlspecialchars($title);
		
		$message = trim($_POST['message']);
		$message = strip_tags($message);
		$message = htmlspecialchars($message);
		
		$error = false;
		
		if ( empty($title) ) {
			$error = true;
			$errorMsg = "Please enter the post title!";
		} else if (empty($message)) {
			$error = true;
			$errorMsg = "Please enter the post message!";
		} else {
			$errorMsg = "";
		}
		
		if (!$error) {
			if($account == "student") {
				$query = "INSERT INTO posts (cid, uid, pid, sid, ptype, pto, poster, title, message, time) VALUES ('$cid', '$university', null, '$sid', '$postType', '$postTo', '$fullname', '$title', '$message', now() );";
			} else {
				$query = "INSERT INTO posts (cid, uid, pid, sid, ptype, pto, poster, title, message, time) VALUES ('$cid', '$university', '$pid', null, '$postType', '$postTo', '$fullname', '$title', '$message', now() );";
			}
			
			$res = mysqli_query($connect_DB,$query);

			if ($res) {
				unset($postType);
				unset($postTo);
				unset($title);
				unset($message);
			} else {
				$error = true;
				$errorMsg = "<strong>Something went wrong, try again later...</strong>"; 
			}

		}
	}
?>		
		<script>
		$(document).ready(function () {
			// ndryshimi i background per postimin e klikuar
			$('.post-sidebar').on('click', '.post', function(event) {
				event.preventDefault();
				$('.post').removeClass('selected');
				$(this).addClass('selected');
			});
			// thirrja e newpost.html
			$('#new-post').click(function() {
				$('.post').removeClass('selected');
				$('#show-post').load('newpost.html');
			});
		});
		</script>
		<script src="searchpost.js" type="text/javascript"></script>
		<script src="showpost.js" type="text/javascript"></script>
		<!-- page content -->
		<nav class="sidenav">
			<img src="../images/find_user.png" alt="userpic" style="display: block; padding: 15px; margin: auto" />
			<h3 class="user-info"><?php echo $fullname; ?></h3>
			<h6 class="user-info"><?php echo $email; ?></h6>
			<ul>
				<li><a href="home.php"><img src="../images/cp/dashboard.png" alt="dashboard" class="icon" /><span class="nav_item">Dashboard</span></a></li>
				<li class="active"><a href="discussions.php"><img src="../images/cp/discussion.png" alt="discussions" class="icon" /><span class="nav_item">Discussions</span></a></li>
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
		<div id="content" style="padding:0px;">
			<div class="post-sidebar">
				<div id="post-serach" >
					<button id="new-post">New Post</button>
					<input type="text" name="search" placeholder="Search..." onkeyup="searchPost(this.value,<?php echo $cid; ?>)" />
				</div>
				<div id="post-feed" id="post-feed">
					<!-- Postimet vendosen ketu me jquery -->
				</div>
				<?php 
					$query = "SELECT * FROM posts WHERE cid ='$cid' ORDER BY postid DESC";
					
					$res=mysqli_query($connect_DB, $query);

					if(mysqli_num_rows($res)>0) {
						while($row = mysqli_fetch_assoc($res)) {
							$postid = $row["postid"];
							
							$title = $row["title"];
							$titleShorted = strlen($title) > 40 ? substr($title,0,40)."..." : $title;
							
							$message = $row["message"];
							$messageShorted = strlen($message) > 100 ? substr($message,0,100)."..." : $message;
							
							$poster = $row["poster"];
							
							$time = $row["time"];
							
							$post = "'<div class=\"post\" id=\"$postid\" onClick=\"showpost(this.id)\"><p><strong>$titleShorted</strong></p>"."<p class=\"feed-message\">$messageShorted</p>"."<p><small><strong>Time Posted:</strong> $time <br /><strong>Posted by:</strong> $poster</small></p></div>'";
							
							echo "<script>$('#post-feed').append($post)</script>";
						}
						
					}
					
					$query = "SELECT * FROM posts WHERE cid ='$cid' ORDER BY postid DESC";
					
					$res=mysqli_query($connect_DB, $query);

					if(mysqli_num_rows($res)>0) {
						while($row = mysqli_fetch_assoc($res)) {
							$postid = $row["postid"];
							
							$title = $row["title"];
							$titleShorted = strlen($title) > 40 ? substr($title,0,40)."..." : $title;
							
							$message = $row["message"];
							$messageShorted = strlen($message) > 100 ? substr($message,0,100)."..." : $message;
							
							$poster = $row["poster"];
							
							$time = $row["time"];
							
							$post = "'<div class=\"post\" id=\"$postid\" onClick=\"showpost(this.id)\"><p><strong>$titleShorted</strong></p>"."<p class=\"feed-message\">$messageShorted</p>"."<p><small><strong>Time Posted:</strong> $time <br /><strong>Posted by:</strong> $poster</small></p></div>'";
							
							echo "<script>$('#post-feed').append($post)</script>";
						}
						
					}
				?>
				
			</div>
			<div class="post-content">
				<div id="show-post">
					<!-- Postimi vendosen ketu me jquery -->
				</div>
			</div>
		</div>
	</body>
</html>

<?php
	// Postimi i pergjigjjeve
	if ( isset($_POST['btn-reply']) ) {
		$replyMsg = $_POST['reply'];
		
		if($account == "student") {
			$query = "INSERT INTO post_reply (postid, pid, sid, replyer, replymessage, replytime) VALUES ('".$_SESSION['postid']."', null, '$sid', '$fullname', '$replyMsg', now());";
		} else {	
			$query = "INSERT INTO post_reply (postid, pid, sid, replyer, replymessage, replytime) VALUES ('".$_SESSION['postid']."', '$pid', null, '$fullname', '$replyMsg', now());";
		}

		$res = mysqli_query($connect_DB,$query);

		if ($res) {
			unset($replyMsg);
			echo "	<script>
						$.post(\"showpost.php\", { postid: ".$_SESSION['postid']." }, function (data) {
									$(\"#show-post\").html(data);
								})
					</script>";
		} else {
			$error = true;
			$errorMsg = "<strong>Something went wrong, try again later...</strong>"; 
		}
	
	}
	mysqli_close($connect_DB);
?>


