<?php

class HelloWorldTest extends PHPUnit_Framework_TestCase {

	protected function setup() {
		$this->key = "value";
	}

	protected function teardown() {
		$this->key = "";
	}

    public function testHello() {

		$this->assertEquals(1, 1);
		$this->assertNotEquals(1, 2);
		$this->assertTrue(true);
		$this->assertFalse(false);
		$this->assertGreaterThan(1, 2);
		$this->assertLessThan(2, 1);

		$this->assertEquals($this->key, "value");

    } // End of testHello()


} // End of HelloWorldTest class

