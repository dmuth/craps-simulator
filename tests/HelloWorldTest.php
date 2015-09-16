<?php

class HelloWorldTest extends PHPUnit_Framework_TestCase {


    public function testHello() {

		$this->assertEquals(1, 1);
		$this->assertNotEquals(1, 2);
		$this->assertTrue(true);
		$this->assertFalse(false);
		$this->assertGreaterThan(1, 2);
		$this->assertLessThan(2, 1);

    } // End of testHello()


} // End of HelloWorldTest class

