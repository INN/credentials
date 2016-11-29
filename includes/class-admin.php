<?php
/**
 * Subject Expertise Bios Admin
 *
 * @since NEXT
 * @package Subject Expertise Bios
 */

/**
 * Subject Expertise Bios Admin.
 *
 * @since NEXT
 */
class SEB_Admin {
	/**
	 * Parent plugin class
	 *
	 * @var   Subject_Expertise_Bios
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  Subject_Expertise_Bios $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
	}
}
