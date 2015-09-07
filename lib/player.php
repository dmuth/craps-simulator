<?php


namespace Craps;

//
// Events we can send to a player
//
define("PLAYER_NEW_GAME", "new game");
define("PLAYER_BET", "bet");
define("PLAYER_BET_ODDS", "taking odds");
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
	// What was rolled?  This is only useful when Taking Odds
	//
	var $roll;

	//
	// How much money is currently on the table?
	//
	var $amount_bet;

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
		$this->amount_bet = 0;
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
		$args = func_get_args();

		if ($event == PLAYER_NEW_GAME) {
			$this->stats["num_games"]++;
			$this->roll = "";
		}

		//
		// Opportunity to place a bet or take odds
		//
		if ($event == PLAYER_BET) {
			$this->placeBet();

		} else if ($event == PLAYER_BET_ODDS) {
			$roll = $args[1];
			$this->placeBetOdds($roll);

		}

		if ($event == PLAYER_WIN) {
			$this->payout();
			$this->stats["wins"]++;

		} else if ($event == PLAYER_LOSE) {
			$this->stats["losses"]++;
			$this->stats["amount_lost"] += $this->amount_bet;

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
		$this->amount_bet = $amount;
		$this->logger->info("Placed bet of $${amount} on Pass (Player $this->name)");
		return(true);

	} // End of placeBet()


	/**
	* Take odds on the shooter.  We need to keep track of 
	* what the point number is, since the payout is dependent
	* on what the point number is.
	*
	* @return boolean True if a bet is successfully, placed, false otherwise.
	*
	*/
	function placeBetOdds($roll) {

		if (!$this->strategy["take_odds"]) {
			return(null);
		}

		$amount = $this->strategy["bet"];
		if ($amount > $this->balance) {
			$this->logger->info("Our balance ($this->balance) can't cover betting $amount, bailing out! (Player $this->name)");
			return(false);
		}

		$this->roll = $roll;
		$this->balance -= $amount;
		$this->amount_bet += $amount;
		$this->logger->info("Took odds of $${amount} on point $roll (Player $this->name)");
		return(true);

	} // End of placeBetOdds()


	/**
	* Handle payouts for a player that won.
	*/
	private function payout() {

		$bet = $this->strategy["bet"];
		$amount = $bet;
		$this->logger->info("Received payout of $${amount} (Player $this->name)");
		$this->balance += $bet + $amount;
		$this->stats["amount_won"] += $amount;

		if ($this->strategy["take_odds"] && $this->roll) {
			if ($this->roll == 4 || $this->roll == 10) {
				$amount = $bet * (2/1) - $bet;

			} else if ($this->roll == 5 || $this->roll == 9) {
				$amount = $bet * (3/2) - $bet;

			} else if ($this->roll == 6 || $this->roll == 8) {
				$amount = $bet * (6/5) - $bet;

			}

			$this->logger->info(sprintf("Oh, we took odds on point of %s as well! Here's an extra $%.2f! (Player $this->name)", 
				$this->roll, $amount));
			$this->balance += $bet + $amount;
			$this->stats["amount_won"] += $amount;

		}

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


