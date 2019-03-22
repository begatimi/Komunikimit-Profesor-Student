<?php
	require_once('game_class.php');
	require_once('tictactoe_class.php');	
	
	
	// krijimi nje loje te re (fshirja e sesionit)
	if (isset($_POST['newgame'])) {
		unset($_SESSION["tictactoe"]);
		setcookie("games_played", $_COOKIE["games_played"]+1, time() + (86400 * 30) * 30, "/");
	}
	
	$gamesPlayed = 1;
	$won = 0;
	$tie = 0;
	
	// cookie qe numron sa here eshte luajtur loja
	if(!isset($_COOKIE["games_played"])) {
		setcookie("games_played", 1, time() + (86400 * 30) * 30, "/");
		echo "<script>alert(\"new cookie\");</script>";
	} else {
		$gamesPlayed = $_COOKIE["games_played"];
	}
	
	// cookie qe numron fitoret
	if(!isset($_COOKIE["wins"])) {
		setcookie("wins", 0, time() + (86400 * 30) * 30, "/");
	} else {
		$won = $_COOKIE["wins"];
	}
	
	// cookie qe numron barazimet
	if(!isset($_COOKIE["tie"])) {
		setcookie("tie", 0, time() + (86400 * 30) * 30, "/");
	} else {
		$tie = $_COOKIE["tie"];
	}
	
	
	

		
	
	if (!isset($_SESSION['tictactoe'])) {
		$_SESSION['tictactoe'] = new Tictactoe();
	}
	
	$session = serialize($_SESSION['tictactoe']);
?>



<table border="1px" id="tictactoe-board">
<?php 
	for( $i = 0; $i < 9; $i+=3 ) {
		echo "<tr><td id=\"".$i."\" class=\"board-cell\"></td><td id=\"".($i+1)."\" class=\"board-cell\"></td><td id=\"".($i+2)."\" class=\"board-cell\"></td></tr>";
	}
?>
</table>

<p id="tictactoe-result"></p>

<table style="position: absolute; right:30px; top:30px; width: 350px;">
	<th colspan='2'>Statistics</th>
	<tr>
		<td>Games Played</td>
		<td><?php echo $gamesPlayed; ?></td>
	</tr>
	<tr>
		<td>Games Won</td>
		<td><?php echo $won; ?></td>
	</tr>
	<tr>
		<td>Games Tie</td>
		<td><?php echo $tie; ?></td>
	</tr>
</table>

<?php 
	if (isset($_SESSION['tictactoe'])) {
		if (unserialize($session)->totalMoves != 0) {
			unserialize($session)->displayXO();
		}
	}
?>

<script>
	$(document).ready(function () {
		$('.board-cell').click(function (event) {
			var vid = $(this).attr('id');
			$.post("game/store.php", { id: vid }, function (data) {
				$("#tictactoe-result").html(data);
			});
		});
	})
</script>