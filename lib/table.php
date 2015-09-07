<?php


namespace Craps;

//
// Our types of rolls/game states
//
define("COME_OUT", "come out roll");
define("POINT", "point roll");

//
// The results of rolls
//
define("WIN", "win!");
define("LOSE", "lose");


/**
* Our table class.  This is used to handle dice rolls, keep game state,
* and is responsible for player management and interactions.
*/
class Table {

	var $logger;
	var $state;
	var $point;

	//
	// Keep track of statistics for this game.
	//
	var $stats;


	/**
	* Our constructor.
	*
	* @param object $logger Our logger.
	*/
	function __construct($logger) {
		$this->logger = $logger;

		$this->stats = array(
			"num_rolls" => 0,
			"wins" => 0,
			"losses" => 0,
			);

	}


	/**
	* Make a dice roll.
	*
	* @return integer Our roll of dice, between 2 and 12.
	*/
	function roll() {

		$retval = $this->rollDie() + $this->rollDie();
		$this->logger->info("Roll: $retval");
		$this->stats["num_rolls"]++;

		$result = $this->checkRoll($retval);
		$this->updateState($result);

		return($retval);

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
			$this->state = COME_OUT;
		}

		if ($this->state == COME_OUT) {
			//
			// This is our come out roll
			//
			if (in_array($roll, array(2, 3, 12))) {
				$this->logger->info("Crapped out");
				$retval = LOSE;
				$this->stats["losses"]++;

			} else if (in_array($roll, array(7, 11))) {
				$this->logger->info("Winner!");
				$retval = WIN;
				$this->stats["wins"]++;

			} else {
				$this->logger->info("The point is now: $roll");
				$this->point = $roll;

			}

		} else {
			//
			// Our point roll.  Try to roll your point 
			// number again before rolling a 7.	
			//
			if ($roll == 7) {
				$this->logger->info("Out!");
				$retval = LOSE;
				$this->stats["losses"]++;

			} else if ($roll == $this->point) {
				$this->logger->info("Winner!");
				$retval = WIN;
				$this->stats["wins"]++;

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

		if ($result == WIN) {
			$this->state = COME_OUT;

		} else if ($result == LOSE) {
			$this->state = COME_OUT;

		} else {
			if ($this->state == COME_OUT) {
				$this->state = POINT;
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


} // End of Table class


