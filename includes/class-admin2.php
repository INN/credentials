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
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error(
				'chat_settings_messages',		// $setting
				'chat_settings_message',		// $code
				__('Settings Saved', $this->plugin_name ),	// $message
				'updated'					// $type
			);
		}
		?>
		<div class="wrap">
			<h1><?= esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'chat_settings' );		// $option_group
				do_settings_sections( 'subject_expertise_bios' );	// $page
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Initialize Settings Settings, Sections, and Fields
	 *
	 * @since    1.0.0
	 */
	public function settings_init() {
		register_setting( 'chat_settings', 'chat_settings' );
		add_settings_section(
			'settings_section',					// $id
			'',							// $title
			array( $this, 'settings_callback' ),			// $callback
			'subject_expertise_bios'				// $page
		);
		add_settings_field(
			'user',							// $id
			__('User', $this->plugin_name ),			// $title
			array( $this, 'settings_fields_callback' ),		// $callback
			'subject_expertise_bios',				// $page
			'chat_settings_section',				// $section
			array(
				'input'	=> 'user'
			)
		);
		add_settings_field(
			'category',						// $id
			__('Category', $this->plugin_name ),			// $title
			array( $this, 'settings_fields_callback' ),		// $callback
			'subject_expertise_bios',				// $page
			'chat_settings_section',				// $section
			array(
				'input'	=> 'category'
			)
		);
	}

	/**
	 * Callback function for settings section
	 *
	 * @since    1.0.0
	 */
	public function settings_callback( $args ) {
	}

	/**
	 * Callback for Site ID field
	 *
	 * @since    1.0.0
	 */
	public function settings_fields_callback( $args ) {
		$options = get_option( 'kenzodb_chat_settings' );
		if ( 'login_method' == $args['input'] ) {
			echo '<select name="kenzodb_chat_settings[' . $args['input'] . ']" >';
				echo '<option value="anonymous" ' . selected( $options[$args['input']], 'anonymous', false ) .'>Anonymous Allowed</option>';
				echo '<option value="wordpress" ' . selected( $options[$args['input']], 'wordpress', false ) .'>Require User Login</option>';
			echo '</select>';
			switch( $options[$args['input']] ) {
				case 'anonymous':
				default:
					echo '<p class="description">' . esc_html__( 'Users can participate in chat anonymously without providing any data to KenzoDB.', $this->plugin_name ) . '</p>';
					break;
				case 'wordpress':
					echo '<p class="description">' . esc_html__( 'Users will be prompted for consent to allow their email address and user id to be send to KenzoDB before they can participate in chat..', $this->plugin_name ) . '</p><p class="description">' . esc_html__( 'Users can still view the chat anonymously.', $this->plugin_name ) . '</p>';
					break;
			}
		} else {
			echo '<input name="kenzodb_chat_settings[' . $args['input'] . ']" type="text" value="' . $options[$args['input']] . '" />';
			echo '<p class="description">' . esc_html__( 'Description goes here.', $this->plugin_name ) . '</p>';
		}
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
