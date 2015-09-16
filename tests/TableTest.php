<?php

require __DIR__ . '/../vendor/autoload.php';
include("lib/table.php");


class TableTest extends PHPUnit_Framework_TestCase {

	protected function setup() {
		Logger::configure("logger-config.xml");
		$this->logger = Logger::getRootLogger();
		$this->logger->setLevel(LoggerLevel::getLevelError());
	}

	protected function teardown() {
	}


	public function testTable() {

		$table = new Craps\Table($this->logger);

		$strategy = array(
			"bet" => 10,
			"bail_at" => 9999999,
			);
		$player = new Craps\Player($this->logger, 100, $strategy);
		$strategy["take_odds"] = 1;
		$player2 = new Craps\Player($this->logger, 100, $strategy);
		$table->addPlayer($player);
		$table->addPlayer($player2);

		$table->debugSet("rolls", array(7));
		$table->play(1);
		$this->assertEquals($table->getStats()["wins"], 1);
		$this->assertEquals($table->getStats()["losses"], 0);
		$this->assertEquals($player->getStats()["balance"], 110);
		$this->assertEquals($player2->getStats()["balance"], 110);

		$table->debugSet("rolls", array(2));
		$table->play(1);
		$this->assertEquals($table->getStats()["wins"], 1);
		$this->assertEquals($table->getStats()["losses"], 1);
		$this->assertEquals($player->getStats()["balance"], 100);
		$this->assertEquals($player2->getStats()["balance"], 100);

		$table->debugSet("rolls", array(6, 6));
		$table->play(1);
		$this->assertEquals($table->getStats()["wins"], 2);
		$this->assertEquals($table->getStats()["losses"], 1);
		$this->assertEquals($player->getStats()["balance"], 110);
		$this->assertEquals($player2->getStats()["balance"], 112);

    } // End of testTable()


} // End of TableTest class


