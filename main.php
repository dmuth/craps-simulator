#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
include("lib/args.php");
include("lib/table.php");
include("lib/player.php");
include("lib/stats.php");

$args = new Craps\Args();
$config = $args->parse();
//print_r($config); // Debugging

//
// Set up logging.
//
Logger::configure("logger-config.xml");
$logger = Logger::getRootLogger();
if ($config["vv"]) {
	$logger->setLevel(LoggerLevel::getLevelDebug());

} else if ($config["v"]) {
	$logger->setLevel(LoggerLevel::getLevelInfo());

} else {
	$logger->setLevel(LoggerLevel::getLevelError());

}

$epoch_id = \Rhumsaa\Uuid\Uuid::uuid4()->toString();

//
// Set up our table
//
$table = new Craps\Table($logger, $epoch_id);
$num_games = $config["num-games"];

//
// Add in our debugging rolls (if we have any)
//
if ($config["debug-rolls"]) {
		$table->debugSet("rolls", $config["debug-rolls"]);
}

//
// Create our players.
//
$players = array();
if (isset($config["players"])) {
	foreach ($config["players"] as $key => $value) {
		$players[] = new Craps\Player($logger, $value["balance"], $value["strategy"]);
	}
}

foreach ($players as $key => $value) {
	$table->addPlayer($value);
}

$result = $table->play($num_games, $epoch_id);

$stats = new Craps\Stats($logger, $table, $players);

if (!$config["no-output"]) {
	$stats->printStats();
}

if ($config["output-kv"]) {
	$stats->printStatsKv();
}

print "All done!\n";



