#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
include("lib/table.php");
include("lib/player.php");
include("lib/stats.php");

Logger::configure("logger-config.xml");
//$logger = Logger::getLogger("main");
$logger = Logger::getRootLogger();
$logger->setLevel(LoggerLevel::getLevelInfo());

$table = new Craps\Table($logger);
//$num_games = 1; $table->debugSet("rolls", array(2));
//$num_games = 1; $table->debugSet("rolls", array(4,7)); // Take odds, lose
$num_games = 1; $table->debugSet("rolls", array(4,4)); // Take odds, win
//$num_games = 2; $table->debugSet("rolls", array(2,7));
//$num_games = 3; $table->debugSet("rolls", array(2,7,8,7));
//$num_games = 4; $table->debugSet("rolls", array(2,7,8,7,8,8));
//$num_games = 5; $table->debugSet("rolls", array(2,7,8,7,8,8,7));

$strategy = array(
	"bet" => 10,
	"take_odds" => true,
	);

$players = array();
$players[] = new Craps\Player($logger, 100, $strategy);
//$players[] = new Craps\Player($logger, 1000, $strategy);

foreach ($players as $key => $value) {
	$table->addPlayer($value);
}

$result = $table->play($num_games);

$stats = new Craps\Stats($logger, $table, $players);
$stats->printStats();

print "All done!\n";



