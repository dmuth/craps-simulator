<?php


namespace Craps;


/**
* This class parses our command-line arguments.
*/
class Args {


	/**
	* Our constructor.
	*/
	function __construct() {
	} // End of __construct()


	/**
	* Parse our command-line arguments.
	*
	* @return array An associative array of our arguments that were parsed.
	*/
	function parse() {

		$retval = array();

		$args = new \Commando\Command();
		$args->beepOnError(false)
			->setHelp("Simulate games of craps")

			->flag("v")
			->describedAs("Verbose mode (logging at INFO level)")
			->boolean()

			->flag("vv")
			->describedAs("Really verbose mode (logging at DEBUG level)")
			->boolean()

			->flag("num-games")
			->describedAs("How many games to play?")

			->flag("players")
			->describedAs("Create a player with a comma-delimited list of the following attributes:"
				. "starting_balance,bet_amount[,taking_odds]\n"
				. "Multiple players can be separated by colons.\n\n"
				. "Example: 100,10,1 or 100,25\n"
				. "Exmaple with two players: 100,10,1:100,25,1"
				)

			->flag("bail-at")
			->describedAs("Quit the game when we reach the specified balance")

			->flag("debug-rolls")
			->describedAs("Comman-delimited list of dice rolls to insert for debugging purposes.\n"
				. "Additional dice rolls (if any) will be random.\n\n"
				. "Example: 7,2,8,8"
				)
			;

			$retval["v"] = $args["v"];
			$retval["vv"] = $args["vv"];
			$retval["num-games"] = $args["num-games"];
			$retval["debug-rolls"] = $args["debug-rolls"];
			$retval["bail-at"] = $args["bail-at"];
			$retval["players"] = $args["players"];

			$retval = $this->processArgs($retval);

			return($retval);

	} // End of parse()


	/**
	* Do further processing on our args, such as defaults, processing, etc.
	*
	* @param array Our array of arguments
	*
	* @return array Our updated associative array of arguments
	*/
	function processArgs($in) {

		$retval = $in;

		//
		// Default number of games.
		//
		if (!$retval["num-games"]) {
			$retval["num-games"] = 10;
		}

		//
		// Turn the rolls into arrays
		//
		if ($retval["debug-rolls"]) {
			$retval["debug-rolls"] = explode(",", $retval["debug-rolls"]);
		}

		//
		// Turn our players into arrays
		//
		if ($retval["players"]) {
			$retval["players"] = $this->processPlayers($retval["players"]);
		}

		return($retval);

	} // End of processArgs()


	/**
	* Process our players.
	*/
	function processPlayers($players_in) {

		$retval = array();

		//
		// Split into players and loop through each player
		//
		$players = explode(":", $players_in);

		foreach ($players as $key => $value) {

			//
			// Split player values, making sure we have a minimum of a starting 
			// balance and bet value.
			//
			// If we don't have both, just skip this player.
			//
			$player = explode(",", $value);
			if (!isset($player[1])) {
				continue;
			}

			$row = array();
			$row["balance"] = $player[0];
			$strategy = array();
			$strategy["bet"] = $player[1];

			//
			// Are we taking odds?
			//
			if (isset($player[2])) {
				$strategy["take_odds"] = true;
			}

			$row["strategy"] = $strategy;

			$retval[] = $row;

		}

		return($retval);

	} // End of processPlayers()


} // End of Args class



