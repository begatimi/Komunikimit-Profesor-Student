<?php
	require('header.php');
	require('file_exceptions.php');
	define("max_file_size",8*1024*1024);
			
	$validExt = array("jpg", "png", "pdf", "rar", "zip");
	$validMime = array("image/jpeg","image/png","application/pdf","application/zip","application/x-rar-compressed","application/octet-stream");
	

	if($_SERVER['REQUEST_METHOD']=='POST') {
		
		$error = false;

		foreach($_FILES as $fileKey => $fileArray) {
			if ($fileArray["size"] < max_file_size) {
				$filename = explode(".", $fileArray["name"]);
				if ( in_array($fileArray["type"],$validMime) && in_array(strtolower($filename[1]), $validExt) ) {
					
					$fileToMove = $_FILES['file1']['tmp_name'];
					$destination = "resources/$class/" . $_FILES["file1"]["name"];
					
					if (move_uploaded_file($fileToMove,$destination)) {
						// File open exception
						try {
							$myfile = fopen("resources/logs.txt", "a");
							if(!$myfile) {
								throw new fileOpenException(" ",-2);
							}
						}
						catch  (fileOpenException $fo) {
							echo $fo;
						}
						
						$logGenerator = $_FILES["file1"]["name"]." was uploaded by $fullname ($account)\n";
						
						// File write exception
						try {
							fwrite($myfile, $logGenerator);
							if(!$myfile) {
								throw new fileOpenException(" ",-2);
							}
							fclose($myfile);
						}
						catch  (fileOpenException $fo) {
							echo $fo;
						}
						
						unset($_FILES);
					} else {
						$error = true;
						$errorMsg = "there was a problem moving the file";
					}
				} else {
					$error = true;
					$errorMsg = $fileKey." has an invalid mime type or extension";
				}
			} else {
				$error = true;
				$errorMsg = "Error: " . $fileKey . " is too big";
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
				<li class="active"><a href="resources.php"><img src="../images/cp/resources.png" alt="resources" class="icon" /><span class="nav_item">Resources</span></a></li>
				<?php 
					if ($account == "student") {
						echo "<li><a href=\"game.php\"><img src=\"../images/cp/game.png\" alt=\"game\" class=\"icon\" /><span class=\"nav_item\">Game</span></a></li>";
					}
				?>	
				<li><a href="config.php"><img src="../images/cp/config.png" alt="config" class="icon" /><span class="nav_item">Configurations</span></a></li>
			</ul>
		</nav>
		<div id="content">
		<h1 style="margin-top: 0">Resources</h1>
		<hr/>
			<form enctype='multipart/form-data' method='post'>
				<input type='file' name='file1' style="margin-left: 40px;" />
				<button type="submit" name="btn-upload">Upload</button>
			</form>
		<hr/>
		<?php
			if ( $_SERVER['REQUEST_METHOD']=='POST' ) {
				if ($error) {
					echo "<p class=\"text-error\">$errorMsg</p>";
				}
			}
		?>
		<?php 
			
			$drname = "resources/$class";
		
			if (!file_exists($drname)) {
				mkdir($drname, 0777, true);
			}

			$directory = opendir($drname);
			$i = 0;
			$j = 0;
			
			echo "<table><tr><th>File No.</th><th>File Name</th><th>File Type</th></tr>";
			while ($file = readdir($directory))
			{
				$i++;
				
				if( $i > 2) {
					$j++;
					$fileName = explode(".",$file);
					echo "<tr><td>$j</td><td><a href=\"resources/$file\">" . $fileName[0] . "</a></td><td>". strtoupper($fileName[1]) ."</td>";
				}
			}
			echo "</table>";
			
			if ($account == "instructor" && $j > 0) {
				echo "<p><a href=\"resources/logs.txt\" target=\"_blank\">Click here</a> to view the logs!</p>";
			}
			
			closedir($directory);
			mysqli_close($connect_DB);
		?>
		
		</div>
	</body>
</html>
