<?php


namespace Craps;

//
// Our types of rolls/game states
//
define("TABLE_COME_OUT", "come out roll");
define("TABLE_POINT", "point roll");

//
// The results of rolls
//
define("TABLE_WIN", "win!");
define("TABLE_LOSE", "lose");


/**
* Our table class.  This is used to handle dice rolls, keep game state,
* and is responsible for player management and interactions.
*/
class Table {

	var $logger;
	var $players;
	var $state;
	var $point;
	var $game_id;

	//
	// Debugging data used for white-box testing purposes.
	//
	var $debug;

	//
	// Keep track of statistics for this game.
	//
	var $stats;


	/**
	* Our constructor.
	*
	* @param object $logger Our logger.
	*
	*/
	function __construct($logger) {

		$this->logger = $logger;
		$this->players = array();
		$this->debug = array();

		$this->stats = array(
			"num_games" => 0,
			"num_rolls" => 0,
			"wins" => 0,
			"losses" => 0,
			"dice_rolls" => array(
				2 => 0,
				3 => 0,
				4 => 0,
				5 => 0,
				6 => 0,
				7 => 0,
				8 => 0,
				9 => 0,
				10 => 0,
				11 => 0,
				12 => 0,
				),
			);

	} // End of __construct()


	/**
	* Play multiple games of craps
	*
	* @param integer $num_games How many games to play?
	*
	*/
	function play($num_games) {

		while (true) {

			//
			// Did we run out of games to play?
			//
			if ($num_games <= 0) {
				break;
			}

			$this->playGame();
			$num_games--;
			$this->stats["num_games"]++;

		}

	} // End of play()


	/**
	* Play a game of craps.
	*
	*/
	private function playGame() {

		while (true) {
			$roll = $this->roll();
			if ($roll == TABLE_WIN || $roll == TABLE_LOSE) {
				break;
			}	
		}

		return(null);

	} // End of playGame()


	/**
	* Make a dice roll.
	*
	* @return string Our result.  An empty string is returned 
	*	if the player neither wins nor loses.  Valid results 
	*	can be TABLE_WIN or TABLE_LOSE.
	*
	*/
	private function roll() {

		$roll = $this->rollDie() + $this->rollDie();

		//
		// Are we overriding the roll with a debug value?
		//
		if (isset($this->debug["rolls"])) {
			if (count($this->debug["rolls"])) {
				$roll = array_shift($this->debug["rolls"]);
			}
		}

		$this->logger->info("Roll: $roll");
		$this->stats["num_rolls"]++;
		$this->stats["dice_rolls"][$roll]++;

		$result = $this->checkRoll($roll);
		$this->updateState($result);

		return($result);

	} // End of roll()


	/**
	* Roll a single die.
	*/
	private function rollDie() {
		$retval = mt_rand(1, 6);
		return($retval);
	}


	/**
	* Checks a roll and determines if we won, lost, or neither, 
	* based on our roll and the current state.
	*
	* @param integer $roll The current roll
	*
	* @return string Our result.  An empty string is returned 
	*	if the player neither wins nor loses.
	*/
	private function checkRoll($roll) {

		$retval = "";

		//
		// First roll of a new game?
		//
		if (!$this->state) {
			$this->state = TABLE_COME_OUT;
		}

		if ($this->state == TABLE_COME_OUT) {
			//
			// This is our come out roll
			//
			$this->sendPlayerEvent(PLAYER_NEW_GAME);
			$this->sendPlayerEvent(PLAYER_BET);

			//
			// Assign a UUID to this game.
			// Sure, we could use an autoincrementing integer, but in
			// the real world, we might have a situation where we have
			// many tables, and we'd want a UUID for each.
			//
			$this->game_id = \Rhumsaa\Uuid\Uuid::uuid4()->toString();
			$this->logger->debug("Assigned game ID: " . $this->game_id);

			if (in_array($roll, array(2, 3, 12))) {
				$this->logger->info("Crapped out (Game ID: $this->game_id)");
				$retval = TABLE_LOSE;
				$this->stats["losses"]++;
				$this->sendPlayerEvent(PLAYER_LOSE);

			} else if (in_array($roll, array(7, 11))) {
				$this->logger->info("Winner! (Game ID: $this->game_id)");
				$retval = TABLE_WIN;
				$this->stats["wins"]++;
				$this->sendPlayerEvent(PLAYER_WIN);

			} else {
				$this->logger->info("The point is now: $roll");
				$this->point = $roll;
				$this->sendPlayerEvent(PLAYER_BET_ODDS, $roll);

			}

		} else {
			//
			// Our point roll.  Try to roll your point 
			// number again before rolling a 7.	
			//
			if ($roll == 7) {
				$this->logger->info("Out! (Game ID: $this->game_id)");
				$retval = TABLE_LOSE;
				$this->stats["losses"]++;
				$this->sendPlayerEvent(PLAYER_LOSE);

			} else if ($roll == $this->point) {
				$this->logger->info("Winner! (Game ID: $this->game_id)");
				$retval = TABLE_WIN;
				$this->stats["wins"]++;
				$this->sendPlayerEvent(PLAYER_WIN);

			}

		}

		return($retval);

	} // End of checkRoll()


	/**
	* Update our state based on the result of the roll.
	* This includes making payouts to players.
	*/
	function updateState($result) {

		$this->logger->debug("Old state: " . $this->state);
		$this->logger->debug("Result: $result");

		if ($result == TABLE_WIN) {
			$this->state = TABLE_COME_OUT;

		} else if ($result == TABLE_LOSE) {
			$this->state = TABLE_COME_OUT;

		} else {
			if ($this->state == TABLE_COME_OUT) {
				$this->state = TABLE_POINT;
			}

		}

		$this->logger->debug("New state: " . $this->state);

	} // End of updateState()


	/**
	* Return our current game state.
	*/
	function getState() {
		return($this->state);
	}


	/**
	* Return the stats on this table.
	*/
	function getStats() {
		return($this->stats);
	}


	/**
	* Return the ID for the current game.
	*/
	function getGameId() {
		return($this->game_id);
	}


	/**
	* Add a player to the game.
	*/
	function addPlayer($player) {
		$this->players[] = $player;
	} // End of addPlayer()


	/**
	* Send an event to all players
	*
	* @param string $event The event to send to each player
	*/
	function sendPlayerEvent($event) {

		foreach ($this->players as $key => $value) {
			call_user_func_array(array($value, "event"), func_get_args());
		}

	} // End of sendPlayerEvent()


	/**
	* Set a debug value.
	*/
	function debugSet($key, $value) {
		$this->debug[$key] = $value;
	}

} // End of Table class


