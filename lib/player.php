<?php


namespace Craps;

//
// Events we can send to a player
//
define("NEW_GAME", "new game");
define("BET", "bet");
define("BET_ODDS", "taking odds");
define("PLAYER_WIN", "I won!");
define("PLAYER_LOSE", "I lost");


/**
* Our player class.  This handles receiving events that 
* players would get.
*/
class Player {

	var $logger;
	var $balance;
	var $name;
	var $strategy;

	//
	// Keep track of stats for this player.
	//
	var $stats;


	/**
	* Our constructor.
	*
	* @param object $logger Our logger.
	* @param integer $balance Our starting balance.
	* @param array $strategy Our better strategy
	*/
	function __construct($logger, $balance, $strategy) {

		$this->logger = $logger;
		$this->balance = $balance;
		$this->strategy = $strategy;
		$this->name = \Rhumsaa\Uuid\Uuid::uuid4()->toString();

		$this->stats = array(
			"num_games" => 0,
			"wins" => 0,
			"losses" => 0,
			"amount_won" => 0,
			"amount_lost" => 0,
			);

	}


	/**
	* Process an event that was sent to us.
	*/
	function event($event) {
		$this->logger->debug("Received event: $event");

		if ($event == NEW_GAME) {
			$this->stats["num_games"]++;
		}

		//
		// Opportunity to place a bet or take odds
		//
		if ($event == BET) {
			$this->placeBet();
		} else if ($event == BET_ODDS) {
		}

		if ($event == PLAYER_WIN) {
			$this->payout();
			$this->stats["wins"]++;
		} else if ($event == PLAYER_LOSE) {
			$this->stats["losses"]++;
		}

	}


	/**
	* Handle placing a bet.
	*
	* @return boolean True if a bet is successfully, placed, false otherwise.
	*/
	private function placeBet() {

		$amount = $this->strategy["bet"];
		if ($amount > $this->balance) {
			$this->logger->info("Our balance ($this->balance) can't cover betting $amount, bailing out!");
			return(false);
		}

		$this->balance -= $amount;
		$this->logger->info("Placed bet of $${amount} on Pass");
		return(true);

	} // End of placeBet()


	/**
	* Handle payouts for a player that won.
	*/
	private function payout() {

		$amount = $this->strategy["bet"] * 2;
		$this->logger->info("Received payout of $${amount}");
		$this->balance += $amount;

	} // End of payout()


	/**
	* Return stats for this player.
	*/
	function getStats() {

		$retval = array();
		$retval["name"] = $this->name;
		$retval["balance"] = $this->balance;
		$retval["stats"] = $this->stats;

		return($retval);

	} // End of getStats()


} // End of Player class


