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
				. "%15s: %10d\n"
				. "%15s: %10d\n"
				. "%15s: %10d\n"
				. "%15s: $this->green%10.2f$this->default\n"
				. "%15s: $this->red%10.2f$this->default\n"
				. "%15s: %10.2f\n"
				. "\n",
				"Name", $stats["name"],
				"Games Played", $stats["stats"]["num_games"],
				"Bet", $stats["stats"]["strategy"]["bet"],
				"Took Odds?", $stats["stats"]["strategy"]["take_odds"],
				"Bail At", $stats["stats"]["strategy"]["bail_at"],
				"Wins", $stats["stats"]["wins"],
				"Losses", $stats["stats"]["losses"],
				"Amount Won", $stats["stats"]["amount_won"],
				"Amount Lost", $stats["stats"]["amount_lost"],
				"Balance", $stats["balance"]
				);

		}

		print $report;

	} // End of printStatsPlayer()


	/**
	* Print stats in key=value format
	*/
	function printStatsKv() {
		$this->printStatsTableKv($this->table);
		$this->printStatsPlayersKv($this->players);
	} // End of printStatsKv()


	/**
	* Print up our stats for the table.
	*/
	function printStatsTableKv($table) {

		$stats = $table->getStats();
		$dice_rolls = $stats["dice_rolls"];

		$report = sprintf("KV: "
			. "\ttype=table"
			. "\tnum_games=%d"
			. "\tnum_rolls=%d"
			. "\twins=%d"
			. "\tlosses=%d"
			. "\tdice_roll_%d=%d"
			. "\tdice_roll_%d=%d"
			. "\tdice_roll_%d=%d"
			. "\tdice_roll_%d=%d"
			. "\tdice_roll_%d=%d"
			. "\tdice_roll_%d=%d"
			. "\tdice_roll_%d=%d"
			. "\tdice_roll_%d=%d"
			. "\tdice_roll_%d=%d"
			. "\tdice_roll_%d=%d"
			. "\n",
			$stats["num_games"],
			$stats["num_rolls"],
			$stats["wins"],
			$stats["losses"],
			2, $dice_rolls[2], 7, $dice_rolls[7],
			3, $dice_rolls[3], 8, $dice_rolls[8],
			4, $dice_rolls[4], 9, $dice_rolls[9],
			5, $dice_rolls[5], 10, $dice_rolls[10],
			6, $dice_rolls[6], 11, $dice_rolls[11],
			12, $dice_rolls[12]
			);
		print $report;

	} // End of printStatsTableKv()


	/**
	* Print up stats on our players.
	*/
	function printStatsPlayersKv($players) {

		$report = "";

		foreach ($players as $key => $value) {
			//$this->logger->info("Player: " . json_encode($value->getStats()));
			$stats = $value->getStats();

			$report .= sprintf("KV: "
				. "\ttype=player"
				. "\tname=\"%s\""
				. "\tnum_games=%s"
				. "\tbet=%s"
				. "\ttake_odds=%s"
				. "\tbail_at=%s"
				. "\twins=%s"
				. "\tlosses=%s"
				. "\tamount_won=%s"
				. "\tamount_lost=%s"
				. "\tbalance=%s"
				. "\n",
				$stats["name"],
				$stats["stats"]["num_games"],
				$stats["stats"]["strategy"]["bet"],
				$stats["stats"]["strategy"]["take_odds"],
				$stats["stats"]["strategy"]["bail_at"],
				$stats["stats"]["wins"],
				$stats["stats"]["losses"],
				$stats["stats"]["amount_won"],
				$stats["stats"]["amount_lost"],
				$stats["balance"]
				);

		}

		print $report;

	} // End of printStatsPlayerKv()


} // End of Stats class


