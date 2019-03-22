<?php
	session_start();

	if (isset($_GET['logout'])) {
		unset($_SESSION['email']);
		session_unset();
		session_destroy();
		header("Location: ../home.php");
		exit;
	}
?>