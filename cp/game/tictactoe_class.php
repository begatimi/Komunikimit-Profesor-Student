<?php 

class Tictactoe extends Game {
	var $player = "X";			
	var $board = array();		
	var $totalMoves = 0;			
	
	function __construct() {
		$this->newBoard();
		game::start();
	}
	
	
	function newGame() {
		//setup the game
		$this->start();
		
		//reset the player
		$this->player = "X";
		$this->totalMoves = 0;
        $this->newBoard();
	}
	
	//funksioni qe krijon nje Board te zbrazet
	function newBoard() {

		$this->board = array();
        
        for ($x = 0; $x < 9; $x++) {
            $this->board[$x] = null;
        }
		
    }
	
	// funksioni qe i ruan X dhe O ne board
	public function storeXO($coordinate) {
		
		// kontrollo a eshte e zbrazet
		if ( $this->board[$coordinate] == null && !$this->over ) {
			$this->board[$coordinate] = $this->player; // vendos O ose X ne koordinaten e marrur
			$this->totalMoves++; // rrit nr. total te levizjeve

			if ( $this->totalMoves % 2 == 1 ) { // numer tek vendos X
				$this->player = "O";
			} else if ( $this->totalMoves % 2 == 0 ) { // numer cift vendos O
				$this->player = "X";
			}
		}		
	}
	
	// funk. qe ben shfaqjen e X ose O
	public function displayXO() {

		// skripta per vendosjen e X edhe O ne tabele
		echo "<script>";
		for ($x = 0; $x < 9; $x++) {
			if ($this->board[$x] != null) {
				echo "document.getElementById($x).innerHTML = \"". $this->board[$x] ."\";";
			}
		}
		echo "</script>";
		
		// shfaqja e mesazhit
		if ( $this->over ) {
			$this->showMessage();
		}
	}
	
	// funksioni per shfaqje te mesazhit
	function showMessage() {
		if ($this->isOver() != "Tie") {
			echo "<p>Congratulations player " . $this->isOver() . ", you've won the game!</p>";
			setcookie("wins", $_COOKIE["wins"]+1, time() + (86400 * 30) * 30, "/");
		} else if ($this->isOver() == "Tie") {
			echo "<p>Whoops! Looks like you've had a tie game. Want to try again?</p>";
			setcookie("tie", $_COOKIE["tie"]+1, time() + (86400 * 30) * 30, "/");
		}
			
				
		echo "<p><form method=\"post\" action=\"game.php\"><input type=\"submit\" name=\"newgame\" value=\"New Game\" /></form></p>";
	}
	
	// funksioni qe konrollon nese dikush ka fituar apo barazim
	function isOver() {
		
		//reshti pare
		if ( $this->board[0] != null && $this->board[1] != null && $this->board[2] != null ) {
			if ($this->board[0] == $this->board[1] && $this->board[1] == $this->board[2]) {
				$this->over = true;
				return $this->board[0];
			}
		}
			
			
		//reshti dyte
		if ( $this->board[3] != null && $this->board[4] != null && $this->board[5] != null ) {
			if ($this->board[3] == $this->board[4] && $this->board[4] == $this->board[5]) {
				$this->over = true;
				return $this->board[3];
			}
		}
		
		//reshti tret
		if ( $this->board[6] != null && $this->board[7] != null && $this->board[8] != null ) {
			if ($this->board[6] == $this->board[7] && $this->board[7] == $this->board[8]) {
				$this->over = true;
				return $this->board[6];
			}
		}
		
		//kolona pare
		if ( $this->board[0] != null && $this->board[3] != null && $this->board[6] != null ) {
			if ($this->board[0] == $this->board[3] && $this->board[3] == $this->board[6]) {
				$this->over = true;
				return $this->board[0];
			}
		}
		
		//kolona dyte
		if ( $this->board[1] != null && $this->board[4] != null && $this->board[7] != null ) {
			if ($this->board[1] == $this->board[4] && $this->board[4] == $this->board[7]) {
				$this->over = true;
				return $this->board[1];
			}
		}
		
		//kolona e tret
		if ( $this->board[2] != null && $this->board[5] != null && $this->board[8] != null ) {
			if ($this->board[2] == $this->board[5] && $this->board[5] == $this->board[8]) {
				$this->over = true;
				return $this->board[2];
			}
		}
			
		//diagonalja 1
		if ( $this->board[0] != null && $this->board[4] != null && $this->board[8] != null ) {
			if ($this->board[0] == $this->board[4] && $this->board[4] == $this->board[8]) {
				$this->over = true;
				return $this->board[0];
			}
		}
		
		//diagonalja 2
		if ( $this->board[2] != null && $this->board[4] != null && $this->board[6] != null ) {
			if ($this->board[2] == $this->board[4] && $this->board[4] == $this->board[6]) {
				$this->over = true;
				return $this->board[2];
			}
		}
		
		//bazarim
		if ($this->totalMoves > 8) {
			$this->over = true;
			return "Tie";
		}
	}
	
}

?>