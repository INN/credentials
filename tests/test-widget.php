<?php

class C_Widget_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'C_Widget') );
	}

	function test_class_access() {
		$this->assertTrue( credentials()->widget instanceof C_Widget );
	}
}
