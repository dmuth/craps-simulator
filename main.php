#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
include("lib/table.php");
include("lib/player.php");

Logger::configure("logger-config.xml");
//$logger = Logger::getLogger("main");
$logger = Logger::getRootLogger();
$logger->setLevel(LoggerLevel::getLevelInfo());

$num_games = 5;
//$num_games = 3;
$table = new Craps\Table($logger, $num_games);
//$table->debugSet("rolls", array(7,7,7));
//$table->debugSet("rolls", array(7,7,6));
//$table->debugSet("rolls", array(2,7,8,7,8,8,7));

$strategy = array(
	"bet" => 10,
	"take_odds" => true,
	);

$players = array();
$players[] = new Craps\Player($logger, 100, $strategy);

foreach ($players as $key => $value) {
	$table->addPlayer($value);
}

while (true) {
	$result = $table->play();
	if (!$result) {
		break;
	}
}

$logger->info("Table stats: " . json_encode($table->getStats()));

if (count($players)) {
	$logger->info("Player stats: ");
}

foreach ($players as $key => $value) {
	$logger->info("Player1: " . json_encode($value->getStats()));
}

print "All done!\n";



