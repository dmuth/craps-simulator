<?php


namespace Craps;


/**
* Our stats class.  This is used to creating stats reports on tables 
* and players.
*/
class Stats {

	var $logger;
	var $table;
	var $players;

	var $green = "\033[32m";
	var $yellow = "\033[33m";
	var $red = "\033[31m";
	var $default = "\033[39m";


	/**
	* Our constructor.
	*
	* @param object $logger Our logger
	* @param object $table Our table object
	* @param array $players Our array of players.
	*/
	function __construct($logger, $table, $players) {

		$this->logger = $logger;
		$this->table = $table;
		$this->players = $players;

	} // End of __construct()


	/**
	* Print out our stats.
	*/
	function printStats() {

		$this->printStatsTable($this->table);
		$this->printStatsPlayers($this->players);

	} // End of printStats()


	/**
	* Print up our stats for the table.
	*/
	function printStatsTable($table) {

		$stats = $table->getStats();
		$dice_rolls = $stats["dice_rolls"];
		$report = sprintf(""
			. "Table Stats:\n"
			. "============\n"
			. "\n"
			. "%15s: %5d\n"
			. "%15s: %5d\n"
			. "%15s: $this->green%5d$this->default\n"
			. "%15s: $this->red%5d$this->default\n"
			. "%15s: \n"
			. "%15s: %5d    %5s: %5d\n"
			. "%15s: %5d    %5s: %5d\n"
			. "%15s: %5d    %5s: %5d\n"
			. "%15s: %5d    %5s: %5d\n"
			. "%15s: %5d    %5s: %5d\n"
			. "%31s: %5d\n"
			. "\n",
			"Games Played",
			$stats["num_games"],
			"Rolls Made",
			$stats["num_rolls"],
			"Wins",
			$stats["wins"],
			"Losses",
			$stats["losses"],
			"Dice Rolls",
			2, $dice_rolls[2], 7, $dice_rolls[7],
			3, $dice_rolls[3], 8, $dice_rolls[8],
			4, $dice_rolls[4], 9, $dice_rolls[9],
			5, $dice_rolls[5], 10, $dice_rolls[10],
			6, $dice_rolls[6], 11, $dice_rolls[11],
			12, $dice_rolls[12]
			);

		print $report;

	} // End of printStatsTable()


	/**
	* Print up stats on our players.
	*/
	function printStatsPlayers($players) {

		$report = "Player Stats:\n"
			. "=============\n"
			;

		foreach ($players as $key => $value) {
			//$this->logger->info("Player: " . json_encode($value->getStats()));
			$stats = $value->getStats();

			$report .= sprintf(""
				. "%15s: %20s\n"
				. "%15s: %10d\n"
				. "%15s: %10d\n"
				. "%15s: %10d\n"
				. "%15s: $this->green%10.2f$this->default\n"
				. "%15s: $this->red%10.2f$this->default\n"
				. "%15s: %10.2f\n"
				. "\n",
				"Name", $stats["name"],
				"Games Played", $stats["stats"]["num_games"],
				"Wins", $stats["stats"]["wins"],
				"Losses", $stats["stats"]["losses"],
				"Amount Won", $stats["stats"]["amount_won"],
				"Amount Lost", $stats["stats"]["amount_lost"],
				"Balance", $stats["balance"]
				);

		}

		print $report;

	} // End of printStatsPlayer()


} // End of Stats class


