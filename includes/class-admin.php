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
	 * Add options page to Settings menu
	 *
	 * @since    1.0.0
	 */
	public function submenu_page() {
		add_submenu_page(
			'edit.php',			// $parent_slug
			'Subject Expertise Bios',	// $page_title
			'Subject Expertise Bios',	// $menu_title
			'manage_options',		// $capability
			'subject_expertise_bios',	// $menu_slug
			array( $this, settings_page )	// $function
		);
	}

	/**
	 * Build the settings page
	 *
	 * @since    1.0.0
	 */
	public function settings_page() {
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_action( 'admin_menu', array( $this, 'submenu_page' ) );
	}
}
