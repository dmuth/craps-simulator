<?php

require __DIR__ . '/../vendor/autoload.php';
include("lib/player.php");

class PlayerTest extends PHPUnit_Framework_TestCase {

	protected function setup() {
		Logger::configure("logger-config.xml");
		$this->logger = Logger::getRootLogger();
		$this->logger->setLevel(LoggerLevel::getLevelError());
	}

	protected function teardown() {
	}


	public function testPlayer() {

		$strategy = array(
			"bet" => 10,
			);
		$player = new Craps\Player($this->logger, 100, $strategy);

		$player->event(PLAYER_BET);
		$this->assertEquals($player->getStats()["balance"], 90);
		$player->event(PLAYER_BET_ODDS, 6);
		$this->assertEquals($player->getStats()["balance"], 90);
		$player->event(PLAYER_WIN);
		$this->assertEquals($player->getStats()["balance"], 110);
		
		$player->event(PLAYER_BET);
		$this->assertEquals($player->getStats()["balance"], 100);
		$player->event(PLAYER_LOSE);
		$this->assertEquals($player->getStats()["balance"], 100);

    } // End of testHello()


	public function testPlayerOdds() {

		$strategy = array(
			"bet" => 10,
			"take_odds" => 1,
			);

		$player = new Craps\Player($this->logger, 100, $strategy);
		$player->event(PLAYER_BET);
		$this->assertEquals($player->getStats()["balance"], 90);
		$player->event(PLAYER_BET_ODDS, 6);
		$this->assertEquals($player->getStats()["balance"], 80);
		$player->event(PLAYER_WIN);
		$this->assertEquals($player->getStats()["balance"], 112);
		
		$player = new Craps\Player($this->logger, 100, $strategy);
		$player->event(PLAYER_BET);
		$this->assertEquals($player->getStats()["balance"], 90);
		$player->event(PLAYER_BET_ODDS, 5);
		$this->assertEquals($player->getStats()["balance"], 80);
		$player->event(PLAYER_WIN);
		$this->assertEquals($player->getStats()["balance"], 115);
		
		$player = new Craps\Player($this->logger, 100, $strategy);
		$player->event(PLAYER_BET);
		$this->assertEquals($player->getStats()["balance"], 90);
		$player->event(PLAYER_BET_ODDS, 4);
		$this->assertEquals($player->getStats()["balance"], 80);
		$player->event(PLAYER_WIN);
		$this->assertEquals($player->getStats()["balance"], 120);
		
	}


} // End of PlayerTest class


