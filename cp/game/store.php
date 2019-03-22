<?php
	require_once('game_class.php');
	require_once('tictactoe_class.php');
	
	session_start();

	$id = $_POST['id'];

	$_SESSION['tictactoe']->storeXO($id);
	
	if ( $_SESSION['tictactoe']->totalMoves > 2) {
		$_SESSION['tictactoe']->isOver();
	}
	
	$_SESSION['tictactoe']->displayXO();
?>
