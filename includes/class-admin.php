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
	 * Display Subject Expertise
	 *
	 * @since    1.0.0
	 */
	public function profile_expertise_display( $user ) {
		global $wpdb;
		$taxonomy_bios = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE meta_key LIKE 'term_%_bio'" );

		?>
		<h3>Subject Expertise</h3>
		<table class="wp-list-table widefat fixed striped users">
			<thead>
				<tr>
					<th scope="col" id="category" class="manage-column column-category">Category</th>
					<th scope="col" id="biography" class="manage-column column-biography">Biography</th>
				</tr>
			</thead>

			<tbody id="the-list" data-wp-lists="list:user">

				<?php
				$count = 0;
				foreach ( $taxonomy_bios as $bio ) {
					$category_id = filter_var( $bio->meta_key, FILTER_SANITIZE_NUMBER_INT );
					$category = get_category( $category_id );

					if ( 0 == $count % 2 ) {
						$row_class = 'even';
					}
					else {
						$row_class = 'odd';
					}

					echo '<tr class="odd">';
						echo '<td class="category column-category" data-colname="Category" name="' . $category_id . '">' . $category->name . '</td>';
						echo '<td class="biography column-biography" data-colname="Biography">' . $bio->meta_value . '</td>';
					echo '</tr>';
				} ?>
				<tr class="odd">
					<td>
						<select name="category" id="category">
							<?php
							foreach ( get_categories() as $category ) {
								echo '<option value=' . $category->term_id . '>' . $category->name . '</option>';
							}
							?>
						</select>
					</td>
					<td>
						<textarea name="topic_bio" id="topic_bio" rows="5" cols="30"></textarea>
						<p class="description">Please enter background information and credentials regarding the author's expertise on this subject.</p>
					</td>
				</tr>
			</tbody>

			<tfoot>
				<tr>
					<th scope="col" id="category" class="manage-column column-category">Category</th>
					<th scope="col" id="biography" class="manage-column column-biography">Biography</th>
				</tr>
			</tfoot>
		</table>

	<?php }

	/**
	 * Save Subject Expertise
	 *
	 * @since    1.0.0
	 */
	public function profile_expertise_save( $user_id ) {

		if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

		update_usermeta( absint( $user_id ), 'term_' . $_POST['category'] . '_bio', wp_kses_post( $_POST['topic_bio'] ) );
	}

	/**
	 * Add options page to Settings menu
	 *
	 * @since    1.0.0
	 */
	public function submenu_page() {
		add_users_page(
			'Subject Expertise Bios',	// $page_title
			'Add Subject Expertise',	// $menu_title
			'manage_options',		// $capability
			'add_taxonomy_bio',		// $menu_slug
			array( $this, create_user_tax_metadata )	// $function
		);
	}

	/**
	 * Build the settings page
	 *
	 * @since    1.0.0
	 */
	public function create_user_tax_metadata() {
		if ( ! class_exists( 'WP_List_Table' ) ) {
		    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}

		$categories = get_categories();
		?>
		<h3>Extra profile information</h3>

		<table class="form-table">
			<tr>
				<th><label for="user_id">User ID</label></th>
				<td>
					<input type="text" name="user_id" id="user_id" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="twitter">Category</label></th>
				<td>
					<select>
						<?php
						foreach ( $categories as $category ) {
							echo '<option>' . $category->name . '</option>';
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="category">Bio</label></th>
				<td>
					<textarea name="description" id="description" rows="5" cols="30"></textarea>
					<p class="description">Please enter background information and credentials regarding the author's expertise on this subject.</p>
				</td>
			</tr>
		</table>
		<?php
	}


	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		add_action( 'show_user_profile', array( $this, 'profile_expertise_display' ) );
		add_action( 'edit_user_profile', array( $this, 'profile_expertise_display' ) );
		add_action( 'personal_options_update', array( $this, 'profile_expertise_save' ) );
		add_action( 'edit_user_profile_update', array( $this, 'profile_expertise_save' ) );
		add_action( 'admin_menu', array( $this, 'submenu_page' ) );
	}
}
