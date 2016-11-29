<?php

class SEB_Admin_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'SEB_Admin') );
	}

	function test_class_access() {
		$this->assertTrue( subject_expertise_bios()->admin instanceof SEB_Admin );
	}
}
