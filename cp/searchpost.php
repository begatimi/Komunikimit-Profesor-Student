<?php
session_start();
require_once("../DatabaseConfig.php");

$keyword = $_GET['k'];
$cid = $_GET['c'];

$query = "SELECT * FROM posts where cid ='$cid' and title like '%$keyword%' ORDER BY postid DESC";
					
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
		
		echo "<div class=\"post\" id=\"$postid\" onClick=\"showpost(this.id)\"><p><strong>$titleShorted</strong></p>"."<p class=\"feed-message\">$messageShorted</p>"."<p><small><strong>Time Posted:</strong> $time <br /><strong>Posted by:</strong> $poster</small></p></div>";
	}
	
}
?>
