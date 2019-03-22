<?php
session_start();
require_once("../DatabaseConfig.php");

$p = $_GET['p'];

$_SESSION['postid'] = $p;


// query per shfaqjen e postimeve
$query = "SELECT * FROM posts WHERE postid='$p'";
$res= mysqli_query($connect_DB,$query);

if($res) {
    $row = mysqli_fetch_assoc($res);
	
	echo "<div class=\"post-container\">";
	echo "<h1 class=\"post-title\">".$row['title']."</h1><hr/>";
	echo "<p class=\"post-message\">".$row['message']."</p>";
	echo "<p><small><strong>Posted by: </strong>".$row['poster']."</small><br/><small><strong>Time Posted:</strong> ".$row['time']."</p></small>";
	// fb like api
	echo "<iframe src=\"https://www.facebook.com/plugins/like.php?href=http%3A%2F%2Flocalhost%3A8090%2FProjekti&width=450&layout=standard&action=like&size=small&show_faces=true&share=true&height=80&appId\" width=\"450\" height=\"20\" style=\"border:none;overflow:hidden\" scrolling=\"no\" frameborder=\"0\" allowTransparency=\"true\"></iframe>";
	echo "</div>";
}

// query per shfaqjen e pergjigjjeve
$query = "SELECT * FROM post_reply WHERE postid='".$_SESSION['postid']."'";
$res= mysqli_query($connect_DB,$query);

if(mysqli_num_rows($res)>0) {
	while($row = mysqli_fetch_assoc($res)) {
		$replyer = $row["replyer"];
		$replyMsg = $row["replymessage"];
		
		echo "<div class=\"post-container\">";
		echo "<p>$replyMsg</p>";
		printf("<p><small><strong>Posted by: </strong>%s</small><br/><small><strong>Time Posted: </strong>%s</p></small>",$replyer,$row['replytime']); //perdorimi i printf
		// fb like api
		echo "<iframe src=\"https://www.facebook.com/plugins/like.php?href=http%3A%2F%2Flocalhost%3A8090%2FProjekti&width=450&layout=standard&action=like&size=small&show_faces=true&share=true&height=80&appId\" width=\"450\" height=\"20\" style=\"border:none;overflow:hidden\" scrolling=\"no\" frameborder=\"0\" allowTransparency=\"true\"></iframe>";
		echo "</div>";
	}
	
}

mysqli_close($connect_DB);
?>
<div style="margin: 20px;">
	<form action="discussions.php" method="post">
			<p>Write a reply</p>
			<textarea placeholder="Write your reply here" name="reply" rows="5" cols="60"></textarea>
			<button type="submit" name="btn-reply" style="display: block">Reply</button>			
	</form>
</div>