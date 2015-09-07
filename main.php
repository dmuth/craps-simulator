#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
include("lib/table.php");
include("lib/player.php");

Logger::configure("logger-config.xml");
//$logger = Logger::getLogger("main");
$logger = Logger::getRootLogger();
$logger->setLevel(LoggerLevel::getLevelInfo());

$table = new Craps\Table($logger);

$strategy = array(
	"bet" => 10,
	"take_odds" => true,
	);

$players = array();
//$players[] = new Craps\Player($logger, 100, $strategy);

foreach ($players as $key => $value) {
	$table->addPlayer($value);
}

$num_games = 3;
$i=1;

$game_id = "";
$game_id_old = "";
while($i < $num_games) {

	$table->roll();
	$game_id = $table->getGameId();
	if (!$game_id_old) {
		$logger->info("New (first) game: $game_id");
		$game_id_old = $game_id;
	}

	if ($game_id != $game_id_old) {
		$logger->info("New game: $game_id");
		$game_id_old = $game_id;
		$i++;
	}

} // while()...


$logger->info("Table stats: " . json_encode($table->getStats()));

if (count($players)) {
	$logger->info("Player stats: ");
}

foreach ($players as $key => $value) {
	$logger->info("Player1: " . json_encode($value->getStats()));
}

print "All done!\n";



