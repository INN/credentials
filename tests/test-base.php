<?php

class BaseTest extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'Subject_Expertise_Bios') );
	}
	
	function test_get_instance() {
		$this->assertTrue( subject_expertise_bios() instanceof Subject_Expertise_Bios );
	}
}
